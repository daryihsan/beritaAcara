<?php
namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\User;
use App\Services\BeritaAcaraService;
use App\Exports\BeritaAcaraExport;
use App\Http\Requests\StoreBeritaAcaraRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Helpers\DateHelper;

class BeritaAcaraController extends Controller
{
    protected $beritaAcaraService;

    // Inject Service
    public function __construct(BeritaAcaraService $beritaAcaraService)
    {
        $this->beritaAcaraService = $beritaAcaraService;
    }

    public function index(Request $request)
    {
        // Logika dashboard vs tabel dipisah agar bersih
        if (!$request->has('tahun')) {
            return view('dashboard.menu');
        }

        $data = $this->beritaAcaraService->getBapData($request->tahun, auth()->user());
        $tahun = $request->tahun;
        $allPetugas = User::where('role', '!=', 'admin')->orderBy('name')->get();

        return view('bap.index', compact('data', 'tahun', 'allPetugas'));
    }

    public function create()
    {
        $petugas = User::whereNotNull('pangkat')->whereNotNull('jabatan')->get();
        return view('bap.form', compact('petugas'));
    }

    public function store(Request $request)
    {
        // Mapping data untuk database
        $dbData = $request->only([
            'no_surat_tugas',
            'tgl_surat_tugas',
            'tanggal_pemeriksaan',
            'hari',
            'objek_nama',
            'objek_alamat',
            'hasil_pemeriksaan',
            'kepala_balai_text',
            'objek_kota',
            'dalam_rangka',
            'yang_diperiksa',
        ]);

        $dbData['tanggal_pemeriksaan'] = $request->tanggal;
        $dbData['created_by'] = auth()->id();

        $petugasData = [
            'nip' => $request->petugas_nip,
            'pangkat' => $request->petugas_pangkat,
            'jabatan' => $request->petugas_jabatan,
            'ttd' => $request->petugas_ttd,
        ];

        $ba = $this->beritaAcaraService->storeBap($dbData, $petugasData);

        // Ambil tahun dari input tanggal untuk redirect ke dashboard yang benar
        $tahun = date('Y', strtotime($request->tanggal));

        // REDIRECT KE DASHBOARD + BAWA ID UNTUK DICETAK
        return redirect()->route('dashboard', ['tahun' => $tahun])
            ->with('success', 'Data Berita Acara berhasil disimpan.')
            ->with('print_pdf_id', $ba->id);
    }

    public function edit($id)
    {
        $ba = $this->beritaAcaraService->getBapById($id);

        // Proteksi: Hanya admin atau pembuat dokumen/petugas terkait yang boleh edit
        $isPetugasTerlibat = $ba->petugas->contains('nip', auth()->user()->nip);

        if (!auth()->user()->isAdmin() && !$isPetugasTerlibat) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit dokumen ini karena nama Anda tidak tercantum sebagai petugas.');
        }

        $petugas = User::whereNotNull('pangkat')->whereNotNull('jabatan')->get();

        // Kirim variabel $ba ke view agar form terisi otomatis
        return view('bap.form', compact('petugas', 'ba'));
    }

    public function update(Request $request, $id)
    {
        $dbData = $request->only([
            'no_surat_tugas',
            'tgl_surat_tugas',
            'tanggal_pemeriksaan',
            'hari',
            'objek_nama',
            'objek_alamat',
            'hasil_pemeriksaan',
            'kepala_balai_text',
            'objek_kota',
            'dalam_rangka',
            'yang_diperiksa',
        ]);

        $dbData['tanggal_pemeriksaan'] = $request->tanggal;
        // 'created_by' TIDAK DIUPDATE agar history pembuat tetap ada

        $petugasData = [
            'nip' => $request->petugas_nip,
            'pangkat' => $request->petugas_pangkat,
            'jabatan' => $request->petugas_jabatan,
            'ttd' => $request->petugas_ttd,
        ];

        $this->beritaAcaraService->updateBap($id, $dbData, $petugasData);

        // Ambil tahun dari input tanggal agar user kembali ke list tahun yang sesuai
        $tahun = date('Y', strtotime($request->tanggal));

        // REDIRECT KE DASHBOARD + BAWA ID UNTUK DICETAK
        return redirect()->route('dashboard', ['tahun' => $tahun])
            ->with('success', value: 'Perubahan berhasil disimpan!')
            ->with('print_pdf_id', $id);
    }

    public function pdf($id)
    {
        $ba = BeritaAcara::with('petugas')->findOrFail($id);

        // Proteksi Akses
        if (!auth()->user()->isAdmin() && !$ba->petugas->contains('nip', auth()->user()->nip)) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Format data agar sesuai dengan struktur view PDF
        $list_petugas = $ba->petugas->map(function ($p) {
            return [
                'nama' => $p->name, // Perbaiki 'nama' jadi 'name' sesuai view
                'pangkat' => $p->pivot->pangkat ?? '-',
                'jabatan' => $p->pivot->jabatan ?? '-',
                'nip' => $p->nip,
                'ttd' => $p->pivot->ttd ?? null,
            ];
        });

        // Mapping Data untuk PDF
        $data = [
            'tanggal' => $ba->tanggal_pemeriksaan,
            'hari' => $ba->hari,
            'no_surat_tugas' => $ba->no_surat_tugas,
            'tgl_surat_tugas' => $ba->tgl_surat_tugas,
            'kepala_balai_text' => $ba->kepala_balai_text,
            'objek_nama' => $ba->objek_nama,
            'objek_alamat' => $ba->objek_alamat,
            'objek_kota' => $ba->objek_kota,
            'dalam_rangka' => $ba->dalam_rangka,
            'hasil_pemeriksaan' => $ba->hasil_pemeriksaan,
            'yang_diperiksa' => $ba->yang_diperiksa,
        ];

        return $this->beritaAcaraService->generatePdf($data, $list_petugas);
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin())
            abort(403);

        $ba = BeritaAcara::findOrFail($id);
        $ba->petugas()->detach();
        $ba->delete();

        return back()->with('success', 'Berita Acara berhasil dihapus.');
    }

    public function adminIndex()
    {
        // Pastikan hanya admin
        if (!auth()->user()->isAdmin())
            abort(403);

        // Ambil semua data lewat service (parameter tahun di-null-kan agar semua keluar)
        $data = $this->beritaAcaraService->getBapData(null, auth()->user());
        $allPetugas = User::where('role', '!=', 'admin')->orderBy('name')->get();

        return view('bap.index', compact('data', 'allPetugas'));
    }

    // ==========================================
    // FITUR REKAPITULASI (EXCEL & PDF LIST)
    // ==========================================

    public function exportExcel(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $filterNip = $request->filter_petugas; // Ambil dari dropdown

        // --- LOGIKA JUDUL & INFO ---
        $judul = $tahun == 'semua'
            ? "SEMUA DATA (TERBARU - TERLAMA)"
            : "TAHUN " . $tahun;

        $infoPetugas = null;

        // Cek siapa yang request?
        if (!auth()->user()->isAdmin()) {
            // Kalau Petugas: Otomatis nama dia
            $infoPetugas = "Petugas: " . auth()->user()->name;
        } elseif ($filterNip && $filterNip !== 'semua') {
            // Kalau Admin pilih filter: Cari nama petugas yang dipilih
            $p = User::where('nip', $filterNip)->first();
            if ($p)
                $infoPetugas = "Nama Petugas: " . $p->name;
        }

        // Panggil Service dengan parameter baru ($filterNip)
        $data = $this->beritaAcaraService->getBapData($tahun, auth()->user(), $filterNip);

        $namaFile = 'Rekap_BAP_' . date('Ymd_His') . '.xlsx';

        // Kirim $judul DAN $infoPetugas ke Class Export
        return Excel::download(new BeritaAcaraExport($data, $judul, $infoPetugas), $namaFile);
    }

    public function exportPdfList(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $filterNip = $request->filter_petugas;

        $judul = $tahun == 'semua' ? "SEMUA DATA" : "TAHUN " . $tahun;
        $infoPetugas = null;

        if (!auth()->user()->isAdmin()) {
            $infoPetugas = "Petugas: " . auth()->user()->name;
        } elseif ($filterNip && $filterNip !== 'semua') {
            $p = User::where('nip', $filterNip)->first();
            if ($p)
                $infoPetugas = "Nama Petugas: " . $p->name;
        }

        $data = $this->beritaAcaraService->getBapData($tahun, auth()->user(), $filterNip);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.bap_rekap_pdf', [
            'data' => $data,
            'labelHeader' => $judul,
            'infoPetugas' => $infoPetugas // Kirim ke View PDF
        ]);

        return $pdf->setPaper('a4', 'landscape')->stream('Laporan.pdf');
    }
}
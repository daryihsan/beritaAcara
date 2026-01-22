<?php
namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\User;
use App\Services\BeritaAcaraService;
use App\Exports\BeritaAcaraExport;
use App\Http\Requests\StoreBeritaAcaraRequest;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Helpers\DateHelper;
use Carbon\Carbon;

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
        // 1. Cek Parameter Tahun (Redirect jika kosong)
        if (!$request->has('tahun') && !$request->ajax()) {
            return view('dashboard.menu');
        }
        // 2. REQUEST AJAX (Server-Side DataTables)
        // Kita cek $request->ajax() ATAU parameter 'draw' agar tidak salah deteksi
        if ($request->ajax() || $request->has('draw')) {
            $user = auth()->user();
            // Ambil Query dari Service (Tanpa ->get)
            $query = $this->beritaAcaraService->getBapQuery(
                $request->tahun, 
                $user, 
                $request->filter_petugas
            );
            return $this->dataTableConfig($query, $user);
        }

        // 3. TAMPILAN AWAL (Bukan Ajax)
        $tahun = $request->tahun ?? date('Y');
        $allPetugas = User::where('role', '!=', 'admin')->orderBy('name')->get();

        // Kirim data kosong [], karena nanti diisi otomatis oleh Ajax
        return view('bap.index', [
            'data' => [], 
            'tahun' => $tahun, 
            'allPetugas' => $allPetugas
        ]);
    }


    public function adminIndex(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        // Tambahkan Logika AJAX di sini juga!
        if ($request->ajax() || $request->has('draw')) {
            $user = auth()->user();
            // null pada parameter tahun artinya ambil SEMUA tahun
            $query = $this->beritaAcaraService->getBapQuery(null, $user, $request->filter_petugas);
            return $this->dataTableConfig($query, $user);
        }
        // Tampilan Awal Admin
        $allPetugas = User::where('role', '!=', 'admin')->orderBy('name')->get();
        return view('bap.index', ['data' => [], 'allPetugas' => $allPetugas]);
    }

    public function create()
    {
        $petugas = User::whereNotNull('pangkat')->whereNotNull('jabatan')->get();
        return view('bap.form', compact('petugas'));
    }

    public function store(Request $request)
    {
        // Mapping data untuk database
        $dbData = $this->getCommonData($request);

        $dbData['created_by'] = auth()->id();

        $petugasData = $this->getPetugasData($request);

        $ba = $this->beritaAcaraService->storeBap($dbData, $petugasData);

        return $this->redirectAfterSave($request->tanggal, $ba->id, 'Data Berita Acara berhasil disimpan.');
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
        $dbData = $this->getCommonData($request);
        // 'created_by' TIDAK DIUPDATE agar history pembuat tetap ada

        $petugasData = $this->getPetugasData($request);

        $this->beritaAcaraService->updateBap($id, $dbData, $petugasData);

        return $this->redirectAfterSave($request->tanggal, $id, 'Perubahan berhasil disimpan!');
    }

    /**
     * Mengambil inputan form yang SAMA antara create & update
     */
    private function getCommonData(Request $request)
    {
        $data = $request->only([
            'no_surat_tugas', 'tgl_surat_tugas', 'hari',
            'objek_nama', 'objek_alamat', 'hasil_pemeriksaan',
            'kepala_balai_text', 'objek_kota', 'dalam_rangka', 'yang_diperiksa'
        ]);
        
        // Override nama field tanggal
        $data['tanggal_pemeriksaan'] = $request->tanggal;
        return $data;
    }

    /**
     * Mengambil inputan array petugas
     */
    private function getPetugasData(Request $request)
    {
        return [
            'nip'     => $request->petugas_nip,
            'pangkat' => $request->petugas_pangkat,
            'jabatan' => $request->petugas_jabatan,
            'ttd'     => $request->petugas_ttd,
        ];
    }

    /**
     * Helper Redirect agar tidak copy-paste logic redirect
     */
    private function redirectAfterSave($tanggal, $id, $message)
    {
        $tahun = date('Y', strtotime($tanggal));
        return redirect()->route('dashboard', ['tahun' => $tahun])
            ->with('success', $message)
            ->with('print_pdf_id', $id);
    }

    /**
     * Konfigurasi DataTables (Dipakai index & adminIndex agar tidak duplikat kode)
     */
    private function dataTableConfig($query, $user)
    {
    return DataTables::of($query)
        ->addIndexColumn() // Kolom No (DT_RowIndex)
        ->addColumn('petugas_names', function($row){
            // Render List Petugas (Persis style original)
            // Tidak pakai <ul> default browser, tapi pakai styling text
            $html = '<div>';
            foreach($row->petugas as $p) {
                $html .= '<span>' . e($p->name) . '</span><br>';
            }
            $html .= '</div>';
            return $html;
        })
        ->editColumn('tanggal_pemeriksaan', function($row){
            return Carbon::parse($row->tanggal_pemeriksaan)->format('d M Y');
        })
        ->addColumn('tanggal_bap', function($row){
            $tgl = $row->tanggal_berita_acara ?? $row->created_at ?? $row->tanggal_pemeriksaan;
            return Carbon::parse($tgl)->format('d M Y');
        })
        ->addColumn('action', function($row) use ($user) {
            // --- RENDER HTML TOMBOL (PERSIS SEPERTI DI VIEW ASLI) ---
            $btn = '<div class="flex items-center justify-center gap-2">';
            
            // Tombol PDF
            $urlPdf = route('berita-acara.pdf', $row->id);
            $btn .= '<a href="'.$urlPdf.'" target="_blank" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-print"></span> PDF</a>';
            
            // Cek Hak Akses Edit
            $isPetugas = $row->petugas->contains('nip', $user->nip);
            if($user->isAdmin() || $isPetugas) {
                $urlEdit = route('berita-acara.edit', $row->id);
                $btn .= ' <a href="'.$urlEdit.'" class="btn btn-warning btn-sm text-white"><span class="glyphicon glyphicon-edit"></span> Edit</a>';
            }

            // Tombol Hapus (Admin Only)
            if($user->isAdmin()) {
                $urlDelete = route('berita-acara.destroy', $row->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');
                // Pesan konfirmasi asli Anda
                $btn .= '<form action="'.$urlDelete.'" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus data rusak/palsu/rekayasa ini?\')" class="m-0 inline-block">
                            '.$csrf.' '.$method.'
                            <button type="submit" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Hapus</button>
                            </form>';
            }
            
            $btn .= '</div>';
            return $btn;
        })
        ->rawColumns(['petugas_names', 'action']) // Izinkan HTML
        ->make(true);
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
                'nama' => $p->name, 'pangkat' => $p->pivot->pangkat ?? '-',
                'jabatan' => $p->pivot->jabatan ?? '-', 'nip' => $p->nip,
                'ttd' => $p->pivot->ttd ?? null,
            ];
        });
        // Mapping Data untuk PDF
        $data = [
            'tanggal' => $ba->tanggal_pemeriksaan, 'hari' => $ba->hari,
            'no_surat_tugas' => $ba->no_surat_tugas, 'tgl_surat_tugas' => $ba->tgl_surat_tugas,
            'kepala_balai_text' => $ba->kepala_balai_text, 'objek_nama' => $ba->objek_nama,
            'objek_alamat' => $ba->objek_alamat, 'objek_kota' => $ba->objek_kota,
            'dalam_rangka' => $ba->dalam_rangka, 'hasil_pemeriksaan' => $ba->hasil_pemeriksaan,
            'yang_diperiksa' => $ba->yang_diperiksa,
        ];
        return $this->beritaAcaraService->generatePdf($data, $list_petugas);
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        $this->beritaAcaraService->deleteBap($id);
        return back()->with('success', 'Berita Acara berhasil dihapus.');
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
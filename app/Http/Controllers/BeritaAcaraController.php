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
use Illuminate\Support\Facades\Log;

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
        // Cek Parameter Tahun (Redirect jika kosong)
        if (!$request->has('tahun') && !$request->ajax()) {
            return view('dashboard.menu');
        }
        // Request Ajax (Server-Side DataTables)
        if ($request->ajax() || $request->has('draw')) {
            try{
                $user = auth()->user();
                // Ambil Query dari Service (Tanpa ->get)
                $query = $this->beritaAcaraService->getBapQuery(
                    $request->tahun,
                    $user,
                    $request->filter_petugas
                );
                return $this->dataTableConfig($query, $user);
            } catch (\Exception $e) {
                Log::error('Gagal mengambil data BAP: ' . $e->getMessage());

                return response()->json([
                    'message' => 'Gagal mengambil data BAP. Silakan coba beberapa saat lagi atau hubungi admin.'
                ], 500);
            }
        }

        // Tampilan Awal (Bukan Ajax)
        $tahun = $request->tahun ?? date('Y');
        $allPetugas = User::where('role', '!=', 'admin')->orderBy('name')->get();

        // Kirim data kosong [], karena diisi otomatis oleh Ajax
        return view('bap.index', [
            'data' => [],
            'tahun' => $tahun,
            'allPetugas' => $allPetugas
        ]);
    }


    public function adminIndex(Request $request)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        // Logika AJAX
        if ($request->ajax() || $request->has('draw')) {
            try{
                $user = auth()->user();
                // ambil semua tahun
                $query = $this->beritaAcaraService->getBapQuery(null, $user, $request->filter_petugas);
                return $this->dataTableConfig($query, $user);
            } catch (\Exception $e) {
                Log::error('Gagal mengambil data BAP: ' . $e->getMessage());

                return response()->json([
                    'message' => 'Gagal mengambil data BAP. Silakan coba beberapa saat lagi.'
                ], 500);
            }
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
        try{
            // Mapping data untuk database
            $dbData = $this->getCommonData($request);

            $dbData['created_by'] = auth()->id();

            $petugasData = $this->getPetugasData($request);

            $ba = $this->beritaAcaraService->storeBap($dbData, $petugasData);

            return $this->redirectAfterSave($request->tanggal, $ba->id, 'Data Berita Acara berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan BAP Baru: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['system_error' => 'Maaf, terjadi kesalahan sistem saat menyimpan data. Silakan coba beberapa saat lagi.']);
        }
    }

    public function edit($id)
    {
        $ba = $this->beritaAcaraService->getBapById($id);

        // Hanya admin atau pembuat dokumen/petugas terkait yang boleh edit
        $isPetugasTerlibat = $ba->petugas->contains('nip', auth()->user()->nip);

        if (!auth()->user()->isAdmin() && !$isPetugasTerlibat) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit dokumen ini karena nama Anda tidak tercantum sebagai petugas.');
        }

        $petugas = User::whereNotNull('pangkat')->whereNotNull('jabatan')->get();

        // Kirim variabel $ba ke view agar form terisi
        return view('bap.form', compact('petugas', 'ba'));
    }

    public function update(Request $request, $id)
    {
        try{
            $dbData = $this->getCommonData($request);
            // 'created_by' tidak di update agar history pembuat tetap ada

            $petugasData = $this->getPetugasData($request);

            $this->beritaAcaraService->updateBap($id, $dbData, $petugasData);

            return $this->redirectAfterSave($request->tanggal, $id, 'Perubahan berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate BAP ID ' . $id . ': ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['system_error' => 'Maaf, terjadi kesalahan sistem saat menyimpan perubahan data. Silakan coba beberapa saat lagi.']);
        }
    }

    /**
     * Mengambil inputan form yang SAMA antara create & update
     */
    private function getCommonData(Request $request)
    {
        $data = $request->only([
            'no_surat_tugas',
            'tgl_surat_tugas',
            'hari',
            'objek_nama',
            'objek_alamat',
            'hasil_pemeriksaan',
            'kepala_balai_text',
            'objek_kota',
            'dalam_rangka',
            'yang_diperiksa'
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
            'nip' => $request->petugas_nip,
            'pangkat' => $request->petugas_pangkat,
            'jabatan' => $request->petugas_jabatan,
            'ttd' => $request->petugas_ttd,
        ];
    }

    /**
     *  Redirect setelah simpan data (create/update)
     */
    private function redirectAfterSave($tanggal, $id, $message)
    {
        $tahun = date('Y', strtotime($tanggal));
        return redirect()->route('dashboard', ['tahun' => $tahun])
            ->with('success', $message)
            ->with('print_pdf_id', $id);
    }

    /**
     * Konfigurasi DataTables
     */
    private function dataTableConfig($query, $user)
    {
        return DataTables::of($query)
            ->filter(function ($instance) {
                $request = request(); 

                if (!empty($request->get('search')['value'])) {
                    $keyword = $request->get('search')['value'];

                    // override cara pencarian
                    $instance->where(function($w) use ($keyword) {
                        // Cari di No Surat
                        $w->where('no_surat_tugas', 'LIKE', "%{$keyword}%")
                          // Cari di Nama Objek
                          ->orWhere('objek_nama', 'LIKE', "%{$keyword}%")
                          // Cari di Nama Petugas (Relasi)
                          ->orWhereHas('petugas', function($q) use ($keyword) {
                              $q->where('name', 'LIKE', "%{$keyword}%");
                          });
                    });
                }
            })
            ->addIndexColumn() // Kolom No (DT_RowIndex)
            ->addColumn('petugas_names', function ($row) {
                // Render List Petugas
                // Tidak pakai <ul> default browser, tapi pakai styling text
                $html = '<div>';
                foreach ($row->petugas as $p) {
                    $html .= '<span>' . e($p->name) . '</span><br>';
                }
                $html .= '</div>';
                return $html;
            })
            ->editColumn('tanggal_pemeriksaan', function ($row) {
                return Carbon::parse($row->tanggal_pemeriksaan)->format('d M Y');
            })
            ->addColumn('tanggal_bap', function ($row) {
                $tgl = $row->tanggal_berita_acara ?? $row->created_at ?? $row->tanggal_pemeriksaan;
                return Carbon::parse($tgl)->format('d M Y');
            })
            ->addColumn('action', function ($row) use ($user) {
                // RENDER HTML TOMBOL 
                $btn = '<div class="flex items-center justify-center gap-2">';

                // Tombol PDF
                $urlPdf = route('berita-acara.pdf', $row->id);
                $btn .= '<a href="' . $urlPdf . '" target="_blank" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-print"></span> PDF</a>';

                // Cek Hak Akses Edit
                $isPetugas = $row->petugas->contains('nip', $user->nip);
                if ($user->isAdmin() || $isPetugas) {
                    $urlEdit = route('berita-acara.edit', $row->id);
                    $btn .= ' <a href="' . $urlEdit . '" class="btn btn-warning btn-sm text-white"><span class="glyphicon glyphicon-edit"></span> Edit</a>';
                }

                // Tombol Hapus (Admin Only)
                if ($user->isAdmin()) {
                    $urlDelete = route('berita-acara.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');
                    // Pesan konfirmasi asli Anda
                    $btn .= '<form action="' . $urlDelete . '" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus data rusak/palsu/rekayasa ini?\')" class="m-0 inline-block">
                            ' . $csrf . ' ' . $method . '
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
        try{
            $ba = BeritaAcara::with('petugas')->findOrFail($id);
            // Proteksi Akses
            if (!auth()->user()->isAdmin() && !$ba->petugas->contains('nip', auth()->user()->nip)) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }
            // Format data agar sesuai dengan struktur view PDF
            $list_petugas = $ba->petugas->map(function ($p) {
                return [
                    'nama' => $p->name,
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
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF BAP ID ' . $id . ': ' . $e->getMessage());
            
            return response()->view('errors.custom', [
                'message' => 'Gagal membuat PDF. Pastikan memori server cukup atau hubungi admin.'
            ], 500);
            // Atau redirect simpel:
            // return redirect()->route('dashboard')->withErrors(['pdf_error' => 'Gagal membuat PDF.']);
        }
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        try{
            $this->beritaAcaraService->deleteBap($id);
            return back()->with('success', 'Berita Acara berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus BAP ID ' . $id . ': ' . $e->getMessage());

            return back()->withErrors(['system_error' => 'Gagal menghapus data. Terjadi kesalahan sistem.']);
        }
    }

    public function exportExcel(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $filterNip = $request->filter_petugas; // Ambil dari dropdown
        // LOGIKA JUDUL & INFO PETUGAS
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
        $namaFile = 'Rekap_BAP_' . $judul . '.xlsx';
        // Kirim $judul DAN $infoPetugas ke Class Export
        return Excel::download(new BeritaAcaraExport($data, $judul, $infoPetugas), $namaFile);
    }

    public function exportPdfList(Request $request)
    {
        try{
            $tahun = $request->tahun ?? date('Y');
            $filterNip = $request->filter_petugas;
            $judul = $tahun == 'semua' ? "SEMUA DATA" : "TAHUN " . $tahun;

            $judulTab = "Rekap BAP - " . ($tahun == 'semua' ? "Semua Periode" : $tahun);
            $namaFile = "Rekap_BAP_" . ($tahun == 'semua' ? "Semua_Periode" : $tahun) . ".pdf";

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
                'infoPetugas' => $infoPetugas, // Kirim ke View PDF
                'judulTab' => $judulTab
            ]);
            return $pdf->setPaper('a4', 'landscape')->stream($namaFile);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF Rekap BAP: ' . $e->getMessage());

            return response()->view('errors.custom', [
                'message' => 'Gagal membuat PDF Rekap. Pastikan memori server cukup atau hubungi admin.'
            ], 500);
        }
    }
}
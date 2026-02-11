<?php
namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\User;
use App\Services\BeritaAcaraService;
use App\Http\Requests\StoreBeritaAcaraRequest;
use App\DataTransferObjects\BapDto;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BeritaAcaraController extends Controller
{
    protected $beritaAcaraService;

    // Inject service
    public function __construct(BeritaAcaraService $beritaAcaraService)
    {
        $this->beritaAcaraService = $beritaAcaraService;
    }

    /**
     * Memproses tampilan data BAP untuk petugas + dashboard
     */
    public function index(Request $request)
    {
        // Cek parameter tahun 
        if (!$request->has('tahun') && !$request->ajax()) {

            $user = auth()->user();

            // Data filter (khusus admin) 
            $targetUserId = $user->id;
            $targetName = "Saya";

            if ($user->isAdmin() && $request->filled('filter_petugas')) {
                $targetUser = User::where('nip', $request->filter_petugas)->first();
                if ($targetUser) {
                    $targetUserId = $targetUser->id;
                    $targetName = $targetUser->name;
                }
            }

            // Query statistik 
            $statsPersonal = BeritaAcara::whereHas('petugas', function ($q) use ($targetUserId) {
                $q->where('users.id', $targetUserId);
            })
                ->selectRaw('YEAR(tanggal_pemeriksaan) as tahun, count(*) as total')
                ->groupBy('tahun')
                ->orderBy('tahun', 'asc')
                ->pluck('total', 'tahun')->toArray();

            $statsGlobal = BeritaAcara::selectRaw('YEAR(tanggal_pemeriksaan) as tahun, count(*) as total')
                ->groupBy('tahun')
                ->orderBy('tahun', 'asc')
                ->pluck('total', 'tahun')->toArray();

            $totalPersonalAllTime = array_sum($statsPersonal);
            $totalGlobalAllTime = array_sum($statsGlobal);
            // Formating data untuk chart 
            $startYear = 2020;
            $endYear = date('Y');
            $allYears = range($startYear, $endYear); // [2020, 2021, ..., 2026]

            $chartData = [
                'labels' => $allYears,
                'personal' => [],
                'global' => []
            ];

            foreach ($allYears as $year) {
                // 0 jika tidak ada data di tahun tersebut
                $chartData['personal'][] = $statsPersonal[$year] ?? 0;
                $chartData['global'][] = $statsGlobal[$year] ?? 0;
            }

            // Data pendukung
            $allPetugas = [];
            if ($user->isAdmin()) {
                $allPetugas = User::where('role', '!=', 'admin')->orderBy('name')->get();
            }

            // Return ke view dashboard Menu
            return view('dashboard.menu', compact(
                'user',
                'allPetugas',
                'chartData',
                'targetName',
                'statsPersonal',
                'statsGlobal',
                'totalPersonalAllTime',
                'totalGlobalAllTime'
            ));
        }
        // Request ajax (server-side dataTables)
        if ($request->ajax() || $request->has('draw')) {
            try {
                $user = auth()->user();
                // Ambil query dari service
                $query = $this->beritaAcaraService->getBapQuery(
                    $request->tahun,
                    $user,
                    $request->filter_petugas
                );
                return $this->dataTableConfig($query, $user);
            } catch (\Throwable $e) {
                Log::error('Gagal mengambil data BAP: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Gagal mengambil data BAP. Silakan coba beberapa saat lagi atau hubungi admin.'
                ], 500);
            }
        }
        // Tampilan awal 
        $tahun = $request->tahun ?? date('Y');
        $allPetugas = User::where('role', '!=', 'admin')->orderBy('name')->get();

        // Kirim data kosong [], karena diisi oleh Ajax
        return view('bap.index', [
            'data' => [],
            'tahun' => $tahun,
            'allPetugas' => $allPetugas
        ]);
    }

    /**
     * Memproses tampilan data BAP untuk admin
     */
    public function adminIndex(Request $request)
    {
        if (!auth()->user()->isAdmin())
            abort(403);
        // Logika AJAX
        if ($request->ajax() || $request->has('draw')) {
            try {
                $user = auth()->user();
                // Ambil semua tahun
                $query = $this->beritaAcaraService->getBapQuery(null, $user, $request->filter_petugas);
                return $this->dataTableConfig($query, $user);
            } catch (\Throwable $e) {
                Log::error('Gagal mengambil data BAP: ' . $e->getMessage());

                return response()->json([
                    'message' => 'Gagal mengambil data BAP. Silakan coba beberapa saat lagi.'
                ], 500);
            }
        }
        // Tampilan awal Admin
        $allPetugas = User::where('role', '!=', 'admin')->orderBy('name')->get();
        return view('bap.index', ['data' => [], 'allPetugas' => $allPetugas]);
    }

    public function create()
    {
        $petugas = User::whereNotNull('pangkat')->whereNotNull('jabatan')->get();
        return view('bap.form', compact('petugas'));
    }

    /**
     * Menyimpan data BAP
     */
    public function store(StoreBeritaAcaraRequest $request)
    {
        try {
            // Mapping data untuk database
            $bapDto = BapDto::fromRequest($request);
            $ba = $this->beritaAcaraService->storeBap($bapDto);

            return $this->redirectAfterSave($request->tanggal, $ba->id, 'Data Berita Acara berhasil disimpan.');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan BAP Baru: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['system_error' => 'Maaf, terjadi kesalahan sistem saat menyimpan data. Silakan coba beberapa saat lagi.']);
        }
    }

    /**
     * Mengedit data BAP
     */
    public function edit($id)
    {
        $ba = $this->beritaAcaraService->getBapById($id);

        // Hanya admin atau pembuat dokumen/petugas terkait yang boleh edit
        $this->authorize('update', $ba);
        $petugas = User::whereNotNull('pangkat')->whereNotNull('jabatan')->get();

        // Kirim variabel $ba ke view agar form terisi
        return view('bap.form', compact('petugas', 'ba'));
    }

    /**
     * Mengupdate data BAP
     */
    public function update(StoreBeritaAcaraRequest $request, $id)
    {
        try {
            $ba = BeritaAcara::findOrFail($id);
            $this->authorize('update', $ba);
            $bapDto = BapDto::fromRequest($request);
            $this->beritaAcaraService->updateBap($id, $bapDto);

            return $this->redirectAfterSave($request->tanggal, $id, 'Perubahan berhasil disimpan!');
        } catch (\Throwable $e) {
            Log::error('Gagal mengupdate BAP ID ' . $id . ': ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['system_error' => 'Maaf, terjadi kesalahan sistem saat menyimpan perubahan data. Silakan coba beberapa saat lagi.']);
        }
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
                    
                    $keywordTranslated = $keyword;
                    $bulanMap = [
                        'januari' => 'January', 'februari' => 'February', 'maret' => 'March', 
                        'april' => 'April', 'mei' => 'May', 'juni' => 'June', 
                        'juli' => 'July', 'agustus' => 'August', 'september' => 'September', 
                        'oktober' => 'October', 'november' => 'November', 'desember' => 'December',
                        'jan' => 'Jan', 'feb' => 'Feb', 'mar' => 'Mar', 'apr' => 'Apr', 
                        'jun' => 'Jun', 'jul' => 'Jul', 'agu' => 'Aug', 'sep' => 'Sep', 
                        'okt' => 'Oct', 'nov' => 'Nov', 'des' => 'Dec'
                    ];

                    foreach ($bulanMap as $indo => $inggris) {
                        // Cek case-insensitive 
                        if (stripos($keyword, $indo) !== false) {
                            $keywordTranslated = str_ireplace($indo, $inggris, $keyword);
                            break; 
                        }
                    }

                    // Query pencarian
                    $instance->where(function ($w) use ($keyword, $keywordTranslated) {
                        // Cari di Nomor Surat Tugas dan Objek Nama 
                        $w->where('no_surat_tugas', 'LIKE', "%{$keyword}%")
                          ->orWhere('objek_nama', 'LIKE', "%{$keyword}%");

                        // Cek tanggal pemeriksaan
                        $w->orWhereRaw("DATE_FORMAT(tanggal_pemeriksaan, '%d %M %Y') LIKE ?", ["%{$keywordTranslated}%"]) 
                          ->orWhereRaw("DATE_FORMAT(tanggal_pemeriksaan, '%d %b %Y') LIKE ?", ["%{$keywordTranslated}%"])
                          ->orWhereRaw("DATE_FORMAT(tanggal_pemeriksaan, '%d-%m-%Y') LIKE ?", ["%{$keyword}%"]); // Format angka 04-02-2026

                        // Cek Tanggal BAP 
                        $w->orWhereRaw("DATE_FORMAT(COALESCE(created_at, tanggal_pemeriksaan), '%d %M %Y') LIKE ?", ["%{$keywordTranslated}%"])
                          ->orWhereRaw("DATE_FORMAT(COALESCE(created_at, tanggal_pemeriksaan), '%d %b %Y') LIKE ?", ["%{$keywordTranslated}%"])
                          ->orWhereRaw("DATE_FORMAT(COALESCE(created_at, tanggal_pemeriksaan), '%d-%m-%Y') LIKE ?", ["%{$keyword}%"]);

                        // Cari di nama petugas
                        $w->orWhereHas('petugas', function ($q) use ($keyword) {
                            $q->where('name', 'LIKE', "%{$keyword}%");
                        });
                    });
                }
            })
            ->addIndexColumn()
            ->addColumn('petugas_names', function ($row) {
                return view('bap.tablePartials.datatable-petugas', compact('row'))->render();
            })
            // Format tampilan tanggal 
            ->editColumn('tanggal_pemeriksaan', function ($row) {
                return Carbon::parse($row->tanggal_pemeriksaan)->translatedFormat('d F Y');
            })
            ->addColumn('tanggal_bap', function ($row) {
                $tgl = $row->tanggal_berita_acara ?? $row->created_at ?? $row->tanggal_pemeriksaan;
                return Carbon::parse($tgl)->translatedFormat('d F Y');
            })
            ->addColumn('action', function ($row) use ($user) {
                return view('bap.tablePartials.datatable-action', compact('row', 'user'))->render();
            })
            ->rawColumns(['petugas_names', 'action'])
            ->make(true);
    }

    /**
     * Membuat pdf untuk BAP
     */
    public function pdf($id)
    {
        set_time_limit(120);
        try {
            $ba = BeritaAcara::with('petugas')->findOrFail($id);
            // Proteksi akses
            $this->authorize('view', $ba);
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
            // Mapping data untuk PDF
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
        } catch (\Throwable $e) {
            Log::error('Gagal generate PDF BAP ID ' . $id . ': ' . $e->getMessage());

            return response()->view('errors.custom', [
                'message' => 'Gagal membuat PDF. Pastikan memori server cukup atau hubungi admin.'
            ], 500);
        }
    }

    /**
     * Menghapus BAP
     */
    public function destroy($id)
    {
        $ba = BeritaAcara::findOrFail($id);
        $this->authorize('delete', $ba);
        try {
            $this->beritaAcaraService->deleteBap($id);
            return back()->with('success', 'Berita Acara berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus BAP ID ' . $id . ': ' . $e->getMessage());

            return back()->withErrors(['system_error' => 'Gagal menghapus data. Terjadi kesalahan sistem.']);
        }
    }
}
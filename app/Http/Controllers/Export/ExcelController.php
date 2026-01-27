<?php
namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\BeritaAcaraExport;
use App\Services\BeritaAcaraService;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
class ExcelController extends Controller
{
    protected $beritaAcaraService;

    public function __construct(BeritaAcaraService $beritaAcaraService)
    {
        $this->beritaAcaraService = $beritaAcaraService;
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
}
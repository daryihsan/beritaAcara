<?php
namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Services\BeritaAcaraService;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    protected $beritaAcaraService;

    public function __construct(BeritaAcaraService $beritaAcaraService)
    {
        $this->beritaAcaraService = $beritaAcaraService;
    }

    public function exportPdfList(Request $request)
    {
        try {
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
            $pdf = Pdf::loadView('exports.bap_rekap_pdf', [
                'data' => $data,
                'labelHeader' => $judul,
                'infoPetugas' => $infoPetugas, // Kirim ke View PDF
                'judulTab' => $judulTab
            ]);
            return $pdf->setPaper('a4', 'landscape')->stream($namaFile);
        } catch (\Throwable $e) {
            Log::error('Gagal generate PDF Rekap BAP: ' . $e->getMessage());

            return response()->view('errors.custom', [
                'message' => 'Gagal membuat PDF Rekap. Pastikan memori server cukup atau hubungi admin.'
            ], 500);
        }
    }
}
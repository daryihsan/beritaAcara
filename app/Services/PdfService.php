<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\DateHelper;

class PdfService
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Logic asli baris 1035-1101
     */
    public function generateBapPdf($data, $listPetugas)
    {
        // Memory Limit (Logic asli baris 1037)
        ini_set('memory_limit', '512M');

        // 1. Sanitasi (Logic asli baris 1038-1053)
        $fieldsToSanitize = [
            'no_surat_tugas', 'objek_nama', 'objek_alamat',
            'hasil_pemeriksaan', 'objek_kota', 'dalam_rangka', 'yang_diperiksa'
        ];

        foreach ($fieldsToSanitize as $field) {
            if (isset($data[$field])) {
                $data[$field] = htmlspecialchars(strip_tags($data[$field]), ENT_QUOTES, 'UTF-8');
            }
        }

        // 2. Persiapkan Petugas (Logic asli baris 1055-1077)
        // Kita delegasikan konversi gambarnya ke ImageService biar rapi
        if (is_object($listPetugas) && method_exists($listPetugas, 'toArray')) {
            $listPetugas = $listPetugas->toArray();
        }

        foreach ($listPetugas as $key => $petugas) {
            $ttdPath = $petugas['ttd'] ?? null;
            $listPetugas[$key]['ttd'] = $this->imageService->processTtdForPdf($ttdPath);
        }

        // 3. Mapping Data (Logic asli baris 1078-1087)
        $pdfData = [
            'data' => $data,
            'list_petugas' => $listPetugas,
            'teks_tgl' => DateHelper::teksTanggal($data['tanggal']),
            'tgl_st'   => DateHelper::indoLengkap($data['tgl_surat_tugas']),
            'logo'     => $this->imageService->imgBase64('headerpdf.png'),
            'footer'   => $this->imageService->imgBase64('footerpdf.png')
        ];

        // 4. Load View & Stream (Logic asli baris 1088-1094)
        $pdf = Pdf::loadView('bap.pdf', $pdfData);

        $pdf->setOptions([
            'isRemoteEnabled' => true,      // Wajib true agar bisa baca gambar base64/path
            'defaultFont' => 'sans-serif',  
            'isHtml5ParserEnabled' => true, 
            'isFontSubsettingEnabled' => true // PENTING: Hanya load huruf yang dipakai saja ke memori
        ]);
        
        $safeSurat = str_replace(['/', '\\'], '-', $data['no_surat_tugas']);
        $fileName = 'BAP-' . $safeSurat . '.pdf';

        return $pdf->setPaper('A4', 'portrait')->stream($fileName);
    }
}
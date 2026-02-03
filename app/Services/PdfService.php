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
     * Menggenerate PDF BAP
     */
    public function generateBapPdf($data, $listPetugas)
    {
        // Memory limit
        ini_set('memory_limit', '512M');

        // Sanitasi
        $fieldsToSanitize = [
            'no_surat_tugas', 'objek_nama', 'objek_alamat',
            'hasil_pemeriksaan', 'objek_kota', 'dalam_rangka', 'yang_diperiksa'
        ];

        foreach ($fieldsToSanitize as $field) {
            if (isset($data[$field])) {
                $data[$field] = htmlspecialchars(strip_tags($data[$field]), ENT_QUOTES, 'UTF-8');
            }
        }

        // Petugas
        if (is_object($listPetugas) && method_exists($listPetugas, 'toArray')) {
            $listPetugas = $listPetugas->toArray();
        }

        foreach ($listPetugas as $key => $petugas) {
            $ttdPath = $petugas['ttd'] ?? null;
            $listPetugas[$key]['ttd'] = $this->imageService->processTtdForPdf($ttdPath);
        }

        // Mapping data
        $pdfData = [
            'data' => $data,
            'list_petugas' => $listPetugas,
            'teks_tgl' => DateHelper::teksTanggal($data['tanggal']),
            'tgl_st'   => DateHelper::indoLengkap($data['tgl_surat_tugas']),
            'logo'     => $this->imageService->imgPath('headerpdf.png'),
            'footer'   => $this->imageService->imgPath('footerpdf.png')
        ];

        // Load view & stream 
        $pdf = Pdf::loadView('bap.pdf', $pdfData);

        $pdf->setOptions([
            'isRemoteEnabled' => true,              // Bisa baca gambar base64/path
            'chroot' => [                           // Izinkan DomPDF akses folder ini
                public_path(), 
                storage_path('app/private')
            ],
            'defaultFont' => 'sans-serif',  
            'isHtml5ParserEnabled' => true, 
            'isFontSubsettingEnabled' => true       // Hanya load huruf yang dipakai ke memori
        ]);
        
        $safeSurat = str_replace(['/', '\\'], '-', $data['no_surat_tugas']);
        $fileName = 'BAP-' . $safeSurat . '.pdf';

        return $pdf->setPaper('A4', 'portrait')->stream($fileName);
    }
}
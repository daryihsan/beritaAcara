<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BeritaAcaraExport implements FromView, WithColumnWidths, WithStyles
{
    protected $data;
    protected $labelHeader;
    protected $infoPetugas;

    public function __construct($data, $labelHeader, $infoPetugas = null)
    {
        $this->data = $data;
        $this->labelHeader = $labelHeader;
        $this->infoPetugas = $infoPetugas;
    }

    public function view(): View
    {
        return view('exports.bap_rekap_excel', [
            'data' => $this->data,
            'labelHeader' => $this->labelHeader,
            'infoPetugas' => $this->infoPetugas
        ]);
    }

    // Lebar Kolom Excel
    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 30, // No. Surat Tugas
            'C' => 40, // Petugas
            'D' => 15, // Tgl Pemeriksaan
            'E' => 15, // Tgl BAP
            'F' => 35, // Objek 
            'G' => 40, // Alamat 
            'H' => 20, // Kota/Kab 
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A:H')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:H')->getAlignment()->setVertical('top');

        $headerRow = 3;
        if (!empty($this->infoPetugas)) {
            $headerRow = 4;
        }

        $sheet->getStyle('A' . $headerRow . ':H' . $headerRow)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCCCCC');

        return [
            1 => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],
            2 => ['font' => ['italic' => true, 'size' => 12], 'alignment' => ['horizontal' => 'center']],
            $headerRow => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
        ];
    }
}
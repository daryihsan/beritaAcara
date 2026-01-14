<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BeritaAcaraExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $labelHeader;
    protected $infoPetugas; // Properti Baru

    // Update Constructor
    public function __construct($data, $labelHeader, $infoPetugas = null)
    {
        $this->data = $data;
        $this->labelHeader = $labelHeader;
        $this->infoPetugas = $infoPetugas;
    }

    public function view(): View
    {
        return view('exports.bap_rekap', [
            'data' => $this->data,
            'labelHeader' => $this->labelHeader,
            'infoPetugas' => $this->infoPetugas // Kirim ke blade
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], 
            2 => ['font' => ['italic' => true, 'size' => 12]], // Style untuk baris Info Petugas
            4 => ['font' => ['bold' => true]], // Header Tabel turun ke baris 4
        ];
    }
}
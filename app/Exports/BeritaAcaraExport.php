<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
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

    // 2. Tentukan Lebar Kolom di Sini (Satuan Excel, bukan Pixel)
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 30,  // No. Surat Tugas
            'C' => 40,  // Petugas Pemeriksa
            'D' => 15,  // Tgl Pemeriksaan
            'E' => 35,  // Objek
            'F' => 40,  // Alamat
            'G' => 20,  // Kota/Kab
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // 3. Aktifkan WRAP TEXT agar tulisan tidak tembus keluar
        $sheet->getStyle('A:G')->getAlignment()->setWrapText(true);
        // Set alignment vertikal ke atas biar rapi
        $sheet->getStyle('A:G')->getAlignment()->setVertical('top');
        $headerRow = 3; // Default (Judul -> Spasi -> Header)

        if (!empty($this->infoPetugas)) {
            $headerRow = 4; // Kalau ada info: (Judul -> Info -> Spasi -> Header)
        }

        // 3. TERAPKAN STYLE KE BARIS HEADER YANG SUDAH DIHITUNG
        // Warna Abu-abu hanya di baris header
        $sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCCCCC');

        // 4. ATUR FONT
        return [
            // Baris 1 (Judul): Bold, Besar, Tengah
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center']
            ],

            // Baris 2 (Info Petugas): Italic, Tengah
            2 => [
                'font' => ['italic' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center']
            ],

            // Baris HEADER (Dinamis): Bold, Tengah, Border
            $headerRow => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }
}
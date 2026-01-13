<?php

namespace App\Services;

use App\Models\BeritaAcara;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BeritaAcaraService
{
    /**
     * Ambil data BAP dengan filter tahun dan hak akses user
     */
    public function getBapData($tahun, $user)
    {
        $query = BeritaAcara::query();

        // Filter Tahun
        if ($tahun) {
            $query->whereYear('tanggal_pemeriksaan', $tahun);
        }

        // Filter Hak Akses (Jika bukan admin, hanya lihat yang ditugaskan)
        if (!$user->isAdmin()) {
            $query->whereHas('petugas', fn ($q) => $q->where('users.nip', $user->nip));
        }

        return $query->latest()->get();
    }

    /**
     * Simpan data BAP baru beserta relasi petugas
     */
    public function storeBap(array $data, array $petugasData)
    {
        // 1. Create Data Utama
        $ba = BeritaAcara::create($data);

        // 2. Sync Data Petugas (Pivot Table)
        $syncData = [];
        foreach ($petugasData['nip'] as $i => $nip) {
            if ($nip) {
                $dataPivot = [
                    'pangkat' => $petugasData['pangkat'][$i] ?? '-',
                    'jabatan' => $petugasData['jabatan'][$i] ?? '-',
                ];

                // Cek apakah ada input TTD baru dari form?
                if (isset($petugasData['ttd'][$i]) && !empty($petugasData['ttd'][$i])) {
                    $dataPivot['ttd'] = $petugasData['ttd'][$i];
                } 
                $syncData[$nip] = $dataPivot;
            }
        }
        
        $ba->petugas()->sync($syncData);

        return $ba;
    }
    
    public function updateBap($id, array $data, array $petugasData)
    {
        $ba = BeritaAcara::findOrFail($id);
        
        // 1. Update Data Utama
        $ba->update($data);

        // 2. Siapkan data pivot petugas
        $syncData = [];
        foreach ($petugasData['nip'] as $i => $nip) {
            if ($nip) {
                $dataPivot = [
                    'pangkat' => $petugasData['pangkat'][$i] ?? '-',
                    'jabatan' => $petugasData['jabatan'][$i] ?? '-',
                ];

                // Cek apakah ada input TTD baru dari form?
                if (isset($petugasData['ttd'][$i]) && !empty($petugasData['ttd'][$i])) {
                    $dataPivot['ttd'] = $petugasData['ttd'][$i];
                } 
                $syncData[$nip] = $dataPivot;
            }
        }
        
        // 3. Sync (Otomatis hapus yang lama, masukkan yang baru)
        $ba->petugas()->sync($syncData);

        return $ba;
    }

    public function getBapById($id)
    {
        return BeritaAcara::with('petugas')->findOrFail($id);
    }

    /**
     * Generate PDF Stream
     * Digunakan oleh Preview (Cetak) maupun Final PDF
     */
    public function generatePdf($data, $listPetugas)
    {
        // Logic konversi gambar ke base64 dipindah ke sini atau Helper, 
        // tapi untuk sekarang kita simpan logic view data-nya
        
        $pdfData = [
            'data'          => $data,
            'list_petugas'  => $listPetugas,
            'teks_tgl'      => DateHelper::teksTanggal($data['tanggal']), // Pastikan key array konsisten
            'tgl_st'        => DateHelper::indoLengkap($data['tgl_surat_tugas']),
            'logo'          => $this->imgBase64('logo_bpom.png'),
            'footer'        => $this->imgBase64('border.png')
        ];

        $pdf = Pdf::loadView('bap.pdf', $pdfData);
        return $pdf->setPaper('A4', 'portrait')->stream('BAP_Digital.pdf');
    }

    // Helper private untuk gambar (diambil dari controller lama)
    private function imgBase64($file)
    {
        $path = public_path('assets/img/' . $file);
        if (!file_exists($path)) return '';
        
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
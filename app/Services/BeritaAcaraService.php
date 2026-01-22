<?php

namespace App\Services;

use App\Models\BeritaAcara;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BeritaAcaraService
{
    /**
     * Query Builder (Untuk DataTables Server-Side)
     */
    public function getBapQuery($tahun, $user, $filterNip = null)
    {
        $query = BeritaAcara::with('petugas', 'pembuat')
            -> select('berita_acara.*');

        // Filter Tahun
        if ($tahun && $tahun !== 'semua') {
            $query->whereYear('tanggal_pemeriksaan', $tahun);
        }

        // Filter Hak Akses (Jika bukan admin, hanya lihat yang ditugaskan)
        if (!$user->isAdmin()) {
            $query->whereHas('petugas', fn($q) => 
            $q->where('users.nip', $user->nip));
        }

        elseif ($filterNip && $filterNip !== 'semua') {
            // Jika Admin DAN dia memilih filter NIP tertentu
            $query->whereHas('petugas', fn ($q) => 
                $q->where('users.nip', $filterNip));
        }

        return $query;
    }

    /**
     * Ambil data BAP dengan filter tahun dan hak akses user
     */
    public function getBapData($tahun, $user, $filterNip = null)
    {
        return $this->getBapQuery($tahun, $user, $filterNip)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Simpan data BAP baru beserta relasi petugas
     */
    public function storeBap(array $data, array $petugasData)
    {
        $ba = activity()->withoutLogs(function () use ($data, $petugasData) {
            
            // A. Buat Data Utama
            $newInstance = BeritaAcara::create($data);
            
            // B. Sync Petugas
            $this->syncPetugas($newInstance, $petugasData);
            
            return $newInstance;
        });

        // 2. Siapkan Data untuk Log Lengkap
        // Refresh agar relasi petugas termuat dengan benar
        $ba->refresh(); 
        
        // Ambil semua atribut data utama yang baru dibuat
        $logAttributes = $ba->only(array_keys($data));
        
        // Ambil nama-nama petugas
        $petugasNames = $ba->petugas->pluck('name')->toArray();
        $logAttributes['petugas'] = implode(', ', $petugasNames); // Gabung jadi string

        // 3. Catat Log "Created" dengan detail lengkap
        activity()
            ->performedOn($ba)
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => $logAttributes]) // Masukkan semua data ke sini
            ->event('created')
            ->log('Membuat Berita Acara Baru');

        return $ba;
    }

    public function updateBap($id, array $data, array $petugasData)
    {
        $ba = BeritaAcara::findOrFail($id);

        // 1. Ambil data LAMA sebelum diapa-apain (untuk perbandingan)
        $oldData = $ba->only(array_keys($data));
        $oldPetugas = $ba->petugas->pluck('name')->toArray();

        // 2. Lakukan Update TANPA Log Otomatis (Kita catat manual nanti biar gabung)
        activity()->withoutLogs(function () use ($ba, $data, $petugasData) {
            // Update Data Utama
            $ba->update($data);
            $this->syncPetugas($ba, $petugasData);
        });

        // 3. Ambil Data BARU setelah update
        $ba->refresh(); // Refresh agar relasi petugas terupdate
        $newData = $ba->only(array_keys($data));
        $newPetugas = $ba->petugas->pluck('name')->toArray();

        // 4. Bandingkan Manual & Buat SATU Log Gabungan
        $changes = [];
        
        // Cek perubahan data utama
        foreach ($newData as $key => $value) {
            if ($oldData[$key] != $value) {
                $changes[$key] = "Dari '{$oldData[$key]}' menjadi '{$value}'";
            }
        }

        // Cek perubahan petugas
        if ($oldPetugas !== $newPetugas) {
            $changes['susunan_petugas'] = "Diubah dari [" . implode(', ', $oldPetugas) . "] menjadi [" . implode(', ', $newPetugas) . "]";
        }

        // 5. Catat Log HANYA JIKA ada perubahan
        if (!empty($changes)) {
            activity()
                ->performedOn($ba)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $changes]) // Simpan detail perubahan di sini
                ->event('updated')
                ->log('Melakukan perubahan data BAP');
        }

        return $ba;
    }

    /**
     * [HELPER] Sinkronisasi Petugas
     */
    private function syncPetugas($ba, $petugasData)
    {
        $syncData = [];
        foreach ($petugasData['nip'] as $i => $nip) {
            if ($nip) {
                $dataPivot = [
                    'pangkat' => $petugasData['pangkat'][$i] ?? '-',
                    'jabatan' => $petugasData['jabatan'][$i] ?? '-',
                ];
                if (isset($petugasData['ttd'][$i]) && !empty($petugasData['ttd'][$i])) {
                    $dataPivot['ttd'] = $petugasData['ttd'][$i];
                }
                $syncData[$nip] = $dataPivot;
            }
        }
        $ba->petugas()->sync($syncData);
    }

    /**
     * Ambil data BAP beserta relasi petugas berdasarkan ID
     */
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
            'data' => $data,
            'list_petugas' => $listPetugas,
            'teks_tgl' => DateHelper::teksTanggal($data['tanggal']), // Pastikan key array konsisten
            'tgl_st' => DateHelper::indoLengkap($data['tgl_surat_tugas']),
            'logo' => $this->imgBase64('logo_bpom.png'),
            'footer' => $this->imgBase64('border.png')
        ];

        $pdf = Pdf::loadView('bap.pdf', $pdfData);
        return $pdf->setPaper('A4', 'portrait')->stream('BAP_Digital.pdf');
    }

    // Helper private untuk gambar (diambil dari controller lama)
    private function imgBase64($file)
    {
        $path = public_path('assets/img/' . $file);
        if (!file_exists($path))
            return '';

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    /**
     * Hapus Data dengan Log Detail (Snapshot sebelum hapus)
     */
    public function deleteBap($id)
    {
        // 1. Ambil data lengkap beserta relasinya
        $ba = BeritaAcara::with('petugas')->findOrFail($id);

        // 2. Siapkan "Snapshot" data yang ingin dicatat di log
        $logAttributes = [
            'no_surat_tugas' => $ba->no_surat_tugas,
            'tanggal_pemeriksaan' => $ba->tanggal_pemeriksaan,
            'objek_nama' => $ba->objek_nama,
            // Gabungkan nama petugas jadi satu string
            'petugas' => $ba->petugas->pluck('name')->implode(', '), 
        ];

        // 3. Hapus data (Matikan log otomatis bawaan model agar tidak double/kosong)
        activity()->withoutLogs(function () use ($ba) {
            $ba->petugas()->detach(); // Hapus relasi pivot
            $ba->delete();            // Hapus data utama
        });

        // 4. Catat Log Manual dengan detail yang sudah kita snapshot tadi
        activity()
            ->performedOn($ba)
            ->causedBy(auth()->user()) // Pelaku hapus
            ->withProperties(['attributes' => $logAttributes]) // Masukkan detail di sini
            ->event('deleted') // Set event sebagai 'deleted' (biar badge warna merah)
            ->log('Menghapus Berita Acara');

        return true;
    }
}
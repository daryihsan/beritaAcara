<?php

namespace App\Services;

use App\Models\BeritaAcara;
use App\Exceptions\BapException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\ImageService; 
use App\Services\PdfService;

class BeritaAcaraService
{
    protected $imageService;
    protected $pdfService;

    public function __construct(ImageService $imageService, PdfService $pdfService)
    {
        $this->imageService = $imageService;
        $this->pdfService = $pdfService;
    }

    /**
     * Query Builder (Untuk DataTables Server-Side)
     */
    public function getBapQuery($tahun, $user, $filterNip = null)
    {
        $query = BeritaAcara::with([
            'petugas' => function ($q) {
                $q->select('users.nip', 'users.name'); 
            },
            'pembuat' => function ($q) {
                $q->select('id', 'name', 'nip');
            }
        ])
        ->select('berita_acara.*');

        // Filter Tahun
        if ($tahun && $tahun !== 'semua') {
            $query->whereYear('tanggal_pemeriksaan', $tahun);
        }

        // Filter Hak Akses (Jika bukan admin, hanya lihat yang ditugaskan)
        if (!$user->isAdmin()) {
            $query->whereHas('petugas', fn($q) =>
                $q->where('users.nip', $user->nip));
        } elseif ($filterNip && $filterNip !== 'semua') {
            // Jika Admin DAN memilih filter NIP tertentu
            $query->whereHas('petugas', fn($q) =>
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
     * Ambil data BAP beserta relasi petugas berdasarkan ID
     */
    public function getBapById($id)
    {
        return BeritaAcara::with('petugas', 'pembuat')->findOrFail($id);
    }

    /**
     * Simpan data BAP baru beserta relasi petugas dengan log baru
     */
    public function storeBap(array $data, array $petugasData)
    {
        return DB::transaction(function () use ($data, $petugasData) {
            try{
                $ba = activity()->withoutLogs(function () use ($data, $petugasData) {
                    // Buat Data Utama
                    $newInstance = BeritaAcara::create($data);

                    // Sync Petugas
                    $this->syncPetugas($newInstance, $petugasData);
                    return $newInstance;
                });
                $this->logCreation($ba, $data);
                return $ba;
            } catch (\Throwable $e) {
                Log::error("CRITICAL ERROR Store BAP: " . $e->getMessage() . " | Line: " . $e->getLine());
                throw new BapException("Gagal menyimpan Berita Acara : " . $e->getMessage() . "Silakan coba lagi.");
            }                
        });
    }

    /**
     * Perbarui data BAP beserta relasi petugas dengan log detail perubahan
     */
    public function updateBap($id, array $data, array $petugasData)
    {
        $ba = BeritaAcara::findOrFail($id);
        // data lama 
        $oldData = $ba->only(array_keys($data));
        $oldPetugas = $ba->petugas->pluck('name')->toArray();

        return DB::transaction(function () use ($id, $ba, $data, $petugasData, $oldData, $oldPetugas) {
            try{
                // Lakukan Update tanpa Log Otomatis
                activity()->withoutLogs(function () use ($ba, $data, $petugasData) {
                    // Update Data Utama
                    $ba->update($data);
                    $this->syncPetugas($ba, $petugasData);
                });
                $this->logUpdate($ba, $data, $oldData, $oldPetugas);
                return $ba;
            } catch (\Throwable $e) {
                Log::error("CRITICAL ERROR Update BAP ID $id: " . $e->getMessage() . " | Line: " . $e->getLine());
                throw new BapException("Gagal memperbarui Berita Acara: " . $e->getMessage() . " Silakan coba lagi.");
            }
        } );
    }

    /**
     * Hapus Data dengan Log Detail (Snapshot sebelum hapus)
     */
    public function deleteBap($id)
    {
        return DB::transaction(function() use ($id) {
            try{
                // data lengkap beserta relasi
                $ba = BeritaAcara::with('petugas')->findOrFail($id);

                $this->logDeteletion($ba);

                // Hapus data 
                activity()->withoutLogs(function () use ($ba) {
                    $ba->petugas()->detach(); // Hapus relasi pivot
                    $ba->delete();            // Hapus data utama
                });
                return true;
            } catch (\Throwable $e) {
                Log::error("CRITICAL ERROR Delete BAP ID $id: " . $e->getMessage() . " | Line: " . $e->getLine());
                throw new BapException("Gagal menghapus Berita Acara: " . $e->getMessage() . " Silakan coba lagi.");
            }
        });
    }

    /**
     * Generate PDF Stream
     * Digunakan oleh Preview (Cetak) maupun Final PDF
     */
    public function generatePdf($data, $listPetugas)
    {
        try{
            return $this->pdfService->generateBapPdf($data, $listPetugas);
        } catch (\Throwable $e) {
            Log::error("CRITICAL ERROR PDF Generation: " . $e->getMessage() . " | Line: " . $e->getLine());
            throw new BapException(message: 'Gagal membuat PDF Berita Acara. Pastikan memori server cukup atau hubungi admin.'); 
        }
    }

    /**
     * Sinkronisasi Petugas
     */
    private function syncPetugas($ba, $petugasData)
    {
        if (!isset($petugasData['nip']) || !is_array($petugasData['nip'])) {
            // Jika kosong/null, jangan lakukan apapun
            return;
        }
        $syncData = [];
        foreach ($petugasData['nip'] as $i => $nip) {
            if ($nip) {
                $dataPivot = [
                    'pangkat' => $petugasData['pangkat'][$i] ?? '-',
                    'jabatan' => $petugasData['jabatan'][$i] ?? '-',
                ];
                $ttdInput = $petugasData['ttd'][$i] ?? null;

                if (!empty($ttdInput) && is_string($ttdInput) && Str::contains($ttdInput, 'data:image')) {
                    try {
                        $filename = $this->imageService->saveSignature($ttdInput, $nip);
                        $dataPivot['ttd'] = $filename;
                    } catch (\Throwable $e) {
                        Log::error("CRITICAL ERROR PDF Generation: " . $e->getMessage() . " | Line: " . $e->getLine());
                        // tanpa TTD      
                        $dataPivot['ttd'] = null;   
                        throw new BapException(message: 'Gagal menyimpan Tanda Tangan digital. Silakan coba lagi.');
                    }
                } else{
                    if ($ttdInput && preg_match('/signatures\/(\d+)_/', $ttdInput, $matches)) {
                        $nipDiFile = $matches[1];
                        // Jika NIP di nama file BEDA dengan NIP petugas baris ini
                        if ($nipDiFile !== $nip) {
                            Log::warning("Mencegah duplikat TTD! NIP Petugas ($nip) beda dengan NIP File ($nipDiFile)");
                            // Kosongkan TTD (Lebih aman daripada salah orang)
                            $dataPivot['ttd'] = null; 
                        } else {
                            $dataPivot['ttd'] = $ttdInput;
                        }
                    } else {
                        // Jika format file tidak mengandung NIP (file legacy/lama), biarkan saja
                        $dataPivot['ttd'] = $ttdInput;
                    }
                }
                $syncData[$nip] = $dataPivot;
            }
        }
        $ba->petugas()->sync($syncData);
    }

    /**
     * log Creation Helper
     */
    private function logCreation($ba, $data)
    {
        // Data untuk Log Lengkap
        // Refresh agar relasi petugas termuat dengan benar
        $ba->refresh();
        // Ambil semua atribut data utama yang baru dibuat
        $logAttributes = $ba->only(array_keys($data));
        // Ambil nama-nama petugas
        $petugasNames = $ba->petugas->pluck('name')->toArray();
        $logAttributes['petugas'] = implode(', ', $petugasNames); 
        // Catat Log "Created" dengan detail lengkap
        activity()
            ->performedOn($ba)
            ->causedBy(auth()->user())
            ->withProperties(['attributes' => $logAttributes]) 
            ->event('created')
            ->log('Membuat Berita Acara Baru');
    }

    /**
     * log Update Helper
     */
    private function logUpdate($ba, $data, $oldData, $oldPetugas)
    {
        // Ambil Data setelah update
        $ba->refresh(); // Refresh agar relasi petugas terupdate
        $newData = $ba->only(array_keys($data));
        $newPetugas = $ba->petugas->pluck('name')->toArray();
        // Bandingkan Manual & Buat satu log gabungan
        $changes = [];
        // Cek perubahan data utama
        foreach ($newData as $key => $value) {
            if (($oldData[$key] ?? '') != $value) {
                $changes[$key] = "Dari '" . ($oldData[$key] ?? '-') . "' menjadi '" . $value . "'";
            }
        }
        // Cek perubahan petugas
        if ($oldPetugas !== $newPetugas) {
            $changes['susunan_petugas'] = "Diubah dari [" . implode(', ', $oldPetugas) . "] menjadi [" . implode(', ', $newPetugas) . "]";
        }
        // Catat Log hanya saat ada perubahan
        if (!empty($changes)) {
            activity()
                ->performedOn($ba)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $changes])
                ->event('updated')
                ->log('Melakukan perubahan data BAP');
        }
    }

    /**
     * log Deletion Helper
     */
    private function logDeteletion($ba)
    {
        // "Snapshot" data yang ingin dicatat di log
        $logAttributes = [
            'no_surat_tugas' => $ba->no_surat_tugas,
            'tanggal_pemeriksaan' => $ba->tanggal_pemeriksaan,
            'objek_nama' => $ba->objek_nama,
            'petugas' => $ba->petugas->pluck('name')->implode(', '),
        ];
        // Catat Log Manual
        activity()
            ->performedOn($ba)
            ->causedBy(auth()->user()) // Pelaku hapus
            ->withProperties(['attributes' => $logAttributes])
            ->event('deleted') // Set event sebagai 'deleted' 
            ->log('Menghapus Berita Acara');
    }
}
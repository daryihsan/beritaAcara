<?php

namespace App\Services;

use App\Models\BeritaAcara;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\BapException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaAcaraService
{
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
     * Simpan data BAP baru beserta relasi petugas
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
                return $ba;
            } catch (\Throwable $e) {
                Log::error("CRITICAL ERROR Store BAP: " . $e->getMessage() . " | Line: " . $e->getLine());
                throw new BapException("Gagal menyimpan Berita Acara : " . $e->getMessage() . "Silakan coba lagi.");
            }                
        });
    }

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
                return $ba;
            } catch (\Throwable $e) {
                Log::error("CRITICAL ERROR Update BAP ID $id: " . $e->getMessage() . " | Line: " . $e->getLine());
                throw new BapException("Gagal memperbarui Berita Acara: " . $e->getMessage() . " Silakan coba lagi.");
            }
        } );
    }

    /**
     * Sinkronisasi Petugas
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

                $ttdInput = $petugasData['ttd'][$i] ?? null;

                if (!empty($ttdInput) && is_string($ttdInput) && Str::contains($ttdInput, 'data:image')) {
                    try {
                        // Generate Nama File Unik
                        $nipPetugas = $nip;
                        // Format: signatures/NIP_TIMESTAMP_RANDOM.png
                        $filename = 'signatures/' . $nipPetugas . '_' . time() . '_' . Str::random(5) . '.png';
                        $image = Image::make($ttdInput)
                            ->resize(300, null, function ($c) {
                                $c->aspectRatio();
                                $c->upsize();
                            })
                            ->encode('png', 85); 
                        // Simpan ke Storage (public disk)
                        Storage::disk('public')->put($filename, $image);
                        $dataPivot['ttd'] = $filename;
                    } catch (\Throwable $e) {
                        Log::error("CRITICAL ERROR PDF Generation: " . $e->getMessage() . " | Line: " . $e->getLine());
                        // tanpa TTD      
                        $dataPivot['ttd'] = null;   
                        throw new BapException(message: 'Gagal menyimpan Tanda Tangan digital. Silakan coba lagi.');
                    }
                } else{
                    if (preg_match('/signatures\/(\d+)_/', $ttdInput, $matches)) {
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
     * Ambil data BAP beserta relasi petugas berdasarkan ID
     */
    public function getBapById($id)
    {
        return BeritaAcara::with('petugas', 'pembuat')->findOrFail($id);
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

                // "Snapshot" data yang ingin dicatat di log
                $logAttributes = [
                    'no_surat_tugas' => $ba->no_surat_tugas,
                    'tanggal_pemeriksaan' => $ba->tanggal_pemeriksaan,
                    'objek_nama' => $ba->objek_nama,
                    'petugas' => $ba->petugas->pluck('name')->implode(', '),
                ];

                // Hapus data 
                activity()->withoutLogs(function () use ($ba) {
                    $ba->petugas()->detach(); // Hapus relasi pivot
                    $ba->delete();            // Hapus data utama
                });

                // Catat Log Manual
                activity()
                    ->performedOn($ba)
                    ->causedBy(auth()->user()) // Pelaku hapus
                    ->withProperties(['attributes' => $logAttributes])
                    ->event('deleted') // Set event sebagai 'deleted' 
                    ->log('Menghapus Berita Acara');

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
            ini_set('memory_limit', '256M');

            $fieldsToSanitize = [
                'no_surat_tugas',
                'objek_nama',
                'objek_alamat',
                'hasil_pemeriksaan',
                'objek_kota',
                'dalam_rangka',
                'yang_diperiksa'
            ];

            foreach ($fieldsToSanitize as $field) {
                if (isset($data[$field])) {
                    // strip_tags: Hapus semua tag HTML (<script>, <b>, dll)
                    // htmlspecialchars: Ubah karakter spesial jadi aman
                    $data[$field] = htmlspecialchars(strip_tags($data[$field]), ENT_QUOTES, 'UTF-8');
                }
            }

            if (is_object($listPetugas) && method_exists($listPetugas, 'toArray')) {
                $listPetugas = $listPetugas->toArray();
            }

            foreach ($listPetugas as $key => $petugas) {
                // Ambil data TTD dari array atau objek
                $ttdPath = $petugas['ttd'] ?? null;

                if ($ttdPath && !Str::contains($ttdPath, 'data:image')) {
                    // Jika ini path storage (signatures/xxx.png), ubah ke Absolute Path server
                    $fullPath = storage_path('app/public/' . $ttdPath);
                    if (file_exists($fullPath)) {
                        // Ubah ke Base64 agar DomPDF lebih stabil merendernya
                        $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                        $imgData = file_get_contents($fullPath);
                        $listPetugas[$key]['ttd'] = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
                    } else {
                         $listPetugas[$key]['ttd'] = null;
                    }
                }
            }

            $pdfData = [
                'data' => $data,
                'list_petugas' => $listPetugas,
                'teks_tgl' => DateHelper::teksTanggal($data['tanggal']),
                'tgl_st' => DateHelper::indoLengkap($data['tgl_surat_tugas']),
                'logo' => $this->imgBase64('headerpdf.png'),
                'footer' => $this->imgBase64('footerpdf.png')
            ];
            $pdf = Pdf::loadView('bap.pdf', $pdfData);

            $safeSurat = str_replace(['/', '\\'], '-', $data['no_surat_tugas']); 
            $fileName = 'BAP-' . $safeSurat . '.pdf';
            return $pdf->setPaper('A4', 'portrait')->stream($fileName);
        } catch (\Throwable $e) {
            Log::error("CRITICAL ERROR PDF Generation: " . $e->getMessage() . " | Line: " . $e->getLine());
            throw new BapException(message: 'Gagal membuat PDF Berita Acara. Pastikan memori server cukup atau hubungi admin.'); 
        }
    }

    // Helper private untuk gambar
    private function imgBase64($file)
    {
        $path = public_path('assets/img/' . $file);
        if (!file_exists($path))
            return '';

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
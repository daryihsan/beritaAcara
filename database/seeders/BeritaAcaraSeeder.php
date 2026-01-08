<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BeritaAcara;
use App\Models\User;

class BeritaAcaraSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            ['id' => 1, 'no_surat_tugas' => 'ST-001/2025', 'tgl_surat_tugas' => '2026-01-07', 'tanggal_pemeriksaan' => '2026-01-07', 'hari' => 'Senin', 'objek_nama' => 'Toko Contoh', 'objek_alamat' => 'Alamat Contoh', 'hasil_pemeriksaan' => 'Baik', 'created_by' => 1],
            ['id' => 2, 'no_surat_tugas' => 'saint', 'tgl_surat_tugas' => '2025-12-10', 'tanggal_pemeriksaan' => '2025-12-24', 'hari' => 'Jumat', 'objek_nama' => 'Gatau', 'objek_alamat' => 'Rumah', 'hasil_pemeriksaan' => 'acwdc', 'created_by' => 2],
            ['id' => 3, 'no_surat_tugas' => 'Kecewa', 'tgl_surat_tugas' => '2025-12-10', 'tanggal_pemeriksaan' => '2025-12-24', 'hari' => 'Senin', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Dimana ya', 'hasil_pemeriksaan' => 'Iya', 'created_by' => 1],
            ['id' => 4, 'no_surat_tugas' => 'KP.07.01.1A.05.10.506', 'tgl_surat_tugas' => '2025-05-14', 'tanggal_pemeriksaan' => '2025-06-05', 'hari' => 'Senin', 'objek_nama' => 'Sekolah', 'objek_alamat' => 'Rumah', 'hasil_pemeriksaan' => 'Ya', 'created_by' => 8],
            ['id' => 5, 'no_surat_tugas' => 'TP.12.04.3B.05.25.123', 'tgl_surat_tugas' => '2024-11-14', 'tanggal_pemeriksaan' => '2024-11-21', 'hari' => 'Selasa', 'objek_nama' => 'Prasarana', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Iya', 'created_by' => 8],
            ['id' => 7, 'no_surat_tugas' => 'KP.07.11.6B.05.10.126', 'tgl_surat_tugas' => '2025-10-15', 'tanggal_pemeriksaan' => '2025-10-17', 'hari' => 'Kamis', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Boleh', 'created_by' => 8],
            ['id' => 8, 'no_surat_tugas' => 'AD.02.01.2A.05.22.811', 'tgl_surat_tugas' => '2025-04-09', 'tanggal_pemeriksaan' => '2025-05-01', 'hari' => 'Selasa', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Iya', 'created_by' => 8],
            ['id' => 9, 'no_surat_tugas' => 'AU.09.01.7C.11.05.116', 'tgl_surat_tugas' => '2024-12-13', 'tanggal_pemeriksaan' => '2025-01-06', 'hari' => 'Rabu', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Gk', 'created_by' => 8],
            ['id' => 10, 'no_surat_tugas' => 'AU.09.01.3B.01.15.119', 'tgl_surat_tugas' => '2025-02-06', 'tanggal_pemeriksaan' => '2025-03-05', 'hari' => 'Selasa', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Gk', 'created_by' => 8],
            ['id' => 11, 'no_surat_tugas' => 'FA.11.05.9B.01.15.119', 'tgl_surat_tugas' => '2025-05-01', 'tanggal_pemeriksaan' => '2025-06-25', 'hari' => 'Kamis', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Gk', 'created_by' => 8],
        ];

        $opsiKepala = ['Balai Besar POM di Semarang', 'Plh. Kepala Balai', 'Plt. Kepala Balai'];
        $petugasMaster = User::where('role', 'petugas')->get();

        foreach ($records as $record) {
            // Isi kolom tambahan yang belum ada di SQL mentah agar PDF tidak error
            $record['kepala_balai_text'] = $opsiKepala[array_rand($opsiKepala)];
            $record['objek_kota'] = 'Semarang';
            $record['dalam_rangka'] = 'Pemeriksaan Setempat';
            $record['yang_diperiksa'] = 'Pimpinan Fasilitas';

            // Simpan Berita Acara
            $ba = BeritaAcara::create($record);

            // Pasangkan 1-3 petugas secara acak
            if ($petugasMaster->count() > 0) {
                $randomPetugas = $petugasMaster->random(rand(1, min(3, $petugasMaster->count())));
                
                foreach ($randomPetugas as $p) {
                    // Masukkan NIP ke pivot beserta Pangkat & Jabatan aslinya
                    $ba->petugas()->attach($p->nip, [
                        'pangkat' => $p->pangkat,
                        'jabatan' => $p->jabatan,
                    ]);
                }
            }
        }
    }
}
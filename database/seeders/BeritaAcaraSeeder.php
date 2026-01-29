<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BeritaAcara;
use App\Models\User;

class BeritaAcaraSeeder extends Seeder
{
    public function run(): void
    {
        // 1. AMBIL USER PEMBUAT (ADMIN) YANG SUDAH ADA (DARI USER SEEDER)
        // Kita butuh ID aslinya (ULID)
        $admin = User::where('role', 'admin')->first();

        // Jaga-jaga kalau user admin belum di-seed, kita buatkan on-the-fly
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Fallback',
                'nip' => '999999',
                'role' => 'admin',
                'password' => bcrypt('password')
            ]);
        }

        // 2. DATA DUMMY (Tanpa 'id' dan 'created_by')
        // ID akan digenerate otomatis jadi ULID.
        // Created_by akan kita isi pakai $admin->id
        $records = [
            ['no_surat_tugas' => 'ST-001/2025', 'tgl_surat_tugas' => '2026-01-07', 'tanggal_pemeriksaan' => '2026-01-07', 'hari' => 'Senin', 'objek_nama' => 'Toko Contoh', 'objek_alamat' => 'Alamat Contoh', 'hasil_pemeriksaan' => 'Baik'],
            ['no_surat_tugas' => 'saint', 'tgl_surat_tugas' => '2025-12-10', 'tanggal_pemeriksaan' => '2025-12-24', 'hari' => 'Jumat', 'objek_nama' => 'Gatau', 'objek_alamat' => 'Rumah', 'hasil_pemeriksaan' => 'acwdc'],
            ['no_surat_tugas' => 'Kecewa', 'tgl_surat_tugas' => '2025-12-10', 'tanggal_pemeriksaan' => '2025-12-24', 'hari' => 'Senin', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Dimana ya', 'hasil_pemeriksaan' => 'Iya'],
            ['no_surat_tugas' => 'KP.07.01.1A.05.10.506', 'tgl_surat_tugas' => '2025-05-14', 'tanggal_pemeriksaan' => '2025-06-05', 'hari' => 'Senin', 'objek_nama' => 'Sekolah', 'objek_alamat' => 'Rumah', 'hasil_pemeriksaan' => 'Ya'],
            ['no_surat_tugas' => 'TP.12.04.3B.05.25.123', 'tgl_surat_tugas' => '2024-11-14', 'tanggal_pemeriksaan' => '2024-11-21', 'hari' => 'Selasa', 'objek_nama' => 'Prasarana', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Iya'],
            ['no_surat_tugas' => 'KP.07.11.6B.05.10.126', 'tgl_surat_tugas' => '2025-10-15', 'tanggal_pemeriksaan' => '2025-10-17', 'hari' => 'Kamis', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Boleh'],
            ['no_surat_tugas' => 'AD.02.01.2A.05.22.811', 'tgl_surat_tugas' => '2025-04-09', 'tanggal_pemeriksaan' => '2025-05-01', 'hari' => 'Selasa', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Iya'],
            ['no_surat_tugas' => 'AU.09.01.7C.11.05.116', 'tgl_surat_tugas' => '2024-12-13', 'tanggal_pemeriksaan' => '2025-01-06', 'hari' => 'Rabu', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Gk'],
            ['no_surat_tugas' => 'AU.09.01.3B.01.15.119', 'tgl_surat_tugas' => '2025-02-06', 'tanggal_pemeriksaan' => '2025-03-05', 'hari' => 'Selasa', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Gk'],
            ['no_surat_tugas' => 'FA.11.05.9B.01.15.119', 'tgl_surat_tugas' => '2025-05-01', 'tanggal_pemeriksaan' => '2025-06-25', 'hari' => 'Kamis', 'objek_nama' => 'Jembatan', 'objek_alamat' => 'Jalan Kota', 'hasil_pemeriksaan' => 'Gk'],
        ];

        $opsiKepala = ['Balai Besar POM di Semarang', 'Plh. Kepala Balai', 'Plt. Kepala Balai'];
        
        // Ambil SEMUA petugas yang ada di DB untuk dirandom nanti
        $petugasMaster = User::where('role', 'petugas')->get();

        foreach ($records as $record) {
            // Lengkapi data default
            $record['kepala_balai_text'] = $opsiKepala[array_rand($opsiKepala)];
            $record['objek_kota'] = 'Semarang';
            $record['dalam_rangka'] = 'Pemeriksaan Setempat';
            $record['yang_diperiksa'] = 'Pimpinan Fasilitas';
            
            // PENTING: Assign ID Admin yang valid (ULID)
            $record['created_by'] = $admin->id; 

            // Simpan Berita Acara
            // Karena ULID, $ba->id nanti otomatis terisi string panjang
            $ba = BeritaAcara::create($record);

            // Pasangkan 1-3 petugas secara acak
            if ($petugasMaster->count() > 0) {
                // Ambil 1 s/d 3 petugas acak
                $jumlahPetugas = rand(1, min(3, $petugasMaster->count()));
                $randomPetugas = $petugasMaster->random($jumlahPetugas);
                
                foreach ($randomPetugas as $p) {
                    // Attach ke Pivot
                    // Relasi 'petugas' menggunakan NIP (String) sesuai migrasi pivot kamu
                    $ba->petugas()->attach($p->nip, [
                        'pangkat' => $p->pangkat,
                        'jabatan' => $p->jabatan,
                        // 'ttd' => null (biarkan kosong dulu)
                    ]);
                }
            }
        }
    }
}
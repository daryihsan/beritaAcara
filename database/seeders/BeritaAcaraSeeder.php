<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BeritaAcara;
use App\Models\User;

class BeritaAcaraSeeder extends Seeder
{
public function run(): void
{
    // 1. Buat Berita Acara-nya dulu
    $ba = BeritaAcara::create([
        'no_surat_tugas' => 'ST-001/2025',
        'tgl_surat_tugas' => now(),
        'tanggal_pemeriksaan' => now(),
        'hari' => 'Senin',
        'objek_nama' => 'Toko Contoh',
        'objek_alamat' => 'Alamat Contoh',
        'hasil_pemeriksaan' => 'Baik',
        'created_by' => 1, // Ini merujuk ke ID user pembuat
    ]);

    // 2. Ambil user yang sudah ada dari UserSeeder (misal ambil user pertama)
    $user = User::first(); 

    // 3. Hubungkan ke pivot menggunakan NIP
    // Laravel akan otomatis mengambil $user->nip karena kita sudah set relasinya di model
    $ba->petugas()->attach($user->nip); 
}

}
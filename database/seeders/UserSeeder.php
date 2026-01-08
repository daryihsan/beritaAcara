<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin Utama
        User::create([
            'nip' => 'admin',
            'name' => 'Administrator Utama',
            'password' => bcrypt('password'),
            'pangkat' => 'Pembina / IVa',
            'jabatan' => 'Kepala Balai Besar POM',
            'role' => 'admin'
        ]);

        // Daftar Jabatan & Pangkat Variasi untuk Petugas
        $jabatanList = [
            ['pangkat' => 'Penata / IIIc', 'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Muda'],
            ['pangkat' => 'Penata Muda Tk. I / IIIb', 'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama'],
            ['pangkat' => 'Penata Muda / IIIa', 'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama'],
            ['pangkat' => 'Penata / IIIc', 'jabatan' => 'Analisis Kebijakan Ahli Muda'],
            ['pangkat' => 'Pengatur / IIc', 'jabatan' => 'Pelaksana'],
        ];

        // Buat Petugas A - I dengan variasi jabatan
        $petugasNames = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        foreach ($petugasNames as $index => $char) {
            $val = $jabatanList[$index % count($jabatanList)];
            User::create([
                'nip' => 'petugas' . ($index + 1),
                'name' => 'Petugas ' . $char,
                'password' => bcrypt('password'),
                'pangkat' => $val['pangkat'],
                'jabatan' => $val['jabatan'],
                'role' => 'petugas'
            ]);
        }

        // Petugas J sebagai Admin (Sesuai kode awal kamu)
        User::create([
            'nip' => 'petugas10',
            'name' => 'Petugas J',
            'password' => bcrypt('password'),
            'pangkat' => 'Penata Muda / IIIa',
            'jabatan' => 'Pengawas Farmasi dan Makanan Ahli Pertama',
            'role' => 'admin'
        ]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nip' => 'admin',
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::create([
            'nip' => 'petugas1',
            'name' => 'Petugas A',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

    }
}

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

        User::create([
            'nip' => 'petugas2',
            'name' => 'Petugas B',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => 'petugas3',
            'name' => 'Petugas C',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => 'petugas4',
            'name' => 'Petugas D',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => 'petugas5',
            'name' => 'Petugas E',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => 'petugas6',
            'name' => 'Petugas F',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => 'petugas7',
            'name' => 'Petugas G',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => 'petugas8',
            'name' => 'Petugas H',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => 'petugas9',
            'name' => 'Petugas I',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'nip' => 'petugas10',
            'name' => 'Petugas J',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
    }
}

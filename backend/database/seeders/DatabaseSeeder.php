<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin untuk Ardi
        User::create([
            'name' => 'Ardiansyah',
            'email' => 'admin@ardstudio.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun User Biasa Pertama (Pelanggan)
        User::create([
            'name' => 'Budiono Siregar',
            'email' => 'budiono@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 3. Buat Akun User Biasa Kedua (Pelanggan)
        User::create([
            'name' => 'Wilson',
            'email' => 'wilson@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 4. Jalankan seeder barang katalog yang tadi
        $this->call([
            GearSeeder::class,
        ]);
    }
}
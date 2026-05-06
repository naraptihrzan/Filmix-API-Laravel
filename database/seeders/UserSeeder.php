<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin otomatis
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@filmix.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // (Opsional) Membuat satu akun member untuk testing
        User::create([
            'name' => 'Budi Member',
            'email' => 'member@filmix.com',
            'password' => Hash::make('password123'),
            'role' => 'member',
        ]);
    }
}

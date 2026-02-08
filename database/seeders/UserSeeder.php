<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Pak Rusman',
            'email' => 'Rusman22@gmail.com',
            'password' => Hash::make('rusman22'), // passwordnya 'password'
            'role' => 'admin',
        ]);

        // 2. Akun Guru
        User::create([
            'name' => 'Pak Haidar',
            'email' => 'haidar24@gmail.com',
            'password' => Hash::make('haidar24'), // passwordnya 'password'
            'role' => 'guru',
        ]);
    }
}

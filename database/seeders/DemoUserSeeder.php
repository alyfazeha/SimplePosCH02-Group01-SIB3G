<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pos.test'],
            ['name' => 'Admin Kafe', 'role' => 'admin', 'password' => Hash::make('password')]
        );

        User::firstOrCreate(
            ['email' => 'kasir@pos.test'],
            ['name' => 'Kasir Kafe', 'role' => 'kasir', 'password' => Hash::make('password')]
        );

        // Tambahkan akun manager di sini
        User::firstOrCreate(
            ['email' => 'manager@pos.test'],
            ['name' => 'Manager Kafe', 'role' => 'manager', 'password' => Hash::make('password')]
        );
    }
}
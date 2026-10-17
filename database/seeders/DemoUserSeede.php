<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder untuk Admin
        User::firstOrCreate(
            ['email' => 'admin@pos.test'], // Kolom untuk pencarian (unik)
            [
                'name' => 'Admin Kafe',
                'role' => 'admin',
                'password' => Hash::make('password') // Disarankan di-hash
            ]
        );

        // Seeder untuk Kasir (dipisah menjadi statement sendiri)
        User::firstOrCreate(
            ['email' => 'kasir@pos.test'], // Kolom untuk pencarian (unik)
            [
                'name' => 'Kasir Kafe',
                'role' => 'kasir',
                'password' => Hash::make('password')
            ]
        );
    }
}

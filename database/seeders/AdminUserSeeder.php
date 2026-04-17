<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator RTLH',
            'email' => 'admin@perkimtan.palu',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pendata Contoh',
            'email' => 'pendata@perkimtan.palu',
            'password' => Hash::make('password123'),
            'role' => 'pendata',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}

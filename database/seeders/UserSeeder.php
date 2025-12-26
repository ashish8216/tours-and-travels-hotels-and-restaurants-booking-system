<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN (Filament)
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // AGENT (Hotel / Tenant Dashboard)
        User::create([
            'name' => 'Hotel Agent',
            'email' => 'agent@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        // USER (Customer)
        User::create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}

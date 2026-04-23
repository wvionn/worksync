<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun Admin
        User::factory()->create([
            'name' => 'Admin WorkSync',
            'email' => 'admin@worksync.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Membuat akun User biasa
        User::factory()->create([
            'name' => 'User WorkSync',
            'email' => 'user@worksync.com',
            'password' => bcrypt('user123'),
            'role' => 'member',
        ]);
    }
}

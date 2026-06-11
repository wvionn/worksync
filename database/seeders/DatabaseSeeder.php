<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@curator.pm'],
            [
                'name' => 'Ais',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]
        );

        // Member Users - You can add more members manually through the UI
        // Uncomment below if you want to seed sample members
        /*
        $member1 = User::firstOrCreate(
            ['email' => 'member1@curator.pm'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('123456'),
                'role' => 'member',
            ]
        );

        $member2 = User::firstOrCreate(
            ['email' => 'member2@curator.pm'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('123456'),
                'role' => 'member',
            ]
        );

        $member3 = User::firstOrCreate(
            ['email' => 'member3@curator.pm'],
            [
                'name' => 'Mike Johnson',
                'password' => Hash::make('123456'),
                'role' => 'member',
            ]
        );
        */

        // NOTE: Projects, Tasks, and Activities should be created manually through the admin panel
        // This ensures you have full control over your data
    }
}
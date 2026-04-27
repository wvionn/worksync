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
        // 1. Buat User Admin
        $admin = User::create([
            'name' => 'Ais',
            'email' => 'admin@curator.pm',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // 2. Buat Contoh Tasks
        $task1 = Task::create([
            'user_id' => $admin->id,
            'title' => 'Define API specs',
            'status' => 'todo',
            'priority' => 'high',
            'due_date' => now()->addDays(2),
        ]);

        $task2 = Task::create([
            'user_id' => $admin->id,
            'title' => 'Sprint Planning',
            'status' => 'doing',
            'priority' => 'medium',
        ]);

        $task3 = Task::create([
            'user_id' => $admin->id,
            'title' => 'QA Testing',
            'status' => 'done',
            'priority' => 'low',
        ]);

        // 3. Buat Contoh Activity
        Activity::create([
            'task_id' => $task3->id,
            'user_id' => $admin->id,
            'action' => 'Finished QA Testing phase',
        ]);
    }
}
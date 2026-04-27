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

        // Tasks
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

        // Activity
        Activity::create([
            'task_id' => $task3->id,
            'user_id' => $admin->id,
            'action' => 'Finished QA Testing phase',
        ]);
    }
}
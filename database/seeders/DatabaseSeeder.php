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

        // Member Users
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

        // Tasks assigned to members
        $task1 = Task::create([
            'user_id' => $member1->id,
            'title' => 'Define API specs',
            'description' => 'Create comprehensive API documentation for the new endpoints',
            'status' => 'todo',
            'priority' => 'high',
            'due_date' => now()->addDays(2),
        ]);

        $task2 = Task::create([
            'user_id' => $member1->id,
            'title' => 'Sprint Planning',
            'description' => 'Prepare sprint planning meeting agenda and user stories',
            'status' => 'doing',
            'priority' => 'medium',
            'due_date' => now()->addDays(5),
        ]);

        $task3 = Task::create([
            'user_id' => $member2->id,
            'title' => 'QA Testing',
            'description' => 'Perform comprehensive QA testing on the new features',
            'status' => 'done',
            'priority' => 'low',
            'due_date' => now()->subDays(1),
        ]);

        $task4 = Task::create([
            'user_id' => $member2->id,
            'title' => 'UI/UX Design Review',
            'description' => 'Review and provide feedback on the new dashboard design',
            'status' => 'todo',
            'priority' => 'high',
            'due_date' => now()->addDays(3),
        ]);

        $task5 = Task::create([
            'user_id' => $member3->id,
            'title' => 'Database Optimization',
            'description' => 'Optimize database queries for better performance',
            'status' => 'doing',
            'priority' => 'urgent',
            'due_date' => now()->addDays(1),
        ]);

        // Activity
        Activity::create([
            'task_id' => $task3->id,
            'user_id' => $member2->id,
            'title' => 'Task completed',
            'description' => 'Finished QA Testing phase',
            'category' => 'task',
            'is_read' => false,
            'link' => '/member/dashboard',
            'occurred_at' => now(),
        ]);
    }
}
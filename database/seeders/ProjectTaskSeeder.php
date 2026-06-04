<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectTaskSeeder extends Seeder
{
    /**
     * Seed tasks for existing project to match dashboard statistics:
     * - Total: 7 tasks
     * - Completed: 1 task
     * - Overdue: 3 tasks
     * - Active: 3 tasks (todo/doing)
     */
    public function run(): void
    {
        // Get the project (ID 1 - "jhjhjh")
        $project = Project::find(1);

        if (!$project) {
            $this->command->error('❌ Project with ID 1 not found!');
            return;
        }

        // Get users to assign tasks
        $admin = User::where('role', 'admin')->first();
        $members = User::where('role', 'member')->get();
        
        // If no members exist, use admin for all tasks
        $assignableUsers = $members->isEmpty() ? collect([$admin]) : $members;

        $this->command->info("📝 Creating tasks for project: {$project->name}");

        // 1. Create 1 COMPLETED task
        Task::create([
            'project_id' => $project->id,
            'user_id' => $assignableUsers->random()->id,
            'title' => 'Setup Project Repository',
            'description' => 'Initialize Git repository and setup basic project structure',
            'status' => 'done',
            'priority' => 'high',
            'due_date' => now()->subDays(5),
        ]);
        $this->command->info('✅ Created 1 completed task');

        // 2. Create 3 OVERDUE tasks
        $overdueTasks = [
            [
                'title' => 'Fix Critical Security Vulnerability',
                'description' => 'Address security issue found in authentication module',
                'priority' => 'urgent',
                'due_date' => now()->subDays(2),
            ],
            [
                'title' => 'Update Dependencies',
                'description' => 'Update all outdated npm packages to latest versions',
                'priority' => 'medium',
                'due_date' => now()->subDays(4),
            ],
            [
                'title' => 'Database Optimization',
                'description' => 'Optimize slow queries and add missing indexes',
                'priority' => 'high',
                'due_date' => now()->subDays(1),
            ],
        ];

        foreach ($overdueTasks as $taskData) {
            Task::create([
                'project_id' => $project->id,
                'user_id' => $assignableUsers->random()->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => 'overdue',
                'priority' => $taskData['priority'],
                'due_date' => $taskData['due_date'],
            ]);
        }
        $this->command->info('⚠️  Created 3 overdue tasks');

        // 3. Create 3 ACTIVE tasks (todo/doing)
        $activeTasks = [
            [
                'title' => 'Implement User Authentication',
                'description' => 'Build login, register, and password reset functionality',
                'status' => 'doing',
                'priority' => 'high',
                'due_date' => now()->addDays(5),
            ],
            [
                'title' => 'Design Dashboard UI',
                'description' => 'Create wireframes and mockups for admin dashboard',
                'status' => 'todo',
                'priority' => 'medium',
                'due_date' => now()->addDays(7),
            ],
            [
                'title' => 'Write API Documentation',
                'description' => 'Document all REST API endpoints with examples',
                'status' => 'todo',
                'priority' => 'low',
                'due_date' => now()->addDays(10),
            ],
        ];

        foreach ($activeTasks as $taskData) {
            Task::create([
                'project_id' => $project->id,
                'user_id' => $assignableUsers->random()->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => $taskData['status'],
                'priority' => $taskData['priority'],
                'due_date' => $taskData['due_date'],
            ]);
        }
        $this->command->info('🔵 Created 3 active tasks (1 doing, 2 todo)');

        // Summary
        $totalTasks = Task::where('project_id', $project->id)->count();
        $completedTasks = Task::where('project_id', $project->id)->where('status', 'done')->count();
        $overdueTasks = Task::where('project_id', $project->id)->where('status', 'overdue')->count();

        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info("   Total Tasks: {$totalTasks}");
        $this->command->info("   Completed: {$completedTasks}");
        $this->command->info("   Overdue: {$overdueTasks}");
        $this->command->info("   Active: " . ($totalTasks - $completedTasks - $overdueTasks));
        $this->command->info('');
        $this->command->info('✨ Tasks seeded successfully!');
    }
}

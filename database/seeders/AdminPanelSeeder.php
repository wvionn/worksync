<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminPanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Alexander Wright',
            'email' => 'admin@curator.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Team Members
        $members = [
            ['name' => 'Sarah Johnson', 'email' => 'sarah@curator.com'],
            ['name' => 'Michael Chen', 'email' => 'michael@curator.com'],
            ['name' => 'Emily Davis', 'email' => 'emily@curator.com'],
            ['name' => 'James Wilson', 'email' => 'james@curator.com'],
        ];

        $users = collect($members)->map(function ($member) {
            return User::create([
                'name' => $member['name'],
                'email' => $member['email'],
                'password' => Hash::make('password'),
                'role' => 'member',
                'email_verified_at' => now(),
            ]);
        });

        // Create Projects
        $projects = [
            [
                'name' => 'NeoCore Cloud Infrastructure',
                'client_name' => 'NeoCore Technologies',
                'status' => 'active',
                'priority' => 'high',
                'progress' => 82,
                'due_date' => now()->addDays(15),
            ],
            [
                'name' => 'Artemis Retail App',
                'client_name' => 'Artemis Retail Group',
                'status' => 'active',
                'priority' => 'medium',
                'progress' => 45,
                'due_date' => now()->addDays(30),
            ],
            [
                'name' => 'UX Audit - Client Portal',
                'client_name' => 'Global Finance Corp',
                'status' => 'active',
                'priority' => 'urgent',
                'progress' => 95,
                'due_date' => now()->addDays(5),
            ],
            [
                'name' => 'Marketing Website Redesign',
                'client_name' => 'TechStart Inc',
                'status' => 'planning',
                'priority' => 'medium',
                'progress' => 15,
                'due_date' => now()->addDays(45),
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create([
                ...$projectData,
                'owner_id' => $admin->id,
            ]);

            // Create tasks for each project
            $this->createTasksForProject($project, $users);
        }
    }

    private function createTasksForProject(Project $project, $users): void
    {
        $taskTemplates = [
            ['title' => 'Define API specs', 'status' => 'done', 'priority' => 'high'],
            ['title' => 'Sprint Planning', 'status' => 'doing', 'priority' => 'high'],
            ['title' => 'Design Audit', 'status' => 'doing', 'priority' => 'medium'],
            ['title' => 'QA Testing', 'status' => 'todo', 'priority' => 'medium'],
            ['title' => 'Code Review', 'status' => 'todo', 'priority' => 'low'],
        ];

        foreach ($taskTemplates as $index => $template) {
            Task::create([
                'project_id' => $project->id,
                'user_id' => $users->random()->id,
                'title' => $template['title'] . ' - ' . $project->name,
                'description' => 'Task description for ' . $template['title'],
                'status' => $template['status'],
                'priority' => $template['priority'],
                'due_date' => now()->addDays(rand(5, 30)),
            ]);
        }

        // Add some overdue tasks
        if (rand(0, 1)) {
            Task::create([
                'project_id' => $project->id,
                'user_id' => $users->random()->id,
                'title' => 'Urgent Bug Fix - ' . $project->name,
                'description' => 'Critical bug that needs immediate attention',
                'status' => 'overdue',
                'priority' => 'urgent',
                'due_date' => now()->subDays(rand(1, 5)),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@curator.com'],
            [
                'name' => 'Alexander Wright',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Team Members
        $members = [
            ['name' => 'Sarah Johnson', 'email' => 'sarah@curator.com', 'role' => 'member'],
            ['name' => 'Michael Chen', 'email' => 'michael@curator.com', 'role' => 'member'],
            ['name' => 'Emily Davis', 'email' => 'emily@curator.com', 'role' => 'member'],
            ['name' => 'James Wilson', 'email' => 'james@curator.com', 'role' => 'member'],
            ['name' => 'Lisa Anderson', 'email' => 'lisa@curator.com', 'role' => 'member'],
        ];

        $users = collect();
        foreach ($members as $member) {
            $users->push(User::firstOrCreate(
                ['email' => $member['email']],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('password'),
                    'role' => $member['role'],
                    'email_verified_at' => now(),
                ]
            ));
        }

        // Create Projects with realistic data
        $projectsData = [
            [
                'name' => 'NeoCore Cloud Infrastructure',
                'client_name' => 'NeoCore Technologies',
                'status' => 'active',
                'priority' => 'high',
                'due_date' => now()->addDays(15),
                'tasks' => [
                    ['title' => 'Setup AWS Infrastructure', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'Configure Load Balancers', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'Implement Auto-Scaling', 'status' => 'doing', 'priority' => 'high'],
                    ['title' => 'Security Audit', 'status' => 'doing', 'priority' => 'urgent'],
                    ['title' => 'Performance Testing', 'status' => 'todo', 'priority' => 'medium'],
                    ['title' => 'Documentation', 'status' => 'todo', 'priority' => 'low'],
                ],
            ],
            [
                'name' => 'Artemis Retail App',
                'client_name' => 'Artemis Retail Group',
                'status' => 'active',
                'priority' => 'medium',
                'due_date' => now()->addDays(30),
                'tasks' => [
                    ['title' => 'Design UI/UX Mockups', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'Develop Product Catalog', 'status' => 'doing', 'priority' => 'high'],
                    ['title' => 'Implement Shopping Cart', 'status' => 'doing', 'priority' => 'high'],
                    ['title' => 'Payment Gateway Integration', 'status' => 'todo', 'priority' => 'urgent'],
                    ['title' => 'User Authentication', 'status' => 'todo', 'priority' => 'high'],
                    ['title' => 'Order Management System', 'status' => 'todo', 'priority' => 'medium'],
                    ['title' => 'Mobile Responsive Design', 'status' => 'todo', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'UX Audit - Client Portal',
                'client_name' => 'Global Finance Corp',
                'status' => 'active',
                'priority' => 'urgent',
                'due_date' => now()->addDays(5),
                'tasks' => [
                    ['title' => 'Heuristic Evaluation', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'User Testing Sessions', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'Accessibility Audit', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'Compile Findings Report', 'status' => 'doing', 'priority' => 'urgent'],
                    ['title' => 'Present to Stakeholders', 'status' => 'todo', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Marketing Website Redesign',
                'client_name' => 'TechStart Inc',
                'status' => 'planning',
                'priority' => 'medium',
                'due_date' => now()->addDays(45),
                'tasks' => [
                    ['title' => 'Competitor Analysis', 'status' => 'done', 'priority' => 'medium'],
                    ['title' => 'Define Brand Guidelines', 'status' => 'doing', 'priority' => 'high'],
                    ['title' => 'Wireframe Homepage', 'status' => 'todo', 'priority' => 'high'],
                    ['title' => 'Design Landing Pages', 'status' => 'todo', 'priority' => 'medium'],
                    ['title' => 'Content Strategy', 'status' => 'todo', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'Mobile Banking App',
                'client_name' => 'SecureBank Ltd',
                'status' => 'active',
                'priority' => 'urgent',
                'due_date' => now()->addDays(20),
                'tasks' => [
                    ['title' => 'Biometric Authentication', 'status' => 'done', 'priority' => 'urgent'],
                    ['title' => 'Transaction History UI', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'Push Notifications', 'status' => 'doing', 'priority' => 'high'],
                    ['title' => 'Bill Payment Feature', 'status' => 'doing', 'priority' => 'high'],
                    ['title' => 'Security Penetration Test', 'status' => 'todo', 'priority' => 'urgent'],
                    ['title' => 'App Store Submission', 'status' => 'todo', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'E-Learning Platform',
                'client_name' => 'EduTech Solutions',
                'status' => 'active',
                'priority' => 'high',
                'due_date' => now()->addDays(35),
                'tasks' => [
                    ['title' => 'Video Streaming Setup', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'Course Management System', 'status' => 'done', 'priority' => 'high'],
                    ['title' => 'Student Dashboard', 'status' => 'doing', 'priority' => 'high'],
                    ['title' => 'Quiz & Assessment Module', 'status' => 'doing', 'priority' => 'medium'],
                    ['title' => 'Certificate Generation', 'status' => 'todo', 'priority' => 'medium'],
                    ['title' => 'Discussion Forum', 'status' => 'todo', 'priority' => 'low'],
                ],
            ],
        ];

        foreach ($projectsData as $projectData) {
            $tasks = $projectData['tasks'];
            unset($projectData['tasks']);

            $project = Project::firstOrCreate(
                ['name' => $projectData['name']],
                [
                    ...$projectData,
                    'owner_id' => $admin->id,
                ]
            );

            // Assign random members to project (2-4 members per project)
            $assignedMembers = $users->random(rand(2, 4));
            $project->members()->syncWithoutDetaching($assignedMembers->pluck('id')->toArray());

            // Create tasks for each project
            foreach ($tasks as $taskData) {
                Task::firstOrCreate(
                    [
                        'project_id' => $project->id,
                        'title' => $taskData['title'],
                    ],
                    [
                        'user_id' => $assignedMembers->random()->id, // Assign to one of the project members
                        'description' => 'Task description for ' . $taskData['title'],
                        'status' => $taskData['status'],
                        'priority' => $taskData['priority'],
                        'due_date' => now()->addDays(rand(5, 30)),
                    ]
                );
            }
        }

        // Add some overdue tasks for realism
        $overdueProjects = Project::whereIn('status', ['active'])->take(2)->get();
        foreach ($overdueProjects as $project) {
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

        // Create some activities
        $activities = [
            ['action' => 'Project Created - NeoCore Cloud Infrastructure', 'task_id' => null],
            ['action' => 'Task Completed - Setup AWS Infrastructure', 'task_id' => Task::where('status', 'done')->first()?->id],
            ['action' => 'New Team Member - Sarah Johnson joined', 'task_id' => null],
            ['action' => 'Milestone Reached - UX Audit 95% complete', 'task_id' => null],
        ];

        foreach ($activities as $activityData) {
            Activity::create([
                'user_id' => $admin->id,
                'task_id' => $activityData['task_id'],
                'action' => $activityData['action'],
            ]);
        }

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('📧 Admin: admin@curator.com | Password: password');
        $this->command->info('📊 Created: ' . Project::count() . ' projects, ' . Task::count() . ' tasks, ' . User::count() . ' users');
    }
}

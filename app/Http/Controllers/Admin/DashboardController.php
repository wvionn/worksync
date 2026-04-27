<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'done')->count();

        $overdueTasks = Task::query()
            ->where('status', '!=', 'done')
            ->where(function ($query): void {
                $query
                    ->where('status', 'overdue')
                    ->orWhereDate('due_date', '<', now()->toDateString());
            })
            ->count();

        $completionRate = $totalTasks > 0
            ? (int) round(($completedTasks / $totalTasks) * 100)
            : 0;

        $activeProjects = Project::query()
            ->withCount(['tasks', 'completedTasks'])
            ->whereIn('status', ['planning', 'active', 'on_hold'])
            ->latest('updated_at')
            ->take(3)
            ->get();

        $boardPreview = [
            'todo' => Task::query()
                ->where('status', 'todo')
                ->orderByDesc('updated_at')
                ->take(3)
                ->get(),
            'doing' => Task::query()
                ->where('status', 'doing')
                ->orderByDesc('updated_at')
                ->take(3)
                ->get(),
            'done' => Task::query()
                ->where('status', 'done')
                ->orderByDesc('updated_at')
                ->take(3)
                ->get(),
        ];

        $newProjectsThisWeek = Project::query()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $newTasksThisWeek = Task::query()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $recentActivities = Activity::query()
            ->orderByDesc('occurred_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('admin.dashboard', [
            'totalProjects' => $totalProjects,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'overdueTasks' => $overdueTasks,
            'completionRate' => $completionRate,
            'activeProjects' => $activeProjects,
            'boardPreview' => $boardPreview,
            'newProjectsThisWeek' => $newProjectsThisWeek,
            'newTasksThisWeek' => $newTasksThisWeek,
            'recentActivities' => $recentActivities,
        ]);
    }
}

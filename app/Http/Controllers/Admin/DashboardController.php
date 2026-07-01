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
            'overdue' => Task::query()
                ->where(function ($query): void {
                    $query->where('status', 'overdue')
                        ->orWhere(function ($q): void {
                            $q->where('status', '!=', 'done')
                                ->whereDate('due_date', '<', now()->toDateString());
                        });
                })
                ->orderBy('due_date', 'asc')
                ->take(3)
                ->get(),
        ];

        $newProjectsThisWeek = Project::query()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $newTasksThisWeek = Task::query()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $previousProjects = Project::where('created_at', '<', now()->startOfWeek())->count();
        $projectChangeRate = $previousProjects > 0
            ? (int) round(($newProjectsThisWeek / $previousProjects) * 100)
            : ($newProjectsThisWeek > 0 ? 100 : 0);

        $previousTasks = Task::where('created_at', '<', now()->startOfWeek())->count();
        $taskChangeRate = $previousTasks > 0
            ? (int) round(($newTasksThisWeek / $previousTasks) * 100)
            : ($newTasksThisWeek > 0 ? 100 : 0);

        $statusCounts = Task::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $priorityCounts = Task::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        $statusCounts = array_merge([
            'todo' => 0,
            'doing' => 0,
            'in_review' => 0,
            'done' => 0,
            'overdue' => 0,
        ], $statusCounts);

        $priorityCounts = array_merge([
            'low' => 0,
            'medium' => 0,
            'high' => 0,
            'urgent' => 0,
        ], $priorityCounts);

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
            'projectChangeRate' => $projectChangeRate,
            'taskChangeRate' => $taskChangeRate,
            'recentActivities' => $recentActivities,
            'statusCounts' => $statusCounts,
            'priorityCounts' => $priorityCounts,
        ]);
    }
}

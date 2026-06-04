<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $tasks = Task::with('project')
            ->where('user_id', $user->id)
            ->get();

        $todoTasks = $tasks->where('status', 'todo');
        $doingTasks = $tasks->where('status', 'doing');
        $doneTasks = $tasks->where('status', 'done');

        $stats = [
            'totalTasks' => $tasks->count(),
            'doingTasks' => $doingTasks->count(),
            'doneTasks' => $doneTasks->count(),
            'overdueTasks' => $tasks
                ->where('status', '!=', 'done')
                ->filter(function ($task) {
                    return $task->due_date &&
                           \Carbon\Carbon::parse($task->due_date)->isPast();
                })
                ->count(),
        ];

        $urgentTasks = $tasks
            ->where('status', '!=', 'done')
            ->sortBy('due_date')
            ->take(5);

        return view('member.dashboard', [
            'todoTasks' => $todoTasks,
            'doingTasks' => $doingTasks,
            'doneTasks' => $doneTasks,
            'stats' => $stats,
            'urgentTasks' => $urgentTasks,
        ]);
    }
}
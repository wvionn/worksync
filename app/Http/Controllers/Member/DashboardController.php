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
        
        // Get tasks assigned to this member
        $todoTasks = Task::with('project')
            ->where('user_id', $user->id)
            ->where('status', 'todo')
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();

        $doingTasks = Task::with('project')
            ->where('user_id', $user->id)
            ->where('status', 'doing')
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();

        $doneTasks = Task::with('project')
            ->where('user_id', $user->id)
            ->where('status', 'done')
            ->latest('updated_at')
            ->get();

        return view('member.dashboard', [
            'todoTasks' => $todoTasks,
            'doingTasks' => $doingTasks,
            'doneTasks' => $doneTasks,
        ]);
    }
}

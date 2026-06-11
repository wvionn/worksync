<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Get only tasks that are assigned to this specific user
        $allTasks = Task::where('user_id', $user->id)
            ->with('project')
            ->get();
        
        // Separate tasks by status
        $todoTasks = $allTasks->where('status', 'todo');
        $doingTasks = $allTasks->where('status', 'doing');
        $doneTasks = $allTasks->where('status', 'done');
        
        // Get projects count where user is assigned as member
        $projectsCount = Project::whereHas('members', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->count();
        
        return view('member.dashboard', compact('todoTasks', 'doingTasks', 'doneTasks', 'projectsCount'));
    }
}
<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Get only projects where current user is assigned as member
        $projects = Project::whereHas('members', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->with(['members'])
        ->get()
        ->map(function ($project) use ($user) {
            // Get only tasks assigned to this user
            $userTasks = $project->tasks()->where('user_id', $user->id)->get();
            $completedTasks = $userTasks->where('status', 'done')->count();
            $totalTasks = $userTasks->count();
            
            // Calculate progress based on user's tasks only
            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            
            return [
                'id' => $project->id,
                'name' => $project->name,
                'progress' => $progress,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'status' => $project->status,
                'priority' => $project->priority,
            ];
        });

        return view('member.projects.index', compact('projects'));
    }

    public function show(Project $project, Request $request): View
    {
        $user = $request->user();
        
        // Check if user is member of this project
        if (!$project->members->contains($user->id)) {
            abort(403, 'You are not assigned to this project.');
        }

        $project->load(['members', 'owner', 'tasks']);

        // Calculate task breakdown
        $tasks = $project->tasks;
        $completedTasks = $tasks->where('status', 'done')->count();
        $totalTasks = $tasks->count();
        $recentlyAddedTasks = $tasks->where('created_at', '>=', now()->subDays(7))->count();

        $taskBreakdown = [
            'completed' => $completedTasks,
            'total' => $totalTasks,
            'recently_added' => $recentlyAddedTasks,
        ];

        $activities = \App\Models\Activity::where(function($query) use ($project) {
            $query->whereHas('task', function($q) use ($project) {
                $q->where('project_id', $project->id);
            })->orWhere(function($q) use ($project) {
                $q->where('category', 'project')
                  ->where('description', 'like', "%{$project->name}%");
            });
        })
        ->orderBy('occurred_at', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

        return view('member.projects.show', compact('project', 'taskBreakdown', 'activities'));
    }
}

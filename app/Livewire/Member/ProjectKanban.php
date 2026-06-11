<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Project;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ProjectKanban extends Component
{
    public $projectId;
    public $project;

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->project = Project::with(['members', 'owner'])->findOrFail($projectId);
        
        // Check if user is member of this project
        if (!$this->project->members->contains(Auth::id())) {
            abort(403, 'You are not assigned to this project.');
        }
    }

    public function updateTaskStatus($taskId, $newStatus)
    {
        if (!in_array($newStatus, ['todo', 'doing', 'done'])) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Status tidak valid.'
            ]);
            return;
        }

        $task = Task::find($taskId);
        
        if (!$task) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Task tidak ditemukan.'
            ]);
            return;
        }

        // Ensure the task belongs to this project
        if ($task->project_id !== $this->projectId) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Task ini tidak berada di project ini.'
            ]);
            return;
        }

        // Ensure the task is assigned to this member
        if ($task->user_id !== Auth::id()) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Anda tidak berwenang mengedit task ini.'
            ]);
            return;
        }

        $oldStatus = $task->status;
        $task->update([
            'status' => $newStatus,
            'completed_at' => $newStatus === 'done' ? now() : null,
        ]);

        // Create activity log
        Activity::create([
            'user_id' => Auth::id(),
            'title' => 'Task status updated',
            'description' => "Task '{$task->title}' status changed from {$oldStatus} to {$newStatus}.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('member.projects.show', $this->projectId),
            'occurred_at' => now(),
        ]);

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Status task berhasil diperbarui! ✅'
        ]);

        // Refresh data
        $this->project->refresh();
    }

    public function render()
    {
        $user = Auth::user();
        
        // Get ONLY tasks assigned to this user in this project
        $myTasks = Task::where('project_id', $this->projectId)
            ->where('user_id', $user->id)
            ->with('user')
            ->get();
        
        // Separate by status
        $todoTasks = $myTasks->where('status', 'todo')->sortBy('due_date');
        $doingTasks = $myTasks->where('status', 'doing')->sortBy('due_date');
        $doneTasks = $myTasks->where('status', 'done')->sortByDesc('updated_at');
        
        // Overdue tasks (not done + past due date)
        $overdueTasks = $myTasks->filter(function($task) {
            return $task->status !== 'done' && $task->due_date && $task->due_date->isPast();
        })->sortBy('due_date');
        
        // Task breakdown for this user only
        $taskBreakdown = [
            'total' => $myTasks->count(),
            'completed' => $doneTasks->count(),
            'in_progress' => $doingTasks->count(),
            'todo' => $todoTasks->count(),
            'recently_added' => $myTasks->filter(function($task) {
                return $task->created_at >= now()->subWeek();
            })->count(),
        ];

        return view('livewire.member.project-kanban', [
            'todoTasks' => $todoTasks,
            'doingTasks' => $doingTasks,
            'doneTasks' => $doneTasks,
            'overdueTasks' => $overdueTasks,
            'taskBreakdown' => $taskBreakdown,
        ]);
    }
}

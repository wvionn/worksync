<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class QuickTaskCard extends Component
{
    public $task;

    public function mount($task)
    {
        $this->task = $task;
    }

    public function updateStatus($newStatus)
    {
        if (!in_array($newStatus, ['todo', 'doing', 'done'])) {
            return;
        }

        // Ensure the task is assigned to this member
        if ($this->task->user_id !== Auth::id()) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Anda tidak berwenang mengedit task ini.'
            ]);
            return;
        }

        $oldStatus = $this->task->status;
        $this->task->update([
            'status' => $newStatus,
            'completed_at' => $newStatus === 'done' ? now() : null,
        ]);

        // Create activity log
        Activity::create([
            'user_id' => Auth::id(),
            'title' => 'Task status updated',
            'description' => "Task '{$this->task->title}' status changed from {$oldStatus} to {$newStatus}.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('member.dashboard'),
            'occurred_at' => now(),
        ]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Status updated! ✅'
        ]);

        $this->dispatch('task-updated');
        $this->task->refresh();
    }

    public function render()
    {
        return view('livewire.member.quick-task-card');
    }
}

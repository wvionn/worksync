<?php

namespace App\Livewire\Member;

use Livewire\Component;

use App\Models\Project;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class KanbanBoard extends Component
{
    public function updateStatus($taskId, $newStatus)
    {
        if (!in_array($newStatus, ['todo', 'doing', 'done'])) {
            session()->flash('error_message', 'Status tidak valid.');
            return;
        }

        $task = Task::find($taskId);
        if (!$task) {
            session()->flash('error_message', 'Task tidak ditemukan.');
            return;
        }

        $activeProject = Project::query()
            ->whereIn('status', Project::ACTIVE_STATUSES)
            ->latest('updated_at')
            ->first();

        if (!$activeProject || $task->project_id !== $activeProject->id) {
            session()->flash('error_message', 'Task ini tidak berada di project aktif.');
            return;
        }

        // Ensure the task is assigned to this member
        if ($task->user_id !== Auth::id()) {
            session()->flash('error_message', 'Anda tidak berwenang mengedit task ini.');
            return;
        }

        // Limit WIP: User can only have one task in 'doing' status at a time
        if ($newStatus === 'doing') {
            $existingDoingTask = Task::where('user_id', Auth::id())
                ->where('status', 'doing')
                ->where('id', '!=', $taskId)
                ->first();

            if ($existingDoingTask) {
                session()->flash('error_message', 'Anda hanya dapat mengerjakan satu pekerjaan dalam satu waktu. Selesaikan terlebih dahulu yang sedang berjalan.');
                return;
            }
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
            'link' => route('member.dashboard'),
            'occurred_at' => now(),
        ]);

        session()->flash('success_message', 'Status task berhasil diperbarui.');
    }

    public function render()
    {
        $user = Auth::user();

        $activeProject = Project::query()
            ->whereIn('status', Project::ACTIVE_STATUSES)
            ->latest('updated_at')
            ->first();
        
        // Get tasks assigned to this member
        $todoTasks = Task::with('project')
            ->where('user_id', $user->id)
            ->when(
                $activeProject,
                fn ($query) => $query->where('project_id', $activeProject->id),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->where('status', 'todo')
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();

        $doingTasks = Task::with('project')
            ->where('user_id', $user->id)
            ->when(
                $activeProject,
                fn ($query) => $query->where('project_id', $activeProject->id),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->where('status', 'doing')
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();

        $doneTasks = Task::with('project')
            ->where('user_id', $user->id)
            ->when(
                $activeProject,
                fn ($query) => $query->where('project_id', $activeProject->id),
                fn ($query) => $query->whereRaw('1 = 0')
            )
            ->where('status', 'done')
            ->latest('updated_at')
            ->get();

        return view('livewire.member.kanban-board', [
            'todoTasks' => $todoTasks,
            'doingTasks' => $doingTasks,
            'doneTasks' => $doneTasks,
        ]);
    }
}

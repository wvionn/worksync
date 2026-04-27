<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = (string) $request->string('status');
        $search = (string) $request->string('search');

        $tasks = Task::query()
            ->with(['project', 'assignee'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhereHas('project', function ($projectQuery) use ($search): void {
                            $projectQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($statusFilter !== '', function ($query) use ($statusFilter): void {
                $query->where('status', $statusFilter);
            })
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.tasks.index', [
            'tasks' => $tasks,
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'search' => $search,
            'statusFilter' => $statusFilter,
            'statusOptions' => ['todo', 'doing', 'done', 'overdue'],
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['todo', 'doing', 'done', 'overdue'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
        ]);

        $validated = $this->normalizeTaskState($validated);

        $task = Task::create($validated);

        Activity::create([
            'user_id' => $request->user()->id,
            'title' => 'Task created',
            'description' => "Task {$task->title} was created.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('admin.tasks.index'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success_message', 'Task berhasil dibuat.');
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['todo', 'doing', 'done', 'overdue'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
        ]);

        $validated = $this->normalizeTaskState($validated);

        $task->update($validated);

        Activity::create([
            'user_id' => $request->user()->id,
            'title' => 'Task updated',
            'description' => "Task {$task->title} was updated.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('admin.tasks.index'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success_message', 'Task berhasil diperbarui.');
    }

    public function complete(Request $request, Task $task): RedirectResponse
    {
        $task->update([
            'status' => 'done',
            'completed_at' => now(),
        ]);

        Activity::create([
            'user_id' => $request->user()->id,
            'title' => 'Task completed',
            'description' => "Task {$task->title} was completed.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('admin.tasks.index'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success_message', 'Task ditandai selesai.');
    }

    /**
     * Ensure task state is consistent with due date and completion timestamp.
     *
     * @param  array<string, mixed>  $taskData
     * @return array<string, mixed>
     */
    private function normalizeTaskState(array $taskData): array
    {
        if (($taskData['status'] ?? null) === 'done') {
            $taskData['completed_at'] = now();

            return $taskData;
        }

        $taskData['completed_at'] = null;

        $dueDate = $taskData['due_date'] ?? null;
        if ($dueDate && $dueDate < now()->toDateString()) {
            $taskData['status'] = 'overdue';
        }

        return $taskData;
    }
}

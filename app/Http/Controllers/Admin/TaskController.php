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

        // For Kanban view
        $todoTasks = Task::with('user', 'project')
            ->where('status', 'todo')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();

        $doingTasks = Task::with('user', 'project')
            ->where('status', 'doing')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();

        $doneTasks = Task::with('user', 'project')
            ->where('status', 'done')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest('updated_at')
            ->get();

        return view('admin.tasks.index', [
            'todoTasks' => $todoTasks,
            'doingTasks' => $doingTasks,
            'doneTasks' => $doneTasks,
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'search' => $search,
            'statusFilter' => $statusFilter,
            'statusOptions' => ['todo', 'doing', 'done', 'overdue'],
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    public function create(): View
    {
        return view('admin.tasks.create', [
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
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
            'user_id' => ['nullable', 'exists:users,id'],
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

        // Redirect back to project if task was created from project page
        if ($validated['project_id'] && $request->has('redirect_to_project')) {
            return redirect()
                ->route('admin.projects.show', $validated['project_id'])
                ->with('success_message', 'Task berhasil dibuat.');
        }

        return redirect()
            ->route('admin.tasks.index')
            ->with('success_message', 'Task berhasil dibuat.');
    }

    public function show(Task $task): View
    {
        $task->load(['project', 'user']);
        
        return view('admin.tasks.show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $task): View
    {
        return view('admin.tasks.edit', [
            'task' => $task,
            'projects' => Project::query()->orderBy('name')->get(['id', 'name']),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'statusOptions' => ['todo', 'doing', 'done', 'overdue'],
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['todo', 'doing', 'done', 'overdue'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'user_id' => ['nullable', 'exists:users,id'],
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

    public function destroy(Task $task): RedirectResponse
    {
        $taskTitle = $task->title;
        $task->delete();

        Activity::create([
            'user_id' => auth()->id(),
            'title' => 'Task deleted',
            'description' => "Task {$taskTitle} was deleted.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('admin.tasks.index'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.tasks.index')
            ->with('success_message', 'Task berhasil dihapus.');
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

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
            ->where(function ($query) {
                $query->whereNull('due_date')
                    ->orWhereDate('due_date', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->whereDate('due_date', '=', now()->toDateString())
                            ->where(function ($sub) {
                                $sub->whereTime('due_date', '=', '00:00:00')
                                    ->orWhere('due_date', '>=', now());
                            });
                    });
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->get();

        $doingTasks = Task::with('user', 'project')
            ->where('status', 'doing')
            ->where(function ($query) {
                $query->whereNull('due_date')
                    ->orWhereDate('due_date', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->whereDate('due_date', '=', now()->toDateString())
                            ->where(function ($sub) {
                                $sub->whereTime('due_date', '=', '00:00:00')
                                    ->orWhere('due_date', '>=', now());
                            });
                    });
            })
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

        $inReviewTasks = Task::with('user', 'project')
            ->where('status', 'in_review')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest('updated_at')
            ->get();

        $overdueTasks = Task::with('user', 'project')
            ->where(function ($query): void {
                $query->where('status', 'overdue')
                    ->orWhere(function ($q): void {
                        $q->whereNotIn('status', ['done', 'in_review'])
                            ->where(function ($inner) {
                                $inner->whereDate('due_date', '<', now()->toDateString())
                                    ->orWhere(function ($sub) {
                                        $sub->where('due_date', '<', now())
                                            ->whereTime('due_date', '!=', '00:00:00');
                                    });
                            });
                    });
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->get();

        return view('admin.tasks.index', [
            'todoTasks' => $todoTasks,
            'doingTasks' => $doingTasks,
            'inReviewTasks' => $inReviewTasks,
            'doneTasks' => $doneTasks,
            'overdueTasks' => $overdueTasks,
            'projects' => Project::with('members:id,name')->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'search' => $search,
            'statusFilter' => $statusFilter,
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    public function create(): View
    {
        return view('admin.tasks.create', [
            'projects' => Project::with('members:id,name')->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
            'labels' => \App\Models\Label::all(),
            'milestones' => \App\Models\Milestone::all(),
            'tasks' => Task::with('project')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'user_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'milestone_id' => ['nullable', 'exists:milestones,id'],
            'is_blocked' => ['nullable', 'boolean'],
            'blocker_description' => ['nullable', 'string'],
        ]);

        $validated['is_blocked'] = $request->has('is_blocked');
        if (!$validated['is_blocked']) {
            $validated['blocker_description'] = null;
        }
        $validated['status'] = 'todo';

        $validated = $this->normalizeTaskState($validated);

        $task = Task::create($validated);

        // Sync labels
        if ($request->has('label_ids')) {
            $task->labels()->sync($request->input('label_ids'));
        }

        // Sync dependencies
        if ($request->has('dependency_ids')) {
            $task->dependencies()->sync($request->input('dependency_ids'));
        }

        Activity::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'title' => 'Task created',
            'description' => "Task {$task->title} was created.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('admin.tasks.show', $task),
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
        $task->load([
            'project', 
            'user', 
            'comments.user', 
            'subtasks', 
            'attachments.user', 
            'labels', 
            'dependencies.project', 
            'dependents.project', 
            'milestone',
            'activities' => function($q) {
                $q->orderBy('occurred_at', 'desc')->orderBy('created_at', 'desc');
            }
        ]);
        
        return view('admin.tasks.show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $task): View
    {
        return view('admin.tasks.edit', [
            'task' => $task,
            'projects' => Project::with('members:id,name')->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
            'labels' => \App\Models\Label::all(),
            'milestones' => \App\Models\Milestone::all(),
            'tasks' => Task::with('project')->get(),
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'user_id' => ['nullable', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'milestone_id' => ['nullable', 'exists:milestones,id'],
            'is_blocked' => ['nullable', 'boolean'],
            'blocker_description' => ['nullable', 'string'],
        ]);

        $validated['is_blocked'] = $request->has('is_blocked');
        if (!$validated['is_blocked']) {
            $validated['blocker_description'] = null;
        }
        $validated['status'] = $task->status;

        $validated = $this->normalizeTaskState($validated);

        $task->update($validated);

        // Sync labels
        if ($request->has('label_ids')) {
            $task->labels()->sync($request->input('label_ids'));
        } else {
            $task->labels()->detach();
        }

        // Sync dependencies
        if ($request->has('dependency_ids')) {
            $task->dependencies()->sync($request->input('dependency_ids'));
        } else {
            $task->dependencies()->detach();
        }

        Activity::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'title' => 'Task updated',
            'description' => "Task {$task->title} was updated.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('admin.tasks.show', $task),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.tasks.show', $task)
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
            'task_id' => $task->id,
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

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        return redirect()
            ->back()
            ->with('error_message', 'Status task dikelola oleh member. Admin hanya dapat approve atau reject task yang masuk review.');
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
        if ($dueDate) {
            $date = \Illuminate\Support\Carbon::parse($dueDate);
            if ($date->hour === 0 && $date->minute === 0 && $date->second === 0) {
                $date->endOfDay();
            }
            if ($date->isPast()) {
                $taskData['status'] = 'overdue';
            }
        }

        return $taskData;
    }
}

<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Show member deadlines page.
     */
    public function deadlines(Request $request): View
    {
        $user = $request->user();
        $now = now();
        
        // Get all tasks with due dates in the current month
        $tasks = Task::where('user_id', $user->id)
            ->whereNotNull('due_date')
            ->whereYear('due_date', $now->year)
            ->whereMonth('due_date', $now->month)
            ->with('project')
            ->get();
            
        // Group tasks by day of month
        $tasksByDay = $tasks->groupBy(function($task) {
            return $task->due_date->day;
        });

        // Tasks due today
        $dueTodayTasks = Task::where('user_id', $user->id)
            ->whereDate('due_date', $now->toDateString())
            ->where('due_date', '>=', $now)
            ->where('status', '!=', 'done')
            ->with('project')
            ->get();

        // Upcoming tasks
        $upcomingTasks = Task::where('user_id', $user->id)
            ->whereDate('due_date', '>', $now->toDateString())
            ->where('status', '!=', 'done')
            ->with('project')
            ->orderBy('due_date', 'asc')
            ->get();

        // Overdue tasks
        $overdueTasks = Task::where('user_id', $user->id)
            ->where('due_date', '<', $now)
            ->where('status', '!=', 'done')
            ->with('project')
            ->orderBy('due_date', 'asc')
            ->get();

        $daysInMonth = $now->daysInMonth;
        $startOfWeek = $now->copy()->startOfMonth()->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.

        return view('member.deadlines.index', compact(
            'tasksByDay',
            'dueTodayTasks',
            'upcomingTasks',
            'overdueTasks',
            'daysInMonth',
            'startOfWeek'
        ));
    }
    /**
     * Update task status (for member to change status)
     */
    public function updateStatus(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        // Ensure the task is assigned to this member
        if ($task->user_id !== $request->user()->id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error_message', 'You are not authorized to update this task.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['todo', 'doing', 'in_review', 'done'])],
        ]);

        // Dependency check
        if (in_array($validated['status'], ['doing', 'in_review', 'done'])) {
            $blockerMessage = $task->blockBecauseOfIncompleteDependencies(
                $request->user()->id,
                route('member.tasks.show', $task)
            );

            if ($blockerMessage) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => $blockerMessage], 422);
                }
                return redirect()->back()->with('error_message', $blockerMessage);
            }
        }

        $task->resolveDependencyBlockerIfClear();

        $oldStatus = $task->status;
        $task->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'done' ? now() : null,
        ]);

        // Create activity log
        Activity::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'title' => 'Task status updated',
            'description' => "Task '{$task->title}' status changed from {$oldStatus} to {$validated['status']}.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('member.tasks.show', $task),
            'occurred_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'task' => $task->fresh(),
            ]);
        }

        return redirect()->back()->with('success_message', 'Task status updated successfully.');
    }

    /**
     * Show task details
     */
    public function show(Task $task)
    {
        // Ensure the task is assigned to this member
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $task->load([
            'project', 
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

        return view('member.tasks.show', [
            'task' => $task,
        ]);
    }
}

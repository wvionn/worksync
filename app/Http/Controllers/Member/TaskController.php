<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
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
            'status' => ['required', Rule::in(['todo', 'doing', 'done'])],
        ]);

        $oldStatus = $task->status;
        $task->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'done' ? now() : null,
        ]);

        // Create activity log
        Activity::create([
            'user_id' => $request->user()->id,
            'title' => 'Task status updated',
            'description' => "Task '{$task->title}' status changed from {$oldStatus} to {$validated['status']}.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('member.dashboard'),
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

        $task->load('project');

        return view('member.tasks.show', [
            'task' => $task,
        ]);
    }
}

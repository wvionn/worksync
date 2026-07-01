<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskActionController extends Controller
{
    private function authorizeTaskAccess(Task $task): void
    {
        $user = Auth::user();
        if ($user->role === 'member') {
            // Check if user is assigned to this project
            if ($task->project && !$task->project->members->contains($user->id)) {
                abort(403, 'Unauthorized task access.');
            }
        }
    }

    public function postComment(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeTaskAccess($task);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        Comment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'title' => 'Comment posted',
            'description' => "Posted comment on task '{$task->title}'",
            'category' => 'task',
            'is_read' => false,
            'link' => Auth::user()->role === 'admin' ? route('admin.tasks.show', $task) : route('member.tasks.show', $task),
            'occurred_at' => now(),
        ]);

        return redirect()->back()->with('success_message', 'Comment posted successfully.');
    }

    public function deleteComment(Comment $comment): RedirectResponse
    {
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $comment->delete();
        return redirect()->back()->with('success_message', 'Comment deleted successfully.');
    }

    public function postSubtask(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeTaskAccess($task);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:190'],
        ]);

        Subtask::create([
            'task_id' => $task->id,
            'title' => $validated['title'],
            'is_completed' => false,
        ]);

        return redirect()->back()->with('success_message', 'Subtask added successfully.');
    }

    public function toggleSubtask(Subtask $subtask): RedirectResponse
    {
        $this->authorizeTaskAccess($subtask->task);

        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        return redirect()->back()->with('success_message', 'Subtask checklist updated.');
    }

    public function deleteSubtask(Subtask $subtask): RedirectResponse
    {
        $this->authorizeTaskAccess($subtask->task);

        $subtask->delete();
        return redirect()->back()->with('success_message', 'Subtask deleted.');
    }

    public function postAttachment(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeTaskAccess($task);

        $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10MB max
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('attachments', 'public');
        $fileSize = $file->getSize();

        Attachment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'title' => 'File attached',
            'description' => "Uploaded attachment {$fileName} to task '{$task->title}'",
            'category' => 'task',
            'is_read' => false,
            'link' => Auth::user()->role === 'admin' ? route('admin.tasks.show', $task) : route('member.tasks.show', $task),
            'occurred_at' => now(),
        ]);

        return redirect()->back()->with('success_message', 'Attachment uploaded successfully.');
    }

    public function deleteAttachment(Attachment $attachment): RedirectResponse
    {
        $this->authorizeTaskAccess($attachment->task);

        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();
        return redirect()->back()->with('success_message', 'Attachment deleted.');
    }

    public function toggleBlocker(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeTaskAccess($task);

        $isBlocked = $task->is_blocked;

        if ($isBlocked) {
            $task->update([
                'is_blocked' => false,
                'blocker_description' => null,
            ]);

            Activity::create([
                'user_id' => Auth::id(),
                'title' => 'Task unblocked',
                'description' => "Task '{$task->title}' was marked resolved.",
                'category' => 'task',
                'is_read' => false,
                'link' => Auth::user()->role === 'admin' ? route('admin.tasks.show', $task) : route('member.tasks.show', $task),
                'occurred_at' => now(),
            ]);

            return redirect()->back()->with('success_message', 'Blocker status resolved.');
        } else {
            $validated = $request->validate([
                'blocker_description' => ['required', 'string'],
            ]);

            $task->update([
                'is_blocked' => true,
                'blocker_description' => $validated['blocker_description'],
            ]);

            Activity::create([
                'user_id' => Auth::id(),
                'title' => 'Task blocked',
                'description' => "Task '{$task->title}' was blocked: " . $validated['blocker_description'],
                'category' => 'task',
                'is_read' => false,
                'link' => Auth::user()->role === 'admin' ? route('admin.tasks.show', $task) : route('member.tasks.show', $task),
                'occurred_at' => now(),
            ]);

            return redirect()->back()->with('success_message', 'Task flagged as blocked.');
        }
    }

    public function approveReview(Task $task): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $task->update([
            'status' => 'done',
            'completed_at' => now(),
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'title' => 'Task approved',
            'description' => "Task '{$task->title}' was approved and marked done.",
            'category' => 'task',
            'is_read' => false,
            'link' => route('admin.tasks.show', $task),
            'occurred_at' => now(),
        ]);

        return redirect()->back()->with('success_message', 'Task review approved successfully.');
    }

    public function rejectReview(Request $request, Task $task): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'feedback' => ['required', 'string'],
        ]);

        $task->update([
            'status' => 'doing',
            'completed_at' => null,
        ]);

        // Post feedback as comment
        Comment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'content' => "🔴 Task review rejected. Feedback: " . $validated['feedback'],
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'title' => 'Task review rejected',
            'description' => "Task '{$task->title}' review was rejected: " . $validated['feedback'],
            'category' => 'task',
            'is_read' => false,
            'link' => route('admin.tasks.show', $task),
            'occurred_at' => now(),
        ]);

        return redirect()->back()->with('success_message', 'Task review rejected with feedback.');
    }

    public function createMilestone(Request $request, Project $project): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ]);

        Milestone::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'status' => 'active',
        ]);

        return redirect()->back()->with('success_message', 'Milestone created successfully.');
    }
}

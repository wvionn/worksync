<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    private function getMemberActivitiesQuery()
    {
        $userId = Auth::id();
        return Activity::query()
            ->where(function($query) use ($userId) {
                $query->whereHas('task', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('task.project', function($q) use ($userId) {
                    $q->whereHas('members', function($m) use ($userId) {
                        $m->where('users.id', $userId);
                    });
                });
            })
            ->where('user_id', '!=', $userId); // ignore own actions
    }

    public function index(): View
    {
        $activities = $this->getMemberActivitiesQuery()
            ->with('user')
            ->orderByDesc('occurred_at')
            ->orderByDesc('created_at')
            ->paginate(14);

        $unreadCount = $this->getMemberActivitiesQuery()
            ->where('is_read', false)
            ->count();

        return view('member.notifications.index', [
            'activities' => $activities,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markRead(Activity $activity): RedirectResponse
    {
        // Simple security check: make sure this activity relates to their task or project
        $userId = Auth::id();
        $related = Activity::query()
            ->where('id', $activity->id)
            ->where(function($query) use ($userId) {
                $query->whereHas('task', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('task.project', function($q) use ($userId) {
                    $q->whereHas('members', function($m) use ($userId) {
                        $m->where('users.id', $userId);
                    });
                });
            })
            ->exists();

        if (!$related) {
            abort(403);
        }

        $activity->update(['is_read' => true]);

        return redirect()
            ->route('member.notifications.index')
            ->with('success_message', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllRead(): RedirectResponse
    {
        $userId = Auth::id();
        
        $this->getMemberActivitiesQuery()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()
            ->route('member.notifications.index')
            ->with('success_message', 'Semua notifikasi ditandai sudah dibaca.');
    }
}

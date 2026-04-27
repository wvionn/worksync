<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $activities = Activity::query()
            ->with('user')
            ->orderByDesc('occurred_at')
            ->orderByDesc('created_at')
            ->paginate(14);

        return view('admin.notifications.index', [
            'activities' => $activities,
            'unreadCount' => Activity::query()->where('is_read', false)->count(),
        ]);
    }

    public function markRead(Activity $activity): RedirectResponse
    {
        $activity->update(['is_read' => true]);

        return redirect()
            ->route('admin.notifications.index')
            ->with('success_message', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllRead(): RedirectResponse
    {
        Activity::query()->where('is_read', false)->update(['is_read' => true]);

        return redirect()
            ->route('admin.notifications.index')
            ->with('success_message', 'Semua notifikasi ditandai sudah dibaca.');
    }
}

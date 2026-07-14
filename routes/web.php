<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TaskActionController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\TaskController as MemberTaskController;
use App\Http\Controllers\Member\ProjectController as MemberProjectController;
use App\Http\Controllers\Member\SettingController as MemberSettingController;
use App\Http\Controllers\Member\NotificationController as MemberNotificationController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/admin/dashboard');

// Member Routes
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tasks/{task}', [MemberTaskController::class, 'show'])->name('tasks.show');
    Route::patch('/tasks/{task}/status', [MemberTaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Projects
    Route::get('/projects', [MemberProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [MemberProjectController::class, 'show'])->name('projects.show');

    // Other Routes
    Route::get('/deadlines', [MemberTaskController::class, 'deadlines'])->name('deadlines');
    Route::view('/chat', 'member.chat.index')->name('chat'); // Team chat (groups + members)
    Route::view('/admin-chat', 'member.admin-chat.index')->name('admin-chat'); // Chat with admin
    Route::view('/profile', 'member.profile.index')->name('profile');
    Route::get('/settings', [MemberSettingController::class, 'index'])->name('settings');
    Route::put('/settings', [MemberSettingController::class, 'update'])->name('settings.update');

    // Notifications Routes
    Route::get('/notifications', [MemberNotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{activity}/read', [MemberNotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [MemberNotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Task Actions
    Route::post('/tasks/{task}/comments', [TaskActionController::class, 'postComment'])->name('tasks.comments');
    Route::delete('/comments/{comment}', [TaskActionController::class, 'deleteComment'])->name('comments.destroy');
    Route::post('/tasks/{task}/attachments', [TaskActionController::class, 'postAttachment'])->name('tasks.attachments');
    Route::delete('/attachments/{attachment}', [TaskActionController::class, 'deleteAttachment'])->name('attachments.destroy');
    Route::post('/tasks/{task}/toggle-blocker', [TaskActionController::class, 'toggleBlocker'])->name('tasks.toggle-blocker');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/profile', [SettingController::class, 'profile'])->name('profile');
    Route::resource('projects', ProjectController::class);
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::resource('tasks', TaskController::class);
    Route::resource('users', UserController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Notifications Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{activity}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::patch('/notifications/{activity}/read-alias', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::view('/chat', 'admin.chat.index')->name('chat.index');

    // Task Actions
    Route::post('/tasks/{task}/comments', [TaskActionController::class, 'postComment'])->name('tasks.comments');
    Route::delete('/comments/{comment}', [TaskActionController::class, 'deleteComment'])->name('comments.destroy');
    Route::post('/tasks/{task}/attachments', [TaskActionController::class, 'postAttachment'])->name('tasks.attachments');
    Route::delete('/attachments/{attachment}', [TaskActionController::class, 'deleteAttachment'])->name('attachments.destroy');
    Route::post('/tasks/{task}/toggle-blocker', [TaskActionController::class, 'toggleBlocker'])->name('tasks.toggle-blocker');
    Route::post('/tasks/{task}/approve', [TaskActionController::class, 'approveReview'])->name('tasks.approve');
    Route::post('/tasks/{task}/reject', [TaskActionController::class, 'rejectReview'])->name('tasks.reject');
    Route::post('/projects/{project}/milestones', [TaskActionController::class, 'createMilestone'])->name('projects.milestones');
});

// User dashboard - redirect based on role
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('member.dashboard');
    })->name('dashboard');
});

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

require __DIR__ . '/auth.php';

Route::any('/slim-api/{any?}', function () {
    require public_path('api_slim.php');
})->where('any', '.*');

if (app()->environment('local')) {
    Route::get('/clear-all', function () {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        return 'All cache cleared!';
    });
}

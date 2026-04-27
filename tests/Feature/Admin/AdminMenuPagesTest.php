<?php

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

it('admin can access all admin menu pages', function (string $routeName) {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route($routeName));

    $response->assertOk();
})->with([
    'admin.dashboard',
    'admin.projects.index',
    'admin.tasks.index',
    'admin.users.index',
    'admin.reports.index',
    'admin.notifications.index',
    'admin.settings.index',
]);

it('member cannot access admin menu pages', function (string $routeName) {
    $member = User::factory()->create(['role' => 'member']);

    $response = $this->actingAs($member)->get(route($routeName));

    $response
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('error_message');
})->with([
    'admin.dashboard',
    'admin.projects.index',
    'admin.tasks.index',
    'admin.users.index',
    'admin.reports.index',
    'admin.notifications.index',
    'admin.settings.index',
]);

it('admin can create and update project data', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.projects.store'), [
            'name' => 'Alpha Delivery Platform',
            'client_name' => 'Alpha Corp',
            'status' => 'active',
            'priority' => 'high',
            'progress' => 30,
            'due_date' => now()->addDays(10)->toDateString(),
        ])
        ->assertRedirect(route('admin.projects.index'));

    $project = Project::query()->where('name', 'Alpha Delivery Platform')->firstOrFail();

    $this->actingAs($admin)
        ->patch(route('admin.projects.update', $project), [
            'name' => $project->name,
            'client_name' => 'Alpha Enterprise',
            'status' => 'completed',
            'priority' => 'urgent',
            'progress' => 55,
            'due_date' => now()->addDays(7)->toDateString(),
        ])
        ->assertRedirect(route('admin.projects.index'));

    $project->refresh();

    expect($project->status)->toBe('completed')
        ->and($project->progress)->toBe(100)
        ->and($project->client_name)->toBe('Alpha Enterprise');
});

it('admin can create and complete task from tasks module', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $project = Project::query()->create([
        'name' => 'Task Suite',
        'client_name' => 'Task Co',
        'owner_id' => $admin->id,
        'progress' => 0,
        'status' => 'active',
        'priority' => 'medium',
        'due_date' => now()->addDays(5)->toDateString(),
    ]);

    $this->actingAs($admin)
        ->post(route('admin.tasks.store'), [
            'project_id' => $project->id,
            'title' => 'Setup kanban columns',
            'description' => 'Create board columns for sprint execution.',
            'status' => 'todo',
            'priority' => 'medium',
            'assigned_to' => $admin->id,
            'due_date' => now()->addDays(3)->toDateString(),
        ])
        ->assertRedirect(route('admin.tasks.index'));

    $task = Task::query()->where('title', 'Setup kanban columns')->firstOrFail();

    $this->actingAs($admin)
        ->patch(route('admin.tasks.complete', $task))
        ->assertRedirect(route('admin.tasks.index'));

    $task->refresh();

    expect($task->status)->toBe('done')
        ->and($task->completed_at)->not->toBeNull();
});

it('admin can mark notifications as read', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $activity = Activity::query()->create([
        'user_id' => $admin->id,
        'title' => 'Alert test',
        'description' => 'Pending alert',
        'category' => 'system',
        'is_read' => false,
        'link' => route('admin.dashboard'),
        'occurred_at' => now(),
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.notifications.read', $activity))
        ->assertRedirect(route('admin.notifications.index'));

    expect($activity->fresh()?->is_read)->toBeTrue();
});

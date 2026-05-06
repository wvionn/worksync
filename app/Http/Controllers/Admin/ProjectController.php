<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $search = (string) $request->string('search');

        $projects = Project::query()
            ->with('owner')
            ->withCount(['tasks', 'completedTasks'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('client_name', 'like', "%{$search}%");
                });
            })
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'search' => $search,
            'statusOptions' => ['planning', 'active', 'on_hold', 'completed'],
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    public function create(): View
    {
        $members = User::where('role', 'member')->orderBy('name')->get(['id', 'name']);
        
        return view('admin.projects.create', [
            'statusOptions' => ['planning', 'active', 'on_hold', 'completed'],
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
            'members' => $members,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'client_name' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['planning', 'active', 'on_hold', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'due_date' => ['nullable', 'date'],
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => ['exists:users,id'],
        ]);

        $validated['owner_id'] = $request->user()->id;

        $project = Project::create($validated);

        // Attach members to project
        if (!empty($validated['member_ids'])) {
            $project->members()->attach($validated['member_ids']);
        }

        Activity::create([
            'user_id' => $request->user()->id,
            'title' => 'Project created',
            'description' => "Project {$project->name} was created.",
            'category' => 'project',
            'is_read' => false,
            'link' => route('admin.projects.index'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success_message', 'Project berhasil dibuat.');
    }

    public function show(Project $project): View
    {
        $project->load(['owner', 'members', 'tasks' => function ($query) {
            $query->with('user')->orderBy('created_at', 'desc');
        }]);
        
        return view('admin.projects.show', [
            'project' => $project,
            'taskBreakdown' => $project->getTaskBreakdown(),
        ]);
    }

    public function edit(Project $project): View
    {
        $members = User::where('role', 'member')->orderBy('name')->get(['id', 'name']);
        $project->load('members');
        
        return view('admin.projects.edit', [
            'project' => $project,
            'statusOptions' => ['planning', 'active', 'on_hold', 'completed'],
            'priorityOptions' => ['low', 'medium', 'high', 'urgent'],
            'members' => $members,
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'client_name' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['planning', 'active', 'on_hold', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'due_date' => ['nullable', 'date'],
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => ['exists:users,id'],
        ]);

        $project->update($validated);

        // Sync members (add new, remove old)
        if (isset($validated['member_ids'])) {
            $project->members()->sync($validated['member_ids']);
        } else {
            $project->members()->detach();
        }

        Activity::create([
            'user_id' => $request->user()->id,
            'title' => 'Project updated',
            'description' => "Project {$project->name} was updated.",
            'category' => 'project',
            'is_read' => false,
            'link' => route('admin.projects.index'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success_message', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $projectName = $project->name;
        $project->delete();

        Activity::create([
            'user_id' => auth()->id(),
            'title' => 'Project deleted',
            'description' => "Project {$projectName} was deleted.",
            'category' => 'project',
            'is_read' => false,
            'link' => route('admin.projects.index'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success_message', 'Project berhasil dihapus.');
    }
}

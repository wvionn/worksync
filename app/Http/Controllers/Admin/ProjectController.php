<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'client_name' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['planning', 'active', 'on_hold', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'progress' => ['required', 'integer', 'between:0,100'],
            'due_date' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'completed') {
            $validated['progress'] = 100;
        }

        $validated['owner_id'] = $request->user()->id;

        $project = Project::create($validated);

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

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'client_name' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['planning', 'active', 'on_hold', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'progress' => ['required', 'integer', 'between:0,100'],
            'due_date' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'completed') {
            $validated['progress'] = 100;
        }

        $project->update($validated);

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
}

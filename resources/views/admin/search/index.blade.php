@extends('layouts.admin')

@section('title', 'Search')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Search</h1>
        <p class="text-gray-600 mt-1">
            @if($query)
                Results for "{{ $query }}"
            @else
                Search projects, tasks, and users from one place.
            @endif
        </p>
    </div>

    <form action="{{ route('admin.search.index') }}" method="GET" class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input
                type="search"
                name="q"
                value="{{ $query }}"
                placeholder="Type a project, task, user, or email..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <button type="submit" class="btn-primary">Search</button>
        </div>
    </form>

    @if($query === '')
        <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-700">
            Enter a keyword to start searching.
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <section class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Projects</h2>
                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">{{ $projects->count() }}</span>
                </div>
                <div class="space-y-3">
                    @forelse($projects as $project)
                        <a href="{{ route('admin.projects.show', $project) }}" class="block rounded-lg border border-gray-100 p-3 transition hover:border-blue-200 hover:bg-blue-50">
                            <p class="font-semibold text-gray-900">{{ $project->name }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $project->client_name ?: 'No client' }}</p>
                            <p class="mt-2 text-xs font-semibold text-blue-700">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</p>
                        </a>
                    @empty
                        <p class="py-6 text-center text-sm italic text-gray-400">No projects found.</p>
                    @endforelse
                </div>
            </section>

            <section class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Tasks</h2>
                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">{{ $tasks->count() }}</span>
                </div>
                <div class="space-y-3">
                    @forelse($tasks as $task)
                        <a href="{{ route('admin.tasks.show', $task) }}" class="block rounded-lg border border-gray-100 p-3 transition hover:border-blue-200 hover:bg-blue-50">
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-semibold text-gray-900">{{ $task->title }}</p>
                                <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-semibold
                                    {{ $task->status === 'done' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $task->status === 'doing' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $task->status === 'todo' ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $task->status === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $task->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ $task->project->name ?? 'No project' }}</p>
                            @if($task->user)
                                <p class="mt-2 text-xs text-gray-500">Assigned to {{ $task->user->name }}</p>
                            @endif
                        </a>
                    @empty
                        <p class="py-6 text-center text-sm italic text-gray-400">No tasks found.</p>
                    @endforelse
                </div>
            </section>

            <section class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Users</h2>
                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">{{ $users->count() }}</span>
                </div>
                <div class="space-y-3">
                    @forelse($users as $user)
                        <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center gap-3 rounded-lg border border-gray-100 p-3 transition hover:border-blue-200 hover:bg-blue-50">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="truncate text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="py-6 text-center text-sm italic text-gray-400">No users found.</p>
                    @endforelse
                </div>
            </section>
        </div>
    @endif
</div>
@endsection

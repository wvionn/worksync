@extends('admin.layouts.app')

@section('page_title', 'Tasks')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.45fr]">
        <section class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
            <h1 class="font-['Manrope'] text-2xl font-extrabold text-slate-900">Create Task</h1>
            <p class="mt-1 text-sm text-slate-500">Tambahkan tugas baru ke board tim.</p>

            <form method="POST" action="{{ route('admin.tasks.store') }}" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Task Title</label>
                    <input id="title" name="title" type="text" required class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400" value="{{ old('title') }}">
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="project_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Project</label>
                    <select id="project_id" name="project_id" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                        <option value="">Without project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="status" class="mb-1.5 block text-sm font-semibold text-slate-700">Status</label>
                        <select id="status" name="status" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}" @selected(old('status', 'todo') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="mb-1.5 block text-sm font-semibold text-slate-700">Priority</label>
                        <select id="priority" name="priority" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                            @foreach ($priorityOptions as $priority)
                                <option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ ucfirst($priority) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="assigned_to" class="mb-1.5 block text-sm font-semibold text-slate-700">Assignee</label>
                        <select id="assigned_to" name="assigned_to" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                            <option value="">Unassigned</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="due_date" class="mb-1.5 block text-sm font-semibold text-slate-700">Due Date</label>
                        <input id="due_date" name="due_date" type="date" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400" value="{{ old('due_date') }}">
                    </div>
                </div>

                <button type="submit" class="w-full rounded-xl bg-blue-700 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-300/60 transition hover:bg-blue-800">
                    Save Task
                </button>
            </form>
        </section>

        <section class="space-y-4 rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-['Manrope'] text-2xl font-extrabold text-slate-900">Task Board</h2>
                    <p class="text-sm text-slate-500">Kelola status tugas per item.</p>
                </div>

                <form method="GET" action="{{ route('admin.tasks.index') }}" class="flex w-full flex-col gap-2 sm:max-w-sm sm:flex-row">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search tasks"
                        value="{{ $search }}"
                        class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400"
                    >
                    <select name="status" class="rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                        <option value="">All</option>
                        @foreach ($statusOptions as $status)
                            <option value="{{ $status }}" @selected($statusFilter === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-700 hover:bg-slate-100">Filter</button>
                </form>
            </div>

            <div class="space-y-3">
                @forelse ($tasks as $task)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                            <form method="POST" action="{{ route('admin.tasks.update', $task) }}" class="grid flex-1 gap-3 sm:grid-cols-2">
                                @csrf
                                @method('PATCH')

                                <div class="sm:col-span-2">
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Title</label>
                                    <input name="title" type="text" required value="{{ $task->title }}" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Description</label>
                                    <textarea name="description" rows="2" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">{{ $task->description }}</textarea>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Project</label>
                                    <select name="project_id" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                                        <option value="">Without project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @selected($task->project_id === $project->id)>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Assignee</label>
                                    <select name="assigned_to" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                                        <option value="">Unassigned</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected($task->assigned_to === $user->id)>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Status</label>
                                    <select name="status" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                                        @foreach ($statusOptions as $status)
                                            <option value="{{ $status }}" @selected($task->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Priority</label>
                                    <select name="priority" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                                        @foreach ($priorityOptions as $priority)
                                            <option value="{{ $priority }}" @selected($task->priority === $priority)>{{ ucfirst($priority) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Due Date</label>
                                    <input name="due_date" type="date" value="{{ optional($task->due_date)->format('Y-m-d') }}" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                                </div>

                                <div class="sm:col-span-2">
                                    <button type="submit" class="rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-800">
                                        Update Task
                                    </button>
                                </div>
                            </form>

                            <div class="lg:ml-4 lg:w-32">
                                <form method="POST" action="{{ route('admin.tasks.complete', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2.5 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100">
                                        Mark Done
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-500">
                            <span class="rounded-full bg-slate-200 px-2.5 py-1">Project: {{ $task->project?->name ?? 'General' }}</span>
                            <span class="rounded-full bg-slate-200 px-2.5 py-1">Assignee: {{ $task->assignee?->name ?? 'Unassigned' }}</span>
                            <span class="rounded-full bg-slate-200 px-2.5 py-1">Updated {{ $task->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                        Tidak ada task ditemukan.
                    </p>
                @endforelse
            </div>

            <div>
                {{ $tasks->links() }}
            </div>
        </section>
    </div>
@endsection

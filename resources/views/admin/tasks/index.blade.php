@extends('layouts.admin')

@section('title', 'Tasks')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
            <p class="text-gray-600 mt-1">Manage and track your project tasks</p>
        </div>
        <a href="{{ route('admin.tasks.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Task
        </a>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- To Do Column -->
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200/50">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-md font-bold text-gray-800">To Do</h2>
                <span class="px-2 py-0.5 bg-gray-200 text-gray-700 text-xs font-bold rounded-full">
                    {{ $todoTasks->count() ?? 0 }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($todoTasks ?? [] as $task)
                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition duration-200">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-1.5 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                        @if($task->is_blocked)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-red-600 text-white rounded">Blocked</span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h3 class="font-semibold text-gray-900 mb-1 hover:text-blue-600 transition-colors text-sm">
                        <a href="{{ route('admin.tasks.show', $task) }}">{{ $task->title }}</a>
                    </h3>

                    <!-- Project -->
                    <p class="text-[11px] text-gray-500 font-semibold mb-2">{{ $task->project->name ?? 'No Project' }}</p>

                    @if($task->description)
                    <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif

                    <!-- Labels & Milestones -->
                    @if($task->milestone)
                    <div class="text-[10px] font-bold text-teal-700 bg-teal-50 border border-teal-100 px-1.5 py-0.5 rounded inline-block mb-2">
                        Milestone: {{ $task->milestone->title }}
                    </div>
                    @endif
                    @if($task->labels->count() > 0)
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach($task->labels as $label)
                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded text-white" style="background-color: {{ $label->color }}">
                            {{ $label->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100">
                        @if($task->user)
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-gray-400 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                                {{ substr($task->user->name, 0, 1) }}
                            </div>
                            <span class="text-gray-600 text-[11px] font-medium">{{ $task->user->name }}</span>
                        </div>
                        @else
                        <span class="text-gray-400 text-[11px] italic">Unassigned</span>
                        @endif
                        @if($task->due_date)
                        <span class="text-gray-500 text-[11px]">{{ $task->formatted_due_date_short }}</span>
                        @endif
                    </div>

                    <!-- Status Selector Form -->
                    <div class="mt-3">
                        <form method="POST" action="{{ route('admin.tasks.updateStatus', $task) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer">
                                <option value="todo" selected>To Do</option>
                                <option value="doing">Doing</option>
                                <option value="in_review">In Review</option>
                                <option value="done">Done</option>
                            </select>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">No tasks</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Doing Column -->
        <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-md font-bold text-blue-900">Doing</h2>
                <span class="px-2 py-0.5 bg-blue-200 text-blue-800 text-xs font-bold rounded-full">
                    {{ $doingTasks->count() ?? 0 }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($doingTasks ?? [] as $task)
                <div class="bg-white rounded-lg p-4 border border-blue-200 hover:shadow-md transition duration-200">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-1.5 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                        @if($task->is_blocked)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-red-600 text-white rounded">Blocked</span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h3 class="font-semibold text-gray-900 mb-1 hover:text-blue-600 transition-colors text-sm">
                        <a href="{{ route('admin.tasks.show', $task) }}">{{ $task->title }}</a>
                    </h3>

                    <!-- Project -->
                    <p class="text-[11px] text-gray-500 font-semibold mb-2">{{ $task->project->name ?? 'No Project' }}</p>

                    @if($task->description)
                    <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif

                    <!-- Labels & Milestones -->
                    @if($task->milestone)
                    <div class="text-[10px] font-bold text-teal-700 bg-teal-50 border border-teal-100 px-1.5 py-0.5 rounded inline-block mb-2">
                        Milestone: {{ $task->milestone->title }}
                    </div>
                    @endif
                    @if($task->labels->count() > 0)
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach($task->labels as $label)
                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded text-white" style="background-color: {{ $label->color }}">
                            {{ $label->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100">
                        @if($task->user)
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                                {{ substr($task->user->name, 0, 1) }}
                            </div>
                            <span class="text-gray-600 text-[11px] font-medium">{{ $task->user->name }}</span>
                        </div>
                        @else
                        <span class="text-gray-400 text-[11px] italic">Unassigned</span>
                        @endif
                        @if($task->due_date)
                        <span class="text-gray-500 text-[11px]">{{ $task->formatted_due_date_short }}</span>
                        @endif
                    </div>

                    <!-- Status Selector Form -->
                    <div class="mt-3">
                        <form method="POST" action="{{ route('admin.tasks.updateStatus', $task) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="w-full px-2 py-1 text-xs border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer">
                                <option value="todo">To Do</option>
                                <option value="doing" selected>Doing</option>
                                <option value="in_review">In Review</option>
                                <option value="done">Done</option>
                            </select>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">No tasks</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Done Column (Combining Done & In Review visually or using column) -->
        <!-- Let's show Done tasks here -->
        <div class="bg-green-50/50 rounded-xl p-4 border border-green-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-md font-bold text-green-900">Done</h2>
                <span class="px-2 py-0.5 bg-green-200 text-green-800 text-xs font-bold rounded-full">
                    {{ $doneTasks->count() ?? 0 }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($doneTasks ?? [] as $task)
                <div class="bg-white rounded-lg p-4 border border-green-200 hover:shadow-md transition duration-200 opacity-80">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-1.5 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h3 class="font-semibold text-gray-900 mb-1 hover:text-blue-600 transition-colors text-sm line-through">
                        <a href="{{ route('admin.tasks.show', $task) }}">{{ $task->title }}</a>
                    </h3>

                    <!-- Project -->
                    <p class="text-[11px] text-gray-500 font-semibold mb-2">{{ $task->project->name ?? 'No Project' }}</p>

                    @if($task->description)
                    <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif

                    <!-- Labels & Milestones -->
                    @if($task->milestone)
                    <div class="text-[10px] font-bold text-teal-700 bg-teal-50 border border-teal-100 px-1.5 py-0.5 rounded inline-block mb-2">
                        Milestone: {{ $task->milestone->title }}
                    </div>
                    @endif
                    @if($task->labels->count() > 0)
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach($task->labels as $label)
                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded text-white" style="background-color: {{ $label->color }}">
                            {{ $label->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100">
                        @if($task->user)
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                                {{ substr($task->user->name, 0, 1) }}
                            </div>
                            <span class="text-gray-600 text-[11px] font-medium">{{ $task->user->name }}</span>
                        </div>
                        @else
                        <span class="text-gray-400 text-[11px] italic">Unassigned</span>
                        @endif
                        @if($task->completed_at)
                        <span class="text-green-600 text-[10px] font-semibold">{{ $task->completed_at->format('M d') }}</span>
                        @endif
                    </div>

                    <!-- Status Selector Form -->
                    <div class="mt-3">
                        <form method="POST" action="{{ route('admin.tasks.updateStatus', $task) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="w-full px-2 py-1 text-xs border border-green-200 rounded-lg focus:ring-2 focus:ring-green-500 bg-white cursor-pointer">
                                <option value="todo">To Do</option>
                                <option value="doing">Doing</option>
                                <option value="in_review">In Review</option>
                                <option value="done" selected>Done</option>
                            </select>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">No tasks completed</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Overdue Column -->
        <div class="bg-red-50/50 rounded-xl p-4 border border-red-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-md font-bold text-red-900">Overdue</h2>
                <span class="px-2 py-0.5 bg-red-200 text-red-800 text-xs font-bold rounded-full">
                    {{ $overdueTasks->count() ?? 0 }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($overdueTasks ?? [] as $task)
                <div class="bg-white rounded-lg p-4 border-2 border-red-200 hover:shadow-md transition duration-200">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-1.5 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                        @if($task->is_blocked)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-red-600 text-white rounded">Blocked</span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h3 class="font-semibold text-gray-900 mb-1 hover:text-blue-600 transition-colors text-sm">
                        <a href="{{ route('admin.tasks.show', $task) }}">{{ $task->title }}</a>
                    </h3>

                    <!-- Project -->
                    <p class="text-[11px] text-gray-500 font-semibold mb-2">{{ $task->project->name ?? 'No Project' }}</p>

                    @if($task->description)
                    <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif

                    <!-- Labels & Milestones -->
                    @if($task->milestone)
                    <div class="text-[10px] font-bold text-teal-700 bg-teal-50 border border-teal-100 px-1.5 py-0.5 rounded inline-block mb-2">
                        Milestone: {{ $task->milestone->title }}
                    </div>
                    @endif
                    @if($task->labels->count() > 0)
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach($task->labels as $label)
                        <span class="px-1.5 py-0.5 text-[9px] font-bold rounded text-white" style="background-color: {{ $label->color }}">
                            {{ $label->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100">
                        @if($task->user)
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                                {{ substr($task->user->name, 0, 1) }}
                            </div>
                            <span class="text-gray-600 text-[11px] font-medium">{{ $task->user->name }}</span>
                        </div>
                        @else
                        <span class="text-gray-400 text-[11px] italic">Unassigned</span>
                        @endif
                        @if($task->due_date)
                        <div class="flex items-center gap-1 text-[11px] font-semibold text-red-600">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $task->formatted_due_date_short }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Status Selector Form -->
                    <div class="mt-3">
                        <form method="POST" action="{{ route('admin.tasks.updateStatus', $task) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="w-full px-2 py-1 text-xs border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 bg-white cursor-pointer text-red-700">
                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="doing" {{ $task->status === 'doing' ? 'selected' : '' }}>Doing</option>
                                <option value="in_review" {{ $task->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                                <option value="overdue" selected>Overdue</option>
                            </select>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">No overdue tasks</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

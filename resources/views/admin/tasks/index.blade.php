@extends('layouts.admin')

@section('title', 'Tasks')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
            <p class="text-gray-600 mt-1">Manage all your tasks</p>
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
        <div class="bg-gray-50 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">To Do</h2>
                <span class="px-2 py-1 bg-gray-200 text-gray-700 text-sm font-medium rounded-full">
                    {{ $todoTasks->count() ?? 0 }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($todoTasks ?? [] as $task)
                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900 flex-1">{{ $task->title }}</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif
                    <div class="flex items-center justify-between">
                        @if($task->user)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center text-white text-xs">
                                {{ substr($task->user->name, 0, 1) }}
                            </div>
                            <span class="text-xs text-gray-600">{{ $task->user->name }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-400">Unassigned</span>
                        @endif
                        @if($task->due_date)
                        <span class="text-xs text-gray-500">{{ $task->due_date->format('M d') }}</span>
                        @endif
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
        <div class="bg-blue-50 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Doing</h2>
                <span class="px-2 py-1 bg-blue-200 text-blue-700 text-sm font-medium rounded-full">
                    {{ $doingTasks->count() ?? 0 }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($doingTasks ?? [] as $task)
                <div class="bg-white rounded-lg p-4 border border-blue-200 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900 flex-1">{{ $task->title }}</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif
                    <div class="flex items-center justify-between">
                        @if($task->user)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs">
                                {{ substr($task->user->name, 0, 1) }}
                            </div>
                            <span class="text-xs text-gray-600">{{ $task->user->name }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-400">Unassigned</span>
                        @endif
                        @if($task->due_date)
                        <span class="text-xs text-gray-500">{{ $task->due_date->format('M d') }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">No tasks</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Done Column -->
        <div class="bg-green-50 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Done</h2>
                <span class="px-2 py-1 bg-green-200 text-green-700 text-sm font-medium rounded-full">
                    {{ $doneTasks->count() ?? 0 }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($doneTasks ?? [] as $task)
                <div class="bg-white rounded-lg p-4 border border-green-200 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900 flex-1 line-through">{{ $task->title }}</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif
                    <div class="flex items-center justify-between">
                        @if($task->user)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs">
                                {{ substr($task->user->name, 0, 1) }}
                            </div>
                            <span class="text-xs text-gray-600">{{ $task->user->name }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-400">Unassigned</span>
                        @endif
                        @if($task->due_date)
                        <span class="text-xs text-gray-500">{{ $task->due_date->format('M d') }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">No tasks</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Overdue Column -->
        <div class="bg-red-50 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Overdue</h2>
                <span class="px-2 py-1 bg-red-200 text-red-700 text-sm font-medium rounded-full">
                    {{ $overdueTasks->count() ?? 0 }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($overdueTasks ?? [] as $task)
                <div class="bg-white rounded-lg p-4 border-2 border-red-300 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900 flex-1">{{ $task->title }}</h3>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                    @endif
                    <div class="flex items-center justify-between">
                        @if($task->user)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs">
                                {{ substr($task->user->name, 0, 1) }}
                            </div>
                            <span class="text-xs text-gray-600">{{ $task->user->name }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-400">Unassigned</span>
                        @endif
                        @if($task->due_date)
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs text-red-600 font-medium">{{ $task->due_date->format('M d') }}</span>
                        </div>
                        @endif
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

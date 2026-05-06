@extends('layouts.member')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Tasks</h1>
        <p class="text-gray-600 mt-1">Manage your assigned tasks</p>
    </div>

    <!-- Task Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Total Tasks</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $todoTasks->count() + $doingTasks->count() + $doneTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">To Do</h3>
            <p class="text-3xl font-bold text-gray-700">{{ $todoTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">In Progress</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $doingTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Completed</h3>
            <p class="text-3xl font-bold text-green-600">{{ $doneTasks->count() }}</p>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- To Do Column -->
        <div class="bg-gray-50 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">To Do</h3>
                <span class="px-2 py-1 bg-gray-200 text-gray-700 text-sm font-medium rounded-full">
                    {{ $todoTasks->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($todoTasks as $task)
                <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-semibold text-gray-900">{{ $task->title }}</h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 80) }}</p>
                    @endif
                    @if($task->project)
                    <p class="text-xs text-gray-500 mb-3">
                        <span class="font-medium">Project:</span> {{ $task->project->name }}
                    </p>
                    @endif
                    <div class="flex items-center justify-between">
                        @if($task->due_date)
                        <span class="text-sm text-gray-500">{{ $task->due_date->format('M d, Y') }}</span>
                        @else
                        <span class="text-sm text-gray-400">No due date</span>
                        @endif
                        <button onclick="updateTaskStatus({{ $task->id }}, 'doing')" 
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Start →
                        </button>
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
                <h3 class="text-lg font-bold text-gray-900">Doing</h3>
                <span class="px-2 py-1 bg-blue-200 text-blue-700 text-sm font-medium rounded-full">
                    {{ $doingTasks->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($doingTasks as $task)
                <div class="bg-white rounded-lg p-4 border border-blue-200 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-semibold text-gray-900">{{ $task->title }}</h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 80) }}</p>
                    @endif
                    @if($task->project)
                    <p class="text-xs text-gray-500 mb-3">
                        <span class="font-medium">Project:</span> {{ $task->project->name }}
                    </p>
                    @endif
                    <div class="flex items-center justify-between">
                        @if($task->due_date)
                        <span class="text-sm text-gray-500">{{ $task->due_date->format('M d, Y') }}</span>
                        @else
                        <span class="text-sm text-gray-400">No due date</span>
                        @endif
                        <div class="flex gap-2">
                            <button onclick="updateTaskStatus({{ $task->id }}, 'todo')" 
                                class="text-sm text-gray-600 hover:text-gray-700 font-medium">
                                ← Back
                            </button>
                            <button onclick="updateTaskStatus({{ $task->id }}, 'done')" 
                                class="text-sm text-green-600 hover:text-green-700 font-medium">
                                Done ✓
                            </button>
                        </div>
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
                <h3 class="text-lg font-bold text-gray-900">Done</h3>
                <span class="px-2 py-1 bg-green-200 text-green-700 text-sm font-medium rounded-full">
                    {{ $doneTasks->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @forelse($doneTasks as $task)
                <div class="bg-white rounded-lg p-4 border border-green-200 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-semibold text-gray-900 line-through">{{ $task->title }}</h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 80) }}</p>
                    @endif
                    @if($task->project)
                    <p class="text-xs text-gray-500 mb-3">
                        <span class="font-medium">Project:</span> {{ $task->project->name }}
                    </p>
                    @endif
                    <div class="flex items-center justify-between">
                        @if($task->due_date)
                        <span class="text-sm text-gray-500">{{ $task->due_date->format('M d, Y') }}</span>
                        @else
                        <span class="text-sm text-gray-400">No due date</span>
                        @endif
                        <button onclick="updateTaskStatus({{ $task->id }}, 'doing')" 
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            ← Reopen
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">No tasks</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6">
        <div class="flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-900 font-medium">Updating task...</span>
        </div>
    </div>
</div>

<script>
function updateTaskStatus(taskId, newStatus) {
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Send AJAX request
    fetch(`/member/tasks/${taskId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to show updated tasks
            window.location.reload();
        } else {
            alert('Failed to update task status');
            document.getElementById('loadingOverlay').classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating task status');
        document.getElementById('loadingOverlay').classList.add('hidden');
    });
}
</script>
@endsection

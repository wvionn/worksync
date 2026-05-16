@extends('layouts.member')

@section('header_title', 'My Tasks Overview')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
            <p class="text-gray-500 mt-1 text-sm">Here's what you need to do today.</p>
        </div>
        <div>
            <a href="{{ route('member.deadlines') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                View Deadlines
            </a>
        </div>
    </div>

    <!-- Task Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500">Total Tasks</h3>
                <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $todoTasks->count() + $doingTasks->count() + $doneTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500">To Do</h3>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-700">{{ $todoTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500">In Progress</h3>
                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ $doingTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500">Completed</h3>
                <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-green-600">{{ $doneTasks->count() }}</p>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
        <!-- To Do Column -->
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-gray-400"></div>
                    <h3 class="text-base font-semibold text-gray-700">To Do</h3>
                </div>
                <span class="px-2.5 py-0.5 bg-gray-200 text-gray-600 text-xs font-bold rounded-full">
                    {{ $todoTasks->count() }}
                </span>
            </div>
            <div class="flex-1 bg-gray-100/50 rounded-2xl p-4 space-y-4 border border-gray-100">
                @forelse($todoTasks as $task)
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                    <div class="flex items-start justify-between mb-3">
                        <h4 class="font-semibold text-gray-800 leading-tight">{{ $task->title }}</h4>
                        <span class="px-2.5 py-1 text-[10px] font-bold tracking-wide rounded-full uppercase
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-600' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-600' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-600' : '' }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
                    @endif
                    @if($task->project)
                    <div class="flex items-center gap-1.5 mb-4">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        <span class="text-xs font-medium text-gray-600">{{ $task->project->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        @if($task->due_date)
                        <div class="flex items-center text-xs text-gray-500 font-medium">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $task->due_date->format('M d') }}
                        </div>
                        @else
                        <span class="text-xs text-gray-400">No date</span>
                        @endif
                        <button onclick="updateTaskStatus({{ $task->id }}, 'doing')" 
                            class="opacity-0 group-hover:opacity-100 transition-opacity text-xs bg-teal-50 text-teal-600 hover:bg-teal-100 px-3 py-1.5 rounded-lg font-medium flex items-center">
                            Start <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="h-32 flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-sm font-medium">No tasks to do</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Doing Column -->
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                    <h3 class="text-base font-semibold text-gray-700">In Progress</h3>
                </div>
                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                    {{ $doingTasks->count() }}
                </span>
            </div>
            <div class="flex-1 bg-blue-50/50 rounded-2xl p-4 space-y-4 border border-blue-100/50">
                @forelse($doingTasks as $task)
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-blue-100 hover:shadow-md transition-all group">
                    <div class="flex items-start justify-between mb-3">
                        <h4 class="font-semibold text-gray-800 leading-tight">{{ $task->title }}</h4>
                        <span class="px-2.5 py-1 text-[10px] font-bold tracking-wide rounded-full uppercase
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-600' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-600' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-600' : '' }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
                    @endif
                    @if($task->project)
                    <div class="flex items-center gap-1.5 mb-4">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        <span class="text-xs font-medium text-gray-600">{{ $task->project->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        @if($task->due_date)
                        <div class="flex items-center text-xs text-gray-500 font-medium">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $task->due_date->format('M d') }}
                        </div>
                        @else
                        <span class="text-xs text-gray-400">No date</span>
                        @endif
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="updateTaskStatus({{ $task->id }}, 'todo')" 
                                class="text-xs bg-gray-100 text-gray-600 hover:bg-gray-200 px-2 py-1.5 rounded-lg font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            </button>
                            <button onclick="updateTaskStatus({{ $task->id }}, 'done')" 
                                class="text-xs bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1.5 rounded-lg font-medium flex items-center">
                                Done <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="h-32 flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <p class="text-sm font-medium">No tasks in progress</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Done Column -->
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                    <h3 class="text-base font-semibold text-gray-700">Done</h3>
                </div>
                <span class="px-2.5 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                    {{ $doneTasks->count() }}
                </span>
            </div>
            <div class="flex-1 bg-green-50/50 rounded-2xl p-4 space-y-4 border border-green-100/50">
                @forelse($doneTasks as $task)
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-100 hover:shadow-md transition-all group opacity-80 hover:opacity-100">
                    <div class="flex items-start justify-between mb-3">
                        <h4 class="font-semibold text-gray-800 leading-tight line-through decoration-gray-300">{{ $task->title }}</h4>
                    </div>
                    @if($task->project)
                    <div class="flex items-center gap-1.5 mb-4">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        <span class="text-xs font-medium text-gray-500">{{ $task->project->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        <div class="flex items-center text-xs text-green-600 font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Completed
                        </div>
                        <button onclick="updateTaskStatus({{ $task->id }}, 'doing')" 
                            class="opacity-0 group-hover:opacity-100 transition-opacity text-xs bg-gray-50 text-gray-600 hover:bg-gray-100 px-3 py-1.5 rounded-lg font-medium flex items-center" title="Reopen task">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reopen
                        </button>
                    </div>
                </div>
                @empty
                <div class="h-32 flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm font-medium">No completed tasks yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-white/60 backdrop-blur-sm z-50 flex items-center justify-center transition-all">
    <div class="bg-white rounded-2xl p-6 shadow-xl flex items-center gap-4 border border-teal-100">
        <svg class="animate-spin h-6 w-6 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-800 font-medium tracking-wide">Updating...</span>
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

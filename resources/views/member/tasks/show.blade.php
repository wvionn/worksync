@extends('layouts.member')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('member.dashboard') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
            <p class="text-gray-600 mt-1">Task Details</p>
        </div>
    </div>

    <!-- Task Details Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="space-y-6">
            <!-- Status and Priority -->
            <div class="flex items-center gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Status</label>
                    <div class="mt-1">
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            {{ $task->status === 'todo' ? 'bg-gray-100 text-gray-700' : '' }}
                            {{ $task->status === 'doing' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $task->status === 'done' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($task->status) }}
                        </span>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Priority</label>
                    <div class="mt-1">
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($task->description)
            <div>
                <label class="text-sm font-medium text-gray-600">Description</label>
                <p class="text-gray-900 mt-1">{{ $task->description }}</p>
            </div>
            @endif

            <!-- Project -->
            @if($task->project)
            <div>
                <label class="text-sm font-medium text-gray-600">Project</label>
                <p class="text-gray-900 mt-1">{{ $task->project->name }}</p>
            </div>
            @endif

            <!-- Due Date -->
            @if($task->due_date)
            <div>
                <label class="text-sm font-medium text-gray-600">Due Date</label>
                <p class="text-gray-900 mt-1">{{ $task->due_date->format('F d, Y') }}</p>
            </div>
            @endif

            <!-- Change Status Actions -->
            <div class="pt-4 border-t border-gray-200">
                <label class="text-sm font-medium text-gray-600 mb-3 block">Change Status</label>
                <div class="flex gap-3">
                    @if($task->status !== 'todo')
                    <button onclick="updateTaskStatus({{ $task->id }}, 'todo')" class="btn-secondary">
                        Move to To Do
                    </button>
                    @endif
                    
                    @if($task->status !== 'doing')
                    <button onclick="updateTaskStatus({{ $task->id }}, 'doing')" class="btn-primary">
                        @if($task->status === 'todo')
                            Start Working
                        @else
                            Reopen Task
                        @endif
                    </button>
                    @endif
                    
                    @if($task->status !== 'done')
                    <button onclick="updateTaskStatus({{ $task->id }}, 'done')" class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        Mark as Done
                    </button>
                    @endif
                </div>
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
            // Redirect back to dashboard
            window.location.href = '{{ route("member.dashboard") }}';
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

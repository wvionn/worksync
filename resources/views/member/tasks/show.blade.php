@extends('layouts.member')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Back & Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('member.dashboard') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
            <p class="text-sm text-gray-600 mt-1">
                Project: <strong>{{ $task->project->name ?? 'No Project' }}</strong>
            </p>
        </div>
    </div>

    <!-- Blocker Warning Banner -->
    @if($task->is_blocked)
        <div class="bg-red-50 border-2 border-red-300 rounded-xl p-5 flex items-start gap-4 shadow-sm animate-pulse">
            <div class="p-3 bg-red-100 rounded-xl text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-red-800 text-lg">Task is Blocked / Has Issue</h3>
                <p class="text-red-700 mt-1">{{ $task->blocker_description ?: 'No blocker description provided.' }}</p>
                <form method="POST" action="{{ route('member.tasks.toggle-blocker', $task) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm shadow transition-colors">
                        Resolve Issue / Unblock Task
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- Blocker Toggle Form -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm">
            <span class="text-sm font-medium text-gray-700">Flag this task if you are stuck or hit a blocker/issue:</span>
            <button onclick="toggleBlockerForm()" class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg text-xs shadow transition-all">
                Flag Blocker
            </button>
        </div>

        <div id="blockerFormContainer" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-5 space-y-3">
            <h4 class="font-bold text-yellow-800">Flag Blocker / Issue</h4>
            <form method="POST" action="{{ route('member.tasks.toggle-blocker', $task) }}" class="space-y-3">
                @csrf
                <textarea name="blocker_description" placeholder="Describe what is blocking you in detail..." required rows="2"
                    class="w-full px-4 py-2 border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white text-gray-900"></textarea>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg text-sm shadow">
                        Flag as Blocked
                    </button>
                    <button type="button" onclick="toggleBlockerForm()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Columns -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-3">Task Description</h2>
                <div class="text-gray-700 whitespace-pre-wrap leading-relaxed text-sm">
                    {!! $task->description ?: '<span class="text-gray-400 italic">No description provided.</span>' !!}
                </div>

                <!-- Labels -->
                @if($task->labels->count() > 0)
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Labels</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($task->labels as $label)
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full text-white" style="background-color: {{ $label->color }}">
                            {{ $label->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Subtasks Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Subtasks Checklist</h2>
                    @php
                        $subtasksCount = $task->subtasks->count();
                        $completedSubtasks = $task->subtasks->where('is_completed', true)->count();
                        $progress = $subtasksCount > 0 ? (int) round(($completedSubtasks / $subtasksCount) * 100) : 0;
                    @endphp
                    <span class="text-sm font-semibold text-gray-500">{{ $completedSubtasks }}/{{ $subtasksCount }} Completed</span>
                </div>

                @if($subtasksCount > 0)
                <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                    <div class="bg-teal-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                </div>
                @endif

                <div class="space-y-3">
                    @forelse($task->subtasks as $subtask)
                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg border border-gray-100 group">
                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('member.subtasks.toggle', $subtask) }}">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" {{ $subtask->is_completed ? 'checked' : '' }}
                                    class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 cursor-pointer">
                            </form>
                            <span class="text-sm font-medium {{ $subtask->is_completed ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                {{ $subtask->title }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('member.subtasks.destroy', $subtask) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 italic">No subtasks checklist created.</p>
                    @endforelse

                    <!-- Add Form -->
                    <form method="POST" action="{{ route('member.tasks.subtasks', $task) }}" class="flex items-center gap-2 pt-2">
                        @csrf
                        <input type="text" name="title" required placeholder="Add new checklist item..."
                            class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                        <button type="submit" class="px-4 py-1.5 bg-teal-700 hover:bg-teal-800 text-white font-semibold rounded-lg text-sm shadow transition-colors">
                            Add
                        </button>
                    </form>
                </div>
            </div>

            <!-- Attachments Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Attachments</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    @forelse($task->attachments as $attachment)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between group">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="p-2 bg-teal-100 rounded-lg text-teal-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate" title="{{ $attachment->file_name }}">
                                    {{ $attachment->file_name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $attachment->formatted_size }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" download class="p-1 text-gray-500 hover:text-teal-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('member.attachments.destroy', $attachment) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-gray-500 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="sm:col-span-2 py-4 text-center text-gray-400 italic">
                        No attachments uploaded.
                    </div>
                    @endforelse
                </div>

                <!-- Upload Form -->
                <form method="POST" action="{{ route('member.tasks.attachments', $task) }}" enctype="multipart/form-data" class="bg-gray-50 rounded-xl p-4 border border-dashed border-gray-300 flex items-center justify-between gap-4">
                    @csrf
                    <input type="file" name="file" required class="text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-800 file:hover:bg-teal-100 cursor-pointer">
                    <button type="submit" class="px-4 py-1.5 bg-teal-700 hover:bg-teal-800 text-white font-semibold rounded-lg text-sm shadow transition-colors">
                        Upload
                    </button>
                </form>
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-gray-900">Discussion & Comments</h2>
                
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    @forelse($task->comments as $comment)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-8 h-8 rounded-full bg-teal-700 text-white font-bold flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                            {{ substr($comment->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-900">{{ $comment->user->name }}</h4>
                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-700 mt-1 whitespace-pre-wrap leading-relaxed">{{ $comment->content }}</p>
                        </div>
                        @if($comment->user_id === Auth::id())
                        <form method="POST" action="{{ route('member.comments.destroy', $comment) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 italic text-center py-4">No comments posted yet. Start the discussion!</p>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('member.tasks.comments', $task) }}" class="flex gap-3">
                    @csrf
                    <textarea name="content" required placeholder="Write a comment..." rows="2"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white text-gray-900"></textarea>
                    <button type="submit" class="px-5 py-2 bg-teal-700 hover:bg-teal-800 text-white font-semibold rounded-lg text-sm shadow transition-colors flex-shrink-0 self-end">
                        Post
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side Properties -->
        <div class="space-y-6">
            <!-- Properties Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-6">
                <h3 class="text-md font-bold text-gray-900 border-b border-gray-100 pb-3">Properties</h3>
                
                <!-- Status -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full border
                            {{ $task->status === 'done' ? 'bg-green-100 text-green-700 border-green-200' : '' }}
                            {{ $task->status === 'doing' ? 'bg-blue-100 text-blue-700 border-blue-200' : '' }}
                            {{ $task->status === 'todo' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                            {{ $task->status === 'in_review' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : '' }}
                            {{ $task->status === 'overdue' ? 'bg-red-100 text-red-700 border-red-200' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                </div>

                <!-- Priority -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full border
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700 border-red-200' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700 border-orange-200' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700 border-green-200' : '' }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                </div>

                <!-- Due Date -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Due Date</label>
                    <p class="mt-1 text-sm font-medium text-gray-900">
                        @if($task->due_date)
                            {{ $task->formatted_due_date }}
                            <span class="text-xs text-gray-500">({{ $task->due_date->diffForHumans() }})</span>
                        @else
                            <span class="text-gray-400 italic">No deadline set</span>
                        @endif
                    </p>
                </div>

                <!-- Milestone -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Milestone</label>
                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        @if($task->milestone)
                            <span class="px-2.5 py-0.5 rounded-md bg-teal-50 text-teal-800 border border-teal-200 text-xs font-bold">
                                {{ $task->milestone->title }}
                            </span>
                        @else
                            <span class="text-gray-400 italic text-xs">None</span>
                        @endif
                    </p>
                </div>

                <!-- Status Transition Buttons -->
                <div class="pt-4 border-t border-gray-100 space-y-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">Change Status</label>
                    
                    @if($task->status === 'todo')
                        <button onclick="updateTaskStatus({{ $task->id }}, 'doing')" class="w-full px-4 py-2 bg-teal-700 text-white rounded-lg font-medium hover:bg-teal-800 transition-colors text-sm shadow">
                            Start Working (Doing)
                        </button>
                    @endif

                    @if($task->status === 'doing' || $task->status === 'overdue')
                        <button onclick="updateTaskStatus({{ $task->id }}, 'in_review')" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 transition-colors text-sm shadow">
                            Submit for Review
                        </button>
                        <button onclick="updateTaskStatus({{ $task->id }}, 'todo')" class="w-full px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
                            Move to To Do
                        </button>
                    @endif

                    @if($task->status === 'in_review')
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-center text-xs font-semibold text-yellow-700">
                            Awaiting admin approval...
                        </div>
                    @endif

                    @if($task->status === 'done')
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-center text-xs font-semibold text-green-700">
                            Task completed successfully!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dependencies Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                <h3 class="text-md font-bold text-gray-900 border-b border-gray-100 pb-3">Dependencies</h3>
                
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">Predecessor Tasks (Must complete first)</label>
                    <div class="space-y-2">
                        @forelse($task->dependencies as $dep)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100 text-xs font-medium">
                            <span class="text-gray-800 truncate flex-1 mr-2">{{ $dep->title }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold
                                {{ $dep->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $dep->status }}
                            </span>
                        </div>
                        @empty
                        <span class="text-xs text-gray-400 italic">No predecessor tasks.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                <h3 class="text-md font-bold text-gray-900 border-b border-gray-100 pb-3">Activity Log</h3>
                <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                    @forelse($task->activities as $act)
                    <div class="flex gap-2 text-xs">
                        <div class="w-1.5 h-1.5 rounded-full bg-teal-500 mt-1.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-gray-800 font-semibold">{{ $act->title }}</p>
                            <p class="text-gray-600 text-[10px]">{{ $act->description }}</p>
                            <p class="text-gray-400 text-[9px] mt-0.5">{{ $act->occurred_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">No activity logs recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6">
        <div class="flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-900 font-medium">Updating status...</span>
        </div>
    </div>
</div>

<script>
function toggleBlockerForm() {
    const el = document.getElementById('blockerFormContainer');
    if (el.classList.contains('hidden')) {
        el.classList.remove('hidden');
    } else {
        el.classList.add('hidden');
    }
}

function updateTaskStatus(taskId, newStatus) {
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
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
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
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
        alert(error.error || 'An error occurred while updating task status');
        document.getElementById('loadingOverlay').classList.add('hidden');
    });
}
</script>
@endsection

@extends('layouts.admin')

@section('title', 'Task Details')

@section('content')
<div class="space-y-6">
    <!-- Back & Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tasks.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                <p class="text-sm text-gray-600 mt-1">
                    Project: 
                    @if($task->project)
                        <a href="{{ route('admin.projects.show', $task->project) }}" class="text-blue-600 hover:underline font-semibold">
                            {{ $task->project->name }}
                        </a>
                    @else
                        <span class="text-gray-400">No Project</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn-secondary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Task
            </a>
            <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" onsubmit="return confirm('Are you sure you want to delete this task?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </form>
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
                <form method="POST" action="{{ route('admin.tasks.toggle-blocker', $task) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm shadow transition-colors">
                        Resolve Blocker / Unblock
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- Blocker Toggle Form (If Admin wants to flag it) -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">Flag this task if there's an issue or blocker:</span>
            <button onclick="toggleBlockerForm()" class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg text-xs shadow transition-all">
                Flag Blocker
            </button>
        </div>

        <div id="blockerFormContainer" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-5 space-y-3">
            <h4 class="font-bold text-yellow-800">Flag Blocker / Issue</h4>
            <form method="POST" action="{{ route('admin.tasks.toggle-blocker', $task) }}" class="space-y-3">
                @csrf
                <textarea name="blocker_description" placeholder="Describe the blocker or issue in detail..." required rows="2"
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

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Columns: Overview, Subtasks, Attachments, Comments -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Description</h2>
                <div class="text-gray-700 whitespace-pre-wrap leading-relaxed">
                    {!! $task->description ?: '<span class="text-gray-400 italic">No description provided for this task.</span>' !!}
                </div>

                <!-- Labels -->
                @if($task->labels->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-100">
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

                <!-- Progress Bar -->
                @if($subtasksCount > 0)
                <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                </div>
                @endif

                <!-- List -->
                <div class="space-y-3">
                    @forelse($task->subtasks as $subtask)
                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg border border-gray-100 group">
                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('admin.subtasks.toggle', $subtask) }}">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onchange="this.form.submit()" {{ $subtask->is_completed ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                            </form>
                            <span class="text-sm font-medium {{ $subtask->is_completed ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                {{ $subtask->title }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('admin.subtasks.destroy', $subtask) }}">
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
                    <form method="POST" action="{{ route('admin.tasks.subtasks', $task) }}" class="flex items-center gap-2 pt-2">
                        @csrf
                        <input type="text" name="title" required placeholder="Add new checklist item..."
                            class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm shadow transition-colors">
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
                            <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
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
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" download class="p-1 text-gray-500 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.attachments.destroy', $attachment) }}">
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
                <form method="POST" action="{{ route('admin.tasks.attachments', $task) }}" enctype="multipart/form-data" class="bg-gray-50 rounded-xl p-4 border border-dashed border-gray-300 flex items-center justify-between gap-4">
                    @csrf
                    <input type="file" name="file" required class="text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 file:hover:bg-blue-100 cursor-pointer">
                    <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm shadow transition-colors">
                        Upload
                    </button>
                </form>
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-gray-900">Discussion & Comments</h2>
                
                <!-- Comment list -->
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    @forelse($task->comments as $comment)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm shadow-sm flex-shrink-0">
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
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}">
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

                <!-- Add Comment Form -->
                <form method="POST" action="{{ route('admin.tasks.comments', $task) }}" class="flex gap-3">
                    @csrf
                    <textarea name="content" required placeholder="Write a comment..." rows="2"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-900"></textarea>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm shadow transition-colors flex-shrink-0 self-end">
                        Post
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Metadata Details -->
        <div class="space-y-6">
            <!-- Settings & Badges -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-6">
                <h3 class="text-md font-bold text-gray-900 border-b border-gray-100 pb-3">Properties</h3>
                
                <!-- Status -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</label>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full border
                            {{ $task->status === 'done' ? 'bg-green-100 text-green-700 border-green-200' : '' }}
                            {{ $task->status === 'doing' ? 'bg-blue-100 text-blue-700 border-blue-200' : '' }}
                            {{ $task->status === 'todo' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}
                            {{ $task->status === 'in_review' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' : '' }}
                            {{ $task->status === 'overdue' ? 'bg-red-100 text-red-700 border-red-200' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                        
                        <!-- Review actions if In Review -->
                        @if($task->status === 'in_review')
                        <div class="flex items-center gap-1 ml-auto">
                            <form method="POST" action="{{ route('admin.tasks.approve', $task) }}">
                                @csrf
                                <button type="submit" class="p-1 bg-green-500 hover:bg-green-600 text-white rounded" title="Approve Task">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </form>
                            <button onclick="openRejectModal()" class="p-1 bg-red-500 hover:bg-red-600 text-white rounded" title="Reject / Needs Work">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        @endif
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

                <!-- Assignee -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Assignee</label>
                    <div class="mt-1 flex items-center gap-2">
                        @if($task->user)
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                            {{ substr($task->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $task->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $task->user->email }}</p>
                        </div>
                        @else
                        <span class="text-sm text-gray-400 italic">Unassigned</span>
                        @endif
                    </div>
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
            </div>

            <!-- Dependencies Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                <h3 class="text-md font-bold text-gray-900 border-b border-gray-100 pb-3">Dependencies</h3>
                
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">Depends On (Predecessors)</label>
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

                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">Blocks (Dependents)</label>
                    <div class="space-y-2">
                        @forelse($task->dependents as $dep)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border border-gray-100 text-xs font-medium">
                            <span class="text-gray-800 truncate flex-1 mr-2">{{ $dep->title }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold
                                {{ $dep->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $dep->status }}
                            </span>
                        </div>
                        @empty
                        <span class="text-xs text-gray-400 italic">Does not block any tasks.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Task Log Card (Activity Feed) -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
                <h3 class="text-md font-bold text-gray-900 border-b border-gray-100 pb-3">Activity Log</h3>
                
                <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                    @forelse($task->activities as $act)
                    <div class="flex gap-2 text-xs">
                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
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

<!-- Reject Rejection Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Reject Task Review</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.tasks.reject', $task) }}" class="space-y-4">
            @csrf
            <div>
                <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">Rejection Feedback (What needs work?)</label>
                <textarea name="feedback" id="feedback" required rows="3" placeholder="Please specify details for the member..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 bg-white text-gray-900"></textarea>
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeRejectModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm shadow">Reject Review</button>
            </div>
        </form>
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
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection

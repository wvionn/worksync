@extends('layouts.admin')

@section('title', 'Project Details')

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.projects.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                <p class="text-gray-600 mt-1">Project Details</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn-secondary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Project
            </a>
            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Are you sure you want to delete this project?');">
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

    <!-- Project Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Status Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="mt-2 inline-block px-3 py-1 text-sm font-medium rounded-full
                        {{ $project->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $project->status === 'planning' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $project->status === 'on_hold' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $project->status === 'completed' ? 'bg-gray-100 text-gray-700' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Priority Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Priority</p>
                    <span class="mt-2 inline-block px-3 py-1 text-sm font-medium rounded-full
                        {{ $project->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $project->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $project->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $project->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                        {{ ucfirst($project->priority) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Progress Card (Auto-calculated) -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm text-gray-600">Progress</p>
                    @if($taskBreakdown['recently_added'] > 0)
                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                        +{{ $taskBreakdown['recently_added'] }} new
                    </span>
                    @endif
                </div>
                <div class="mt-2">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-2xl font-bold text-gray-900">{{ $project->progress }}%</span>
                        <span class="text-sm text-gray-500">{{ $taskBreakdown['completed'] }}/{{ $taskBreakdown['total'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $project->progress }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Auto-calculated from tasks</p>
                </div>
            </div>
        </div>

        <!-- Due Date Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div>
                <p class="text-sm text-gray-600">Due Date</p>
                @if($project->due_date)
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $project->due_date->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $project->due_date->diffForHumans() }}</p>
                @else
                    <p class="text-lg text-gray-400 mt-2">No due date set</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Project Information -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-600">Project Name</label>
                <p class="text-gray-900 mt-1">{{ $project->name }}</p>
            </div>
            @if($project->client_name)
            <div>
                <label class="text-sm font-medium text-gray-600">Client Name</label>
                <p class="text-gray-900 mt-1">{{ $project->client_name }}</p>
            </div>
            @endif
            <div>
                <label class="text-sm font-medium text-gray-600">Owner</label>
                @if($project->owner)
                <div class="flex items-center gap-2 mt-1">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm">
                        {{ substr($project->owner->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-gray-900">{{ $project->owner->name }}</p>
                        <p class="text-sm text-gray-500">{{ $project->owner->email }}</p>
                    </div>
                </div>
                @else
                <p class="text-gray-400 mt-1">Unassigned</p>
                @endif
            </div>
        </div>
        
        <!-- Assigned Members -->
        @if($project->members->count() > 0)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <label class="text-sm font-medium text-gray-600 mb-3 block">Assigned Members</label>
            <div class="flex flex-wrap gap-2">
                @foreach($project->members as $member)
                <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                    <span class="text-sm text-gray-900">{{ $member->name }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Task Breakdown Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Total Tasks</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $taskBreakdown['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">To Do</h3>
            <p class="text-3xl font-bold text-gray-700">{{ $taskBreakdown['todo'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">In Progress</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $taskBreakdown['in_progress'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Completed</h3>
            <p class="text-3xl font-bold text-green-600">{{ $taskBreakdown['completed'] }}</p>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Tasks</h2>
            <button onclick="openCreateTaskModal()" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Task
            </button>
        </div>

        <!-- Kanban Columns -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- To Do Column -->
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">To Do</h3>
                    <span class="px-2 py-1 bg-gray-200 text-gray-700 text-sm font-medium rounded-full">
                        {{ $project->tasks->where('status', 'todo')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($project->tasks->where('status', 'todo') as $task)
                    <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer">
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
                        <div class="flex items-center justify-between text-sm">
                            @if($task->user)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-white text-xs">
                                    {{ substr($task->user->name, 0, 1) }}
                                </div>
                                <span class="text-gray-700">{{ $task->user->name }}</span>
                            </div>
                            @else
                            <span class="text-gray-400">Unassigned</span>
                            @endif
                            @if($task->due_date)
                            <span class="text-gray-500">{{ $task->due_date->format('M d') }}</span>
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
                    <h3 class="text-lg font-bold text-gray-900">Doing</h3>
                    <span class="px-2 py-1 bg-blue-200 text-blue-700 text-sm font-medium rounded-full">
                        {{ $project->tasks->where('status', 'doing')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($project->tasks->where('status', 'doing') as $task)
                    <div class="bg-white rounded-lg p-4 border border-blue-200 hover:shadow-md transition-shadow cursor-pointer">
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
                        <div class="flex items-center justify-between text-sm">
                            @if($task->user)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs">
                                    {{ substr($task->user->name, 0, 1) }}
                                </div>
                                <span class="text-gray-700">{{ $task->user->name }}</span>
                            </div>
                            @else
                            <span class="text-gray-400">Unassigned</span>
                            @endif
                            @if($task->due_date)
                            <span class="text-gray-500">{{ $task->due_date->format('M d') }}</span>
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
                    <h3 class="text-lg font-bold text-gray-900">Done</h3>
                    <span class="px-2 py-1 bg-green-200 text-green-700 text-sm font-medium rounded-full">
                        {{ $project->tasks->where('status', 'done')->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($project->tasks->where('status', 'done') as $task)
                    <div class="bg-white rounded-lg p-4 border border-green-200 hover:shadow-md transition-shadow cursor-pointer">
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
                        <div class="flex items-center justify-between text-sm">
                            @if($task->user)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center text-white text-xs">
                                    {{ substr($task->user->name, 0, 1) }}
                                </div>
                                <span class="text-gray-700">{{ $task->user->name }}</span>
                            </div>
                            @else
                            <span class="text-gray-400">Unassigned</span>
                            @endif
                            @if($task->due_date)
                            <span class="text-gray-500">{{ $task->due_date->format('M d') }}</span>
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
        </div>
    </div>



<!-- Create Task Modal Placeholder -->
<div id="createTaskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Create New Task</h3>
            <button onclick="closeCreateTaskModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.tasks.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <input type="hidden" name="redirect_to_project" value="1">
            
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Task Title</label>
                <input type="text" name="title" id="title" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <input type="hidden" name="status" value="todo">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select name="priority" id="priority" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                    <select name="user_id" id="user_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Unassigned</option>
                        @foreach(\App\Models\User::where('role', 'member')->orderBy('name')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                <input type="date" name="due_date" id="due_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCreateTaskModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Create Task</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateTaskModal() {
    document.getElementById('createTaskModal').classList.remove('hidden');
}

function closeCreateTaskModal() {
    document.getElementById('createTaskModal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateTaskModal();
    }
});

// Close modal when clicking outside
document.getElementById('createTaskModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateTaskModal();
    }
});
</script>
@endsection

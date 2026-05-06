@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Projects -->
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">Total Projects</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProjects }}</h3>
                    <p class="text-sm text-green-600 mt-2">+4%</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Tasks -->
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">Total Tasks Assigned</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalTasks }}</h3>
                    <p class="text-sm text-green-600 mt-2">+12%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tasks Completed -->
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">Tasks Completed</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $completedTasks }}</h3>
                    <p class="text-sm text-blue-600 mt-2">{{ $completionRate }}% rate</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tasks Overdue -->
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">Tasks Overdue</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $overdueTasks }}</h3>
                    <p class="text-sm text-red-600 mt-2">Urgent</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Active Projects (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Active Tasks</h2>
                <a href="{{ route('admin.projects.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All Archive</a>
            </div>

            <div class="space-y-4">
                @forelse($activeProjects as $project)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Project Avatar -->
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold text-lg
                                {{ $loop->index % 3 == 0 ? 'bg-blue-500' : ($loop->index % 3 == 1 ? 'bg-orange-400' : 'bg-cyan-500') }}">
                                {{ strtoupper(substr($project->name, 0, 2)) }}
                            </div>

                            <!-- Project Info -->
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $project->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Updated {{ $project->updated_at->diffForHumans() }}</p>

                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div class="flex items-center justify-between text-sm mb-1">
                                        <span class="text-gray-600">Progress</span>
                                        <span class="font-medium text-gray-900">{{ $project->progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all
                                            {{ $project->progress >= 80 ? 'bg-blue-600' : ($project->progress >= 50 ? 'bg-orange-500' : 'bg-cyan-500') }}"
                                            style="width: {{ $project->progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Count & Action -->
                        <div class="text-right ml-4">
                            <div class="text-sm text-gray-600 mb-3">
                                <span class="font-semibold text-gray-900">{{ $project->completed_tasks_count }}/{{ $project->tasks_count }}</span>
                                <span>TASKS</span>
                            </div>
                            <button onclick="openProjectModal(
                                {{ $project->id }}, 
                                '{{ addslashes($project->name) }}', 
                                {{ $project->progress }}, 
                                '{{ $project->status }}', 
                                {{ $project->tasks_count }}, 
                                {{ $project->completed_tasks_count }},
                                '{{ $project->client_name ?? 'N/A' }}',
                                '{{ $project->priority }}',
                                '{{ $project->owner ? addslashes($project->owner->name) : 'Unassigned' }}',
                                '{{ $project->due_date ? $project->due_date->format('M d, Y') : 'No deadline' }}'
                            )" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <p>No active projects found.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions & Board Preview (1/3 width) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.projects.create') }}" class="block w-full px-4 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors text-center">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Task
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Details Modal -->
<div id="projectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-4 border w-80 shadow-lg rounded-xl bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 class="text-base font-bold text-gray-900" id="modalProjectName">Project Details</h3>
            <button onclick="closeProjectModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="mt-3 space-y-3">
            <!-- Assigned To -->
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">Assigned To</span>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-medium" id="modalOwnerAvatar">A</div>
                    <span class="text-sm font-medium text-gray-900" id="modalOwner">-</span>
                </div>
            </div>

            <!-- Due Date & Priority -->
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Due Date</p>
                    <p class="text-sm font-medium text-gray-900" id="modalDueDate">-</p>
                </div>
                <span id="modalPriority" class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Medium</span>
            </div>

            <!-- Progress -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-500">Progress</span>
                    <span class="text-sm font-bold text-blue-600" id="modalProgress">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div id="modalProgressBar" class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-blue-50 rounded-lg p-2">
                    <p class="text-xs text-gray-600">Total Tasks</p>
                    <p class="text-lg font-bold text-gray-900" id="modalTotalTasks">0</p>
                </div>
                <div class="bg-green-50 rounded-lg p-2">
                    <p class="text-xs text-gray-600">Completed</p>
                    <p class="text-lg font-bold text-green-600" id="modalCompletedTasks">0</p>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center justify-between pt-1">
                <span class="text-xs text-gray-500">Status</span>
                <span id="modalStatus" class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">Active</span>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 pt-2">
                <a id="modalViewFullBtn" href="#" class="flex-1 px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg font-medium hover:bg-blue-700 transition-colors text-center">
                    View Full
                </a>
                <button onclick="closeProjectModal()" class="flex-1 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs rounded-lg font-medium hover:bg-gray-200 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Modal Functions
function openProjectModal(id, name, progress, status, totalTasks, completedTasks, client, priority, owner, dueDate) {
    // Set modal content
    document.getElementById('modalProjectName').textContent = name;
    document.getElementById('modalProgress').textContent = progress + '%';
    document.getElementById('modalProgressBar').style.width = progress + '%';
    document.getElementById('modalTotalTasks').textContent = totalTasks;
    document.getElementById('modalCompletedTasks').textContent = completedTasks;
    
    // Set assigned person
    document.getElementById('modalOwner').textContent = owner;
    document.getElementById('modalOwnerAvatar').textContent = owner.charAt(0).toUpperCase();
    
    // Set due date
    document.getElementById('modalDueDate').textContent = dueDate;
    
    // Set priority badge
    const priorityBadge = document.getElementById('modalPriority');
    priorityBadge.textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
    
    const priorityColors = {
        'urgent': 'bg-red-100 text-red-700',
        'high': 'bg-orange-100 text-orange-700',
        'medium': 'bg-yellow-100 text-yellow-700',
        'low': 'bg-green-100 text-green-700'
    };
    priorityBadge.className = 'px-2 py-0.5 text-xs font-medium rounded-full ' + (priorityColors[priority] || priorityColors['medium']);
    
    // Set status badge
    const statusBadge = document.getElementById('modalStatus');
    statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');
    
    const statusColors = {
        'active': 'bg-green-100 text-green-700',
        'planning': 'bg-blue-100 text-blue-700',
        'on_hold': 'bg-yellow-100 text-yellow-700',
        'completed': 'bg-gray-100 text-gray-700'
    };
    statusBadge.className = 'px-2 py-0.5 text-xs font-medium rounded-full ' + (statusColors[status] || statusColors['active']);
    
    // Set view full button link
    document.getElementById('modalViewFullBtn').href = '/admin/projects/' + id;
    
    // Show modal
    document.getElementById('projectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProjectModal() {
    document.getElementById('projectModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('projectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProjectModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProjectModal();
    }
});
</script>

@endsection

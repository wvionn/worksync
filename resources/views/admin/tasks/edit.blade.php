@extends('layouts.admin')

@section('title', 'Edit Task')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tasks.show', $task) }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Task</h1>
            <p class="text-sm text-gray-600 mt-1">Modify task information and assignments</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.tasks.update', $task) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Project Selector -->
            <div>
                <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-2">Project</label>
                <select name="project_id" id="project_id" required onchange="onProjectChange()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" data-members="{{ json_encode($project->members->pluck('id')) }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Task Title</label>
                <input type="text" name="title" id="title" required value="{{ $task->title }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">{{ $task->description }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}" {{ $task->status === $option ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">Priority</label>
                    <select name="priority" id="priority" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        @foreach($priorityOptions as $option)
                            <option value="{{ $option }}" {{ $task->priority === $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Assignee -->
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">Assign To</label>
                    <select name="user_id" id="user_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">Unassigned</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ $task->user_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                    <input type="datetime-local" name="due_date" id="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '' }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Milestone -->
                <div>
                    <label for="milestone_id" class="block text-sm font-semibold text-gray-700 mb-2">Milestone</label>
                    <select name="milestone_id" id="milestone_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">None</option>
                        @foreach($milestones as $m)
                            <option value="{{ $m->id }}" data-project="{{ $m->project_id }}" {{ $task->milestone_id == $m->id ? 'selected' : '' }}>
                                {{ $m->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Blocked status -->
                <div class="flex items-center pt-8">
                    <input type="checkbox" name="is_blocked" id="is_blocked" value="1" onchange="toggleBlockerText()" {{ $task->is_blocked ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                    <label for="is_blocked" class="ml-2 text-sm font-semibold text-gray-700 cursor-pointer">Mark as Blocked / Has Issue</label>
                </div>
            </div>

            <!-- Blocker description -->
            <div id="blocker_desc_container" class="{{ $task->is_blocked ? '' : 'hidden' }}">
                <label for="blocker_description" class="block text-sm font-semibold text-gray-700 mb-2">Blocker Reason / Issue Description</label>
                <textarea name="blocker_description" id="blocker_description" rows="2"
                    class="w-full px-4 py-2 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 bg-white text-gray-900">{{ $task->blocker_description }}</textarea>
            </div>

            <!-- Labels selection -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Labels / Tags</label>
                <div class="flex flex-wrap gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    @forelse($labels as $lbl)
                    <label class="flex items-center gap-2 px-2.5 py-1 bg-white rounded-md border border-gray-200 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" name="label_ids[]" value="{{ $lbl->id }}" {{ $task->labels->contains($lbl->id) ? 'checked' : '' }} class="text-blue-600 rounded">
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $lbl->color }}"></span>
                        <span class="text-xs font-semibold text-gray-800">{{ $lbl->name }}</span>
                    </label>
                    @empty
                    <span class="text-xs text-gray-400 italic">No labels available. Create them in project settings.</span>
                    @endforelse
                </div>
            </div>

            <!-- Dependencies -->
            <div>
                <label for="dependencies" class="block text-sm font-semibold text-gray-700 mb-2">Predecessor Tasks (Task Dependencies)</label>
                <select name="dependency_ids[]" id="dependencies" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white h-24">
                    @foreach($tasks as $t)
                        @if($t->id !== $task->id)
                            <option value="{{ $t->id }}" data-project="{{ $t->project_id }}" {{ $task->dependencies->contains($t->id) ? 'selected' : '' }}>
                                [{{ $t->project->name ?? 'No Project' }}] {{ $t->title }} ({{ $t->status }})
                            </option>
                        @endif
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple dependencies.</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.tasks.show', $task) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleBlockerText() {
    const isChecked = document.getElementById('is_blocked').checked;
    const container = document.getElementById('blocker_desc_container');
    if (isChecked) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}

function onProjectChange() {
    const projectSelect = document.getElementById('project_id');
    const selectedOption = projectSelect.options[projectSelect.selectedIndex];
    const projectId = projectSelect.value;
    
    // Filter Assignee
    const userSelect = document.getElementById('user_id');
    let memberIds = [];
    if (selectedOption && selectedOption.getAttribute('data-members')) {
        memberIds = JSON.parse(selectedOption.getAttribute('data-members')).map(String);
    }
    
    Array.from(userSelect.options).forEach(opt => {
        if (!opt.value) {
            opt.style.display = '';
            return;
        }
        if (projectId === "") {
            opt.style.display = '';
        } else {
            opt.style.display = memberIds.includes(String(opt.value)) ? '' : 'none';
        }
    });

    // Reset selected assignee if they are now hidden
    if (userSelect.selectedOptions[0] && userSelect.selectedOptions[0].style.display === 'none') {
        userSelect.value = '';
    }

    // Filter Milestones
    const milestoneSelect = document.getElementById('milestone_id');
    Array.from(milestoneSelect.options).forEach(opt => {
        if (!opt.value) {
            opt.style.display = '';
            return;
        }
        const proj = opt.getAttribute('data-project');
        opt.style.display = proj === projectId ? '' : 'none';
    });
    
    // Filter Dependencies
    const depSelect = document.getElementById('dependencies');
    if (depSelect) {
        Array.from(depSelect.options).forEach(opt => {
            const proj = opt.getAttribute('data-project');
            opt.style.display = proj === projectId ? '' : 'none';
        });
    }
    
    // Reset selected items that are now hidden
    if (milestoneSelect.selectedOptions[0] && milestoneSelect.selectedOptions[0].style.display === 'none') {
        milestoneSelect.value = '';
    }
    if (depSelect) {
        Array.from(depSelect.selectedOptions).forEach(opt => {
            if (opt.style.display === 'none') {
                opt.selected = false;
            }
        });
    }
}

// Trigger initial filter on page load
document.addEventListener('DOMContentLoaded', () => {
    onProjectChange();
});
</script>
@endsection

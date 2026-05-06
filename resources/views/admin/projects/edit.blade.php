@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.projects.show', $project) }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Project</h1>
            <p class="text-gray-600 mt-1">Update project information</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Project Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Project Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Client Name -->
            <div>
                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Client Name
                </label>
                <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $project->client_name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('client_name') border-red-500 @enderror">
                @error('client_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status and Priority -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ old('status', $project->status) === $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" id="priority" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priority') border-red-500 @enderror">
                        @foreach($priorityOptions as $priority)
                            <option value="{{ $priority }}" {{ old('priority', $project->priority) === $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Assign Members -->
            <div>
                <label for="member_ids" class="block text-sm font-medium text-gray-700 mb-2">
                    Assign Members
                </label>
                <select name="member_ids[]" id="member_ids" multiple
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('member_ids') border-red-500 @enderror"
                    style="min-height: 120px;">
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" 
                            {{ in_array($member->id, old('member_ids', $project->members->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple members</p>
                @error('member_ids')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Due Date
                </label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('due_date') border-red-500 @enderror">
                @error('due_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.projects.show', $project) }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

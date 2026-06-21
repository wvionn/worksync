@extends('layouts.member')

@section('header_title', 'Project Details')

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('member.projects.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                <p class="text-gray-600 mt-1">Project Details</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-700">
                Real-time Updates
            </span>
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

        <!-- Progress Card -->
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
        
        <!-- Team Members -->
        @if($project->members->count() > 0)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <label class="text-sm font-medium text-gray-600 mb-3 block">Team Members</label>
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

    <!-- Livewire Kanban Board with Real-time Updates -->
    @livewire('member.project-kanban', ['projectId' => $project->id])

</div>
@endsection

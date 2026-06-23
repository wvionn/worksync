@extends('layouts.member')

@section('header_title', 'Project Details')

@section('content')
<div class="space-y-6">
    <!-- Combined Simplified Header & Project Info -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- Left Side: Back button + Title & Status/Priority -->
            <div class="flex items-start gap-4">
                <a href="{{ route('member.projects.index') }}" class="text-gray-500 hover:text-teal-600 transition-colors p-1.5 hover:bg-gray-50 rounded-lg mt-0.5" title="Back to Projects">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl md:text-2xl font-bold text-gray-900 leading-tight">{{ $project->name }}</h1>
                        
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                            {{ $project->status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                            {{ $project->status === 'planning' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                            {{ $project->status === 'on_hold' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                            {{ $project->status === 'completed' ? 'bg-gray-50 text-gray-700 border-gray-200' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>

                        <!-- Priority Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                            {{ $project->priority === 'urgent' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                            {{ $project->priority === 'high' ? 'bg-orange-50 text-orange-700 border-orange-200' : '' }}
                            {{ $project->priority === 'medium' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                            {{ $project->priority === 'low' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}">
                            {{ ucfirst($project->priority) }} Priority
                        </span>
                    </div>

                    <!-- Compact Metadata Row (Due date & Client) -->
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-2 text-sm text-gray-500">
                        @if($project->due_date)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Due <strong>{{ $project->due_date->format('M d, Y') }}</strong> <span class="text-gray-400">({{ $project->due_date->diffForHumans() }})</span></span>
                            </div>
                        @else
                            <span class="text-gray-400">No due date</span>
                        @endif

                        @if($project->client_name)
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 hidden md:block"></span>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>Client: <strong>{{ $project->client_name }}</strong></span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side: Owner, Team Avatars, and Progress -->
            <div class="flex flex-wrap items-center gap-6 lg:self-center">
                <!-- Owner & Team Avatars -->
                <div class="flex items-center">
                    <span class="text-xs font-medium text-gray-400 mr-2.5 hidden sm:inline">Team</span>
                    <div class="flex items-center -space-x-2">
                        <!-- Owner Avatar -->
                        @if($project->owner)
                            <div class="relative group" title="Owner: {{ $project->owner->name }} ({{ $project->owner->email }})">
                                <div class="w-9 h-9 rounded-full bg-teal-600 border-2 border-white text-white flex items-center justify-center font-bold text-sm shadow-sm ring-2 ring-teal-500/20">
                                    {{ substr($project->owner->name, 0, 1) }}
                                </div>
                                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-teal-400" title="Project Owner"></span>
                            </div>
                        @endif

                        <!-- Members Avatars -->
                        @foreach($project->members as $member)
                            @if(!$project->owner || $member->id !== $project->owner->id)
                                <div class="w-9 h-9 rounded-full bg-gray-400 border-2 border-white text-white flex items-center justify-center font-semibold text-sm shadow-sm hover:z-10 transition-all hover:scale-105 cursor-default" 
                                     title="Member: {{ $member->name }}">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <!-- Progress Indicator -->
                <div class="w-36 border-l border-gray-100 pl-4">
                    <div class="flex items-center justify-between text-xs font-semibold mb-1">
                        <span class="text-teal-700">Progress</span>
                        <span class="text-gray-500">{{ $taskBreakdown['completed'] }}/{{ $taskBreakdown['total'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-teal-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Livewire Kanban Board with Real-time Updates -->
    @livewire('member.project-kanban', ['projectId' => $project->id])

</div>
@endsection

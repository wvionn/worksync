@extends('layouts.member')

@section('header_title', 'My Projects')

@section('content')

<div class="space-y-8">

<div class="flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            My Projects
        </h1>
        <p class="text-slate-500 mt-1">
            Track your project progress and tasks.
        </p>
    </div>
</div>

<!-- PROJECT LIST -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

    @forelse($projects as $project)
    <a href="{{ route('member.projects.show', $project['id']) }}"
        class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer">

        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-slate-800">
                {{ $project['name'] }}
            </h2>
        </div>

        <div class="mt-5">
            <div class="w-full h-3 rounded-full bg-slate-100">
                <div class="h-3 rounded-full 
                    {{ $project['progress'] >= 100 ? 'bg-green-500' : ($project['progress'] >= 50 ? 'bg-blue-500' : 'bg-yellow-500') }}" 
                    style="width: {{ $project['progress'] }}%">
                </div>
            </div>

            <div class="flex justify-between mt-2 text-sm">
                <span class="text-slate-500">{{ $project['completed_tasks'] }} / {{ $project['total_tasks'] }} Tasks</span>
                <span class="font-semibold 
                    {{ $project['progress'] >= 100 ? 'text-green-600' : ($project['progress'] >= 50 ? 'text-blue-600' : 'text-yellow-600') }}">
                    {{ $project['progress'] }}%
                </span>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-2">
            <span class="px-3 py-1 text-xs font-semibold rounded-full
                {{ $project['priority'] === 'urgent' ? 'bg-red-100 text-red-700' : 
                   ($project['priority'] === 'high' ? 'bg-orange-100 text-orange-700' : 
                   ($project['priority'] === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                {{ ucfirst($project['priority']) }}
            </span>
            <span class="px-3 py-1 text-xs font-semibold rounded-full capitalize
                {{ $project['status'] === 'active' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                {{ str_replace('_', ' ', $project['status']) }}
            </span>
        </div>

    </a> 
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-3xl p-12 border border-slate-200 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">No Projects Assigned</h3>
            <p class="text-slate-500">You haven't been assigned to any projects yet.</p>
        </div>
    </div>
    @endforelse

</div>

</div>

@endsection

@extends('layouts.member')

@section('header_title', 'Overview')

@section('content')

<div class="p-8">

    <!-- Date -->
    <div class="flex items-center gap-2 text-gray-500 text-sm mb-8">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>

        <span id="currentDate"></span>
    </div>

    <!-- Greeting -->
    <div class="flex items-center gap-6 mb-10">

        <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-3xl font-extrabold text-blue-600 shadow-sm">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>

        <div>
            <h1 class="text-5xl font-bold text-blue-600">
                Halo {{ explode(' ', Auth::user()->name)[0] }}!
            </h1>

            <p class="text-gray-500 text-xl mt-2">
                Ayo selesaikan tugasmu hari ini
            </p>
        </div>

    </div>

    @php

        $totalProjects = $projectsCount;

        $overdueTasks =
            $todoTasks->filter(function ($task) {

                return $task->due_date
                    && \Carbon\Carbon::parse($task->due_date)->isPast();

            })->count();

    @endphp

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

        <!-- My Projects -->
        <a href="{{ route('member.projects.index') }}"
           class="bg-sky-100 rounded-3xl p-8 border border-sky-200 hover:scale-105 transition duration-300">

            <h4 class="text-sky-700 text-xs font-bold uppercase tracking-wider">
                My Projects
            </h4>

            <h2 class="text-5xl font-bold text-sky-800 mt-4">
                {{ $totalProjects }}
            </h2>

            <p class="text-sky-600 text-sm mt-3">
                View Projects →
            </p>

        </a>

        <!-- Doing -->
        <a href="{{ route('member.projects.index') }}"
           class="bg-blue-600 rounded-3xl p-8 text-white hover:scale-105 transition duration-300">

            <h4 class="text-xs font-bold uppercase tracking-wider">
                Doing
            </h4>

            <h2 class="text-5xl font-bold mt-4">
                {{ $doingTasks->count() }}
            </h2>

            <p class="text-blue-100 text-sm mt-3">
                In Progress
            </p>

        </a>

        <!-- Done -->
        <a href="{{ route('member.projects.index') }}"
           class="bg-emerald-500 rounded-3xl p-8 text-white hover:scale-105 transition duration-300">

            <h4 class="text-xs font-bold uppercase tracking-wider">
                Done
            </h4>

            <h2 class="text-5xl font-bold mt-4">
                {{ $doneTasks->count() }}
            </h2>

            <p class="text-emerald-100 text-sm mt-3">
                Completed
            </p>

        </a>

        <!-- Overdue -->
        <a href="{{ route('member.deadlines') }}"
           class="bg-red-500 rounded-3xl p-8 text-white hover:scale-105 transition duration-300">

            <h4 class="text-xs font-bold uppercase tracking-wider">
                Overdue
            </h4>

            <h2 class="text-5xl font-bold mt-4">
                {{ $overdueTasks }}
            </h2>

            <p class="text-red-100 text-sm mt-3">
                Need Attention
            </p>

        </a>

    </div>

    <!-- Pekerjaan Mendesak -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-blue-600">
                Pekerjaan Mendesak
            </h2>
            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                Real-time
            </span>
        </div>

        <div class="space-y-5" wire:poll.5s>

            @forelse($todoTasks->take(5) as $task)
                @livewire('member.quick-task-card', ['task' => $task], key('task-'.$task->id))
            @empty

                <div class="text-center py-10 text-gray-400">
                    Tidak ada tugas mendesak
                </div>

            @endforelse

        </div>

    </div>

</div>

<script>

document.getElementById('currentDate').innerHTML =
new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});

</script>

@endsection
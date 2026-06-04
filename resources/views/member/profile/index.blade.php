@extends('layouts.member')

@section('header_title', 'My Profile')

@section('content')

@php

$user = Auth::user();

$totalProjects = \App\Models\Project::count();

$totalTasks = \App\Models\Task::count();

$doneTasks = \App\Models\Task::where('status', 'done')->count();

$doingTasks = \App\Models\Task::where('status', 'doing')->count();

$todoTasks = \App\Models\Task::where('status', 'todo')->count();

$completedProjects = \App\Models\Project::with('tasks')
    ->get()
    ->filter(function ($project) {

        $total = $project->tasks->count();

        $done = $project->tasks
            ->where('status', 'done')
            ->count();

        return $total > 0 && $total == $done;
    })
    ->count();

$performance = $totalTasks > 0
    ? round(($doneTasks / $totalTasks) * 100)
    : 0;

@endphp

<div class="space-y-8">

    <!-- Header -->
    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            My Profile 👩‍💻
        </h1>

        <p class="text-slate-500">
            Personal information and performance.
        </p>

    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">

        <div class="flex items-center gap-6">

            <img
                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff"
                class="w-28 h-28 rounded-full">

            <div>

                <h2 class="text-3xl font-bold text-slate-800">
                    {{ $user->name }}
                </h2>

                <p class="text-slate-500">
                    {{ $user->email }}
                </p>

                <span class="mt-3 inline-block px-4 py-2 bg-indigo-100 text-indigo-600 rounded-xl">
                    Member
                </span>

            </div>

        </div>

    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

            <p class="text-slate-500">
                Total Projects
            </p>

            <h3 class="text-4xl font-bold mt-2 text-slate-800">
                {{ $totalProjects }}
            </h3>

        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

            <p class="text-slate-500">
                Completed Projects
            </p>

            <h3 class="text-4xl font-bold mt-2 text-green-600">
                {{ $completedProjects }}
            </h3>

        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

            <p class="text-slate-500">
                Done Tasks
            </p>

            <h3 class="text-4xl font-bold mt-2 text-blue-600">
                {{ $doneTasks }}
            </h3>

        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

            <p class="text-slate-500">
                Doing Tasks
            </p>

            <h3 class="text-4xl font-bold mt-2 text-yellow-500">
                {{ $doingTasks }}
            </h3>

        </div>

    </div>

    <!-- Performance -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">

        <div class="flex justify-between items-center">

            <h3 class="text-xl font-bold text-slate-800">
                Overall Performance
            </h3>

            <span class="font-bold text-indigo-600 text-lg">
                {{ $performance }}%
            </span>

        </div>

        <div class="w-full h-4 bg-slate-100 rounded-full mt-5 overflow-hidden">

            <div
                class="h-4 bg-indigo-500 rounded-full transition-all duration-700"
                style="width: {{ $performance }}%">
            </div>

        </div>

        <p class="mt-4 text-slate-500">

            {{ $doneTasks }} of {{ $totalTasks }} tasks completed.

        </p>

    </div>

    <!-- Task Summary -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">

        <h3 class="text-xl font-bold text-slate-800 mb-6">
            Task Summary
        </h3>

        <div class="grid grid-cols-3 gap-6 text-center">

            <div>

                <h4 class="text-3xl font-bold text-gray-500">
                    {{ $todoTasks }}
                </h4>

                <p class="text-slate-500 mt-2">
                    To Do
                </p>

            </div>

            <div>

                <h4 class="text-3xl font-bold text-yellow-500">
                    {{ $doingTasks }}
                </h4>

                <p class="text-slate-500 mt-2">
                    Doing
                </p>

            </div>

            <div>

                <h4 class="text-3xl font-bold text-green-500">
                    {{ $doneTasks }}
                </h4>

                <p class="text-slate-500 mt-2">
                    Done
                </p>

            </div>

        </div>

    </div>

</div>

@endsection
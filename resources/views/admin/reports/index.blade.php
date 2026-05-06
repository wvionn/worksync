@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
        <p class="text-gray-600 mt-1">View analytics and performance metrics</p>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Project Performance</h3>
            <p class="text-gray-600 text-sm mb-4">Track project completion rates and timelines</p>
            <button class="btn-primary w-full">View Report</button>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Team Productivity</h3>
            <p class="text-gray-600 text-sm mb-4">Analyze team member task completion</p>
            <button class="btn-primary w-full">View Report</button>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Time Tracking</h3>
            <p class="text-gray-600 text-sm mb-4">Monitor time spent on projects and tasks</p>
            <button class="btn-primary w-full">View Report</button>
        </div>
    </div>
</div>
@endsection

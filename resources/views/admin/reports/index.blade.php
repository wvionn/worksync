@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
                <p class="text-gray-600 mt-1">
                    View real-time project metrics, task distribution, and productivity analytics.
                </p>
            </div>

            <button onclick="window.print()"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                <span>Print Report</span>
            </button>
        </div>

        <!-- Summary Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Project Statuses</h3>
                <p class="text-3xl font-extrabold text-gray-900">{{ collect($projectStatusCounts)->sum() }}</p>
                <p class="text-xs text-gray-500 mt-2">Projects across all stages</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Active Tasks</h3>
                <p class="text-3xl font-extrabold text-blue-600">
                    {{ ($taskStatusCounts['todo'] ?? 0) + ($taskStatusCounts['doing'] ?? 0) }}
                </p>
                <p class="text-xs text-gray-500 mt-2">Currently being worked on</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tasks Completed</h3>
                <p class="text-3xl font-extrabold text-green-600">{{ $taskStatusCounts['done'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-2">Successfully closed tasks</p>
            </div>

            <div class="bg-red-50 rounded-2xl border border-red-100 p-6 shadow-sm">
                <h3 class="text-xs font-semibold text-red-600 uppercase tracking-wider mb-2">Overdue Tasks</h3>
                <p class="text-3xl font-extrabold text-red-700">{{ $overdueList->count() }}</p>
                <p class="text-xs text-red-500 mt-2">Require immediate attention</p>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Project Status Chart -->
            <div
                class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col h-[380px]">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Project Status Distribution</h3>
                <div class="flex-1 relative min-h-[280px]">
                    <canvas id="projectStatusChart"></canvas>
                </div>
            </div>

            <!-- Task Status Chart -->
            <div
                class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col h-[380px]">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Task Status Distribution</h3>
                <div class="flex-1 relative min-h-[280px]">
                    <canvas id="taskStatusChart"></canvas>
                </div>
            </div>

            <!-- Task Priority Chart -->
            <div
                class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col h-[380px]">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Task Priority Levels</h3>
                <div class="flex-1 relative min-h-[280px]">
                    <canvas id="taskPriorityChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Charts Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Timeline Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm h-[400px] flex flex-col">
                <h3 class="text-sm font-bold text-slate-900 mb-4">
                    Task Creation & Completion (Last 6 Months)
                </h3>
                <div class="flex-1 relative">
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>

            <!-- Overdue Tasks List -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm h-[400px] flex flex-col">
                <h3 class="text-sm font-bold text-slate-900 mb-2">Overdue Tasks</h3>
                <p class="text-xs text-gray-500 mb-4">List of non-completed tasks past due dates.</p>

                <div class="flex-1 overflow-y-auto space-y-3 pr-1">
                    @forelse($overdueList as $task)
                        <div class="p-3 border border-red-100 rounded-lg bg-red-50/50 hover:bg-red-50 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="font-semibold text-gray-900 text-xs line-clamp-1">
                                    {{ $task->title }}
                                </h4>
                                <span
                                    class="px-1.5 py-0.5 text-[8px] font-bold uppercase rounded bg-red-100 text-red-700 flex-shrink-0">
                                    {{ $task->priority }}
                                </span>
                            </div>

                            @if($task->project)
                                <p class="text-[10px] text-gray-500 mt-1 font-medium truncate">
                                    Project: {{ $task->project->name }}
                                </p>
                            @endif

                            <div class="flex items-center justify-between mt-2 text-[10px] text-gray-400">
                                <span class="text-red-600 font-medium">
                                    Due:
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'N/A' }}
                                </span>
                                <span class="text-gray-500 truncate">
                                    Assignee: {{ $task->user ? $task->user->name : 'Unassigned' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 py-12">
                            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <p class="text-xs">No overdue tasks! Keep it up.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Completed Projects History -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Completed Projects History</h3>
                    <p class="text-xs text-gray-500 mt-1">Recently completed projects with completion details</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                    {{ $completedProjects->count() }} Projects
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Project Name
                            </th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Owner
                            </th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tasks
                            </th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Completion
                            </th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Completed Date
                            </th>
                            <th class="text-center py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Priority
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completedProjects as $project)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.projects.show', $project) }}" 
                                       class="font-semibold text-gray-900 hover:text-blue-600 text-sm">
                                        {{ $project->name }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $project->client_name ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $project->owner->name ?? 'N/A' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                        <span class="font-semibold text-green-600">{{ $project->tasks->where('status', 'done')->count() }}</span>
                                        <span class="text-gray-400">/</span>
                                        <span>{{ $project->tasks->count() }}</span>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-green-500 rounded-full" 
                                                 style="width: {{ $project->progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-green-600">
                                            {{ $project->progress }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $project->updated_at->format('M d, Y') }}
                                    <span class="text-xs text-gray-400 block">
                                        {{ $project->updated_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($project->priority === 'urgent') bg-red-100 text-red-700
                                        @elseif($project->priority === 'high') bg-orange-100 text-orange-700
                                        @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ ucfirst($project->priority) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm font-medium">No completed projects yet</p>
                                        <p class="text-xs mt-1">Completed projects will appear here</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const commonChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 14,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            };

            const projectStatuses = @json(array_keys($projectStatusCounts->toArray()));
            const projectCounts = @json(array_values($projectStatusCounts->toArray()));

            new Chart(document.getElementById('projectStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: projectStatuses.map(s => s.replace('_', ' ').toUpperCase()),
                    datasets: [{
                        data: projectCounts,
                        backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#6b7280'],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: commonChartOptions
            });

            const taskStatuses = @json(array_keys($taskStatusCounts->toArray()));
            const taskCounts = @json(array_values($taskStatusCounts->toArray()));

            new Chart(document.getElementById('taskStatusChart'), {
                type: 'pie',
                data: {
                    labels: taskStatuses.map(s => s.replace('_', ' ').toUpperCase()),
                    datasets: [{
                        data: taskCounts,
                        backgroundColor: ['#6b7280', '#3b82f6', '#10b981', '#ef4444'],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: commonChartOptions
            });

            const priorityLabels = @json(array_keys($taskPriorityCounts->toArray()));
            const priorityCounts = @json(array_values($taskPriorityCounts->toArray()));

            new Chart(document.getElementById('taskPriorityChart'), {
                type: 'bar',
                data: {
                    labels: priorityLabels.map(l => l.toUpperCase()),
                    datasets: [{
                        label: 'Tasks',
                        data: priorityCounts,
                        backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444', '#dc2626'],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            const timelineData = @json($timeline);
            const months = timelineData.map(item => item.month);
            const createdCounts = timelineData.map(item => item.created);
            const completedCounts = timelineData.map(item => item.completed);

            new Chart(document.getElementById('timelineChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Tasks Created',
                            data: createdCounts,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.08)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2
                        },
                        {
                            label: 'Tasks Completed',
                            data: completedCounts,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.08)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: '#e5e7eb'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

        });
    </script>
@endsection
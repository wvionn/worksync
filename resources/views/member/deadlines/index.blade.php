@extends('layouts.member')

@section('header_title', 'Deadlines')

@section('content')

<div class="space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Deadlines
            </h1>

            <p class="text-slate-500 mt-1">
                Track upcoming deadlines and overdue tasks.
            </p>
        </div>

        <div class="bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-sm">

            <p class="text-sm text-slate-500">
                Today
            </p>

            <p class="font-bold text-slate-800">
                {{ now()->format('d M Y') }}
            </p>

        </div>

    </div>

    <!-- Calendar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

        <div class="flex items-center justify-between mb-6">

            <h2 class="font-bold text-xl text-slate-800">
                Monthly Calendar
            </h2>

            <span class="text-slate-500">
                {{ now()->format('F Y') }}
            </span>

        </div>

        <!-- Days -->
        <div class="grid grid-cols-7 gap-3 text-center mb-4">

            <div class="font-semibold text-slate-500">Sun</div>
            <div class="font-semibold text-slate-500">Mon</div>
            <div class="font-semibold text-slate-500">Tue</div>
            <div class="font-semibold text-slate-500">Wed</div>
            <div class="font-semibold text-slate-500">Thu</div>
            <div class="font-semibold text-slate-500">Fri</div>
            <div class="font-semibold text-slate-500">Sat</div>

            <!-- Empty slots before day 1 -->
            @for($i = 0; $i < $startOfWeek; $i++)
                <div class="h-14 bg-transparent border border-transparent"></div>
            @endfor

            <!-- Days of the month -->
            @for($day = 1; $day <= $daysInMonth; $day++)

            <div
                onclick="openDeadlineModal({{ $day }})"
                class="relative h-14 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-50 transition cursor-pointer flex items-center justify-center font-medium

                {{ $day == now()->day ? 'bg-blue-500 text-white font-bold' : '' }}
            ">

                {{ $day }}

                @if(isset($tasksByDay[$day]))
                    @php
                        $dayTasks = $tasksByDay[$day];
                        $hasOverdue = $dayTasks->contains(fn($t) => $t->isOverdue());
                        $hasDueToday = $dayTasks->contains(fn($t) => $t->due_date->isToday() && $t->status !== 'done');
                        $allCompleted = $dayTasks->every(fn($t) => $t->status === 'done');
                    @endphp

                    @if($allCompleted)
                        <span class="absolute bottom-2 w-2 h-2 bg-green-500 rounded-full"></span>
                    @elseif($hasOverdue)
                        <span class="absolute bottom-2 w-2 h-2 bg-red-500 rounded-full"></span>
                    @elseif($hasDueToday)
                        <span class="absolute bottom-2 w-2 h-2 bg-yellow-500 rounded-full"></span>
                    @else
                        <span class="absolute bottom-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                    @endif
                @endif

            </div>

            @endfor

        </div>

        <!-- Legend -->
        <div class="flex flex-wrap gap-5 text-sm">

            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                Overdue
            </div>

            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                Due Today
            </div>

            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                Upcoming
            </div>

            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                Completed
            </div>

        </div>

    </div>

    <!-- Deadline Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Today -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col">

            <div class="flex items-center gap-2 mb-5">

                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>

                <h2 class="font-bold text-lg text-slate-800">
                    Due Today
                </h2>

            </div>

            <div class="space-y-4 flex-1">

                @forelse($dueTodayTasks as $task)
                <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-200 hover:shadow-sm transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-slate-800">
                                {{ $task->title }}
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ $task->project->name ?? 'No Project' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <span class="inline-block text-xs bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full">
                            Due Today
                        </span>
                        <a href="{{ route('member.tasks.show', $task->id) }}" class="text-xs text-blue-600 font-semibold hover:underline">
                            View Details →
                        </a>
                    </div>
                </div>
                @empty
                <div class="flex items-center justify-center h-24 text-slate-400 text-sm">
                    No tasks due today.
                </div>
                @endforelse

            </div>

        </div>

        <!-- Upcoming -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col">

            <div class="flex items-center gap-2 mb-5">

                <span class="w-3 h-3 rounded-full bg-blue-500"></span>

                <h2 class="font-bold text-lg text-slate-800">
                    Upcoming
                </h2>

            </div>

            <div class="space-y-4 flex-1">

                @forelse($upcomingTasks as $task)
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 hover:shadow-sm transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-slate-800">
                                {{ $task->title }}
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ $task->project->name ?? 'No Project' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <span class="inline-block text-xs bg-blue-200 text-blue-800 px-3 py-1 rounded-full">
                            {{ $task->due_date->format('M d, Y') }}
                        </span>
                        <a href="{{ route('member.tasks.show', $task->id) }}" class="text-xs text-blue-600 font-semibold hover:underline">
                            View Details →
                        </a>
                    </div>
                </div>
                @empty
                <div class="flex items-center justify-center h-24 text-slate-400 text-sm">
                    No upcoming tasks.
                </div>
                @endforelse

            </div>

        </div>

        <!-- Overdue -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col">

            <div class="flex items-center gap-2 mb-5">

                <span class="w-3 h-3 rounded-full bg-red-500"></span>

                <h2 class="font-bold text-lg text-slate-800">
                    Overdue
                </h2>

            </div>

            <div class="space-y-4 flex-1">

                @forelse($overdueTasks as $task)
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 hover:shadow-sm transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-slate-800">
                                {{ $task->title }}
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ $task->project->name ?? 'No Project' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <span class="inline-block text-xs bg-red-200 text-red-800 px-3 py-1 rounded-full">
                            {{ $task->due_date->diffForHumans() }}
                        </span>
                        <a href="{{ route('member.tasks.show', $task->id) }}" class="text-xs text-blue-600 font-semibold hover:underline">
                            View Details →
                        </a>
                    </div>
                </div>
                @empty
                <div class="flex items-center justify-center h-24 text-slate-400 text-sm">
                    No overdue tasks.
                </div>
                @endforelse

            </div>

        </div>

    </div>

</div>

<!-- Modal -->
<div id="deadlineModal"
class="hidden fixed inset-0 bg-black/30 backdrop-blur-md z-50 flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl w-full max-w-md p-8 relative">

        <button onclick="closeDeadlineModal()"
        class="absolute top-4 right-4 text-slate-400 hover:text-red-500">

            ✕

        </button>

        <div class="text-center">

            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>

            <h2 id="deadlineDate"
            class="text-2xl font-bold text-slate-800">
            </h2>

            <p class="text-slate-500 mt-2">
                Task Deadline(s)
            </p>

        </div>

        <!-- Dynamic Tasks List -->
        <div id="deadlineTasksList" class="mt-8 space-y-4 max-h-[300px] overflow-y-auto pr-2">
            <!-- Populated via Javascript -->
        </div>

        <button onclick="closeDeadlineModal()"
        class="w-full mt-6 bg-slate-100 text-slate-700 py-3 rounded-2xl hover:bg-slate-200 font-semibold transition">
            Close
        </button>

    </div>

</div>

<script>
const tasksByDay = @json($tasksByDay);

function openDeadlineModal(day){
    const formattedMonth = "{{ now()->format('F Y') }}";
    document.getElementById('deadlineDate').innerText = day + ' ' + formattedMonth;

    const tasksContainer = document.getElementById('deadlineTasksList');
    tasksContainer.innerHTML = '';

    const dayTasks = tasksByDay[day] || [];

    if (dayTasks.length === 0) {
        tasksContainer.innerHTML = `
            <div class="text-center py-8 text-slate-400">
                <p>No tasks due on this day.</p>
            </div>
        `;
    } else {
        dayTasks.forEach(task => {
            const projectName = task.project ? task.project.name : 'No Project';
            const actionUrl = `/member/tasks/${task.id}/status`;
            
            let statusColor = 'bg-slate-50 border-slate-200';
            if (task.status === 'done') statusColor = 'bg-green-50 border-green-200';
            else if (task.status === 'doing') statusColor = 'bg-blue-50 border-blue-200';
            
            const taskHtml = `
                <div class="p-4 rounded-2xl border ${statusColor}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-slate-800 text-left">${escapeHtml(task.title)}</h3>
                            <p class="text-sm text-slate-500 text-left mt-0.5">${escapeHtml(projectName)}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <form action="${actionUrl}" method="POST" class="flex flex-col gap-1.5">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PATCH">
                            <label class="text-xs text-slate-500 text-left font-medium">Update Status</label>
                            <select name="status" onchange="this.form.submit()" class="w-full rounded-xl border border-slate-200 p-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="todo" ${task.status === 'todo' ? 'selected' : ''}>To Do</option>
                                <option value="doing" ${task.status === 'doing' ? 'selected' : ''}>Doing</option>
                                <option value="done" ${task.status === 'done' ? 'selected' : ''}>Done</option>
                            </select>
                        </form>
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-slate-100 flex justify-end">
                        <a href="/member/tasks/${task.id}" class="text-xs text-blue-600 font-semibold hover:underline">
                            View Details →
                        </a>
                    </div>
                </div>
            `;
            tasksContainer.insertAdjacentHTML('beforeend', taskHtml);
        });
    }

    document.getElementById('deadlineModal')
    .classList.remove('hidden');
}

function closeDeadlineModal(){
    document.getElementById('deadlineModal')
    .classList.add('hidden');
}

function escapeHtml(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>

@endsection
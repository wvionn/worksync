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

            @for($i = 1; $i <= 31; $i++)

            <div
                onclick="openDeadlineModal({{ $i }})"
                class="relative h-14 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-50 transition cursor-pointer flex items-center justify-center font-medium

                {{ $i == now()->day ? 'bg-blue-500 text-white font-bold' : '' }}
            ">

                {{ $i }}

                @if($i == 8)
                <span class="absolute bottom-2 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif

                @if($i == 12)
                <span class="absolute bottom-2 w-2 h-2 bg-yellow-500 rounded-full"></span>
                @endif

                @if($i == 18)
                <span class="absolute bottom-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                @endif

                @if($i == 24)
                <span class="absolute bottom-2 w-2 h-2 bg-green-500 rounded-full"></span>
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
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

            <div class="flex items-center gap-2 mb-5">

                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>

                <h2 class="font-bold text-lg text-slate-800">
                    Due Today
                </h2>

            </div>

            <div class="space-y-4">

                <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-200">

                    <h3 class="font-semibold text-slate-800">
                        Fix Header Responsive
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Portfolio Web
                    </p>

                    <span class="inline-block mt-3 text-xs bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full">
                        Due Today
                    </span>

                </div>

            </div>

        </div>

        <!-- Upcoming -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

            <div class="flex items-center gap-2 mb-5">

                <span class="w-3 h-3 rounded-full bg-blue-500"></span>

                <h2 class="font-bold text-lg text-slate-800">
                    Upcoming
                </h2>

            </div>

            <div class="space-y-4">

                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200">

                    <h3 class="font-semibold text-slate-800">
                        Setup Authentication
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        E-Commerce App
                    </p>

                    <span class="inline-block mt-3 text-xs bg-blue-200 text-blue-800 px-3 py-1 rounded-full">
                        Tomorrow
                    </span>

                </div>

            </div>

        </div>

        <!-- Overdue -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">

            <div class="flex items-center gap-2 mb-5">

                <span class="w-3 h-3 rounded-full bg-red-500"></span>

                <h2 class="font-bold text-lg text-slate-800">
                    Overdue
                </h2>

            </div>

            <div class="space-y-4">

                <div class="p-4 rounded-2xl bg-red-50 border border-red-200">

                    <h3 class="font-semibold text-slate-800">
                        Create Report Module
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Internal Tools
                    </p>

                    <span class="inline-block mt-3 text-xs bg-red-200 text-red-800 px-3 py-1 rounded-full">
                        2 Days Late
                    </span>

                </div>

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
                Task Deadline
            </p>

        </div>

        <div class="mt-8 space-y-4">

            <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-200">

                <h3 class="font-semibold">
                    Fix Header Responsive
                </h3>

                <p class="text-sm text-slate-500">
                    Portfolio Web
                </p>

            </div>

            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200">

                <h3 class="font-semibold">
                    Setup Authentication
                </h3>

                <p class="text-sm text-slate-500">
                    E-Commerce App
                </p>

            </div>

        </div>

        <div class="mt-6">

            <label class="text-sm text-slate-500">
                Status
            </label>

            <select
            class="w-full mt-2 rounded-2xl border border-slate-200 p-3">

                <option>To Do</option>
                <option>Doing</option>
                <option>Done</option>

            </select>

        </div>

        <button
        class="w-full mt-6 bg-red-50 text-red-600 py-3 rounded-2xl hover:bg-red-100 font-semibold">

            Delete Task

        </button>

    </div>

</div>

<script>

function openDeadlineModal(day){

    document.getElementById('deadlineDate').innerText =
    day + ' June 2026';

    document.getElementById('deadlineModal')
    .classList.remove('hidden');
}

function closeDeadlineModal(){

    document.getElementById('deadlineModal')
    .classList.add('hidden');
}

</script>

@endsection
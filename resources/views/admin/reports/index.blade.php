@extends('admin.layouts.app')

@section('page_title', 'Reports')

@section('content')
    <div class="space-y-6">
        <header class="rounded-3xl border border-white/80 bg-white p-6 shadow-sm">
            <h1 class="font-['Manrope'] text-3xl font-extrabold text-slate-900">Reports Overview</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan performa project, distribusi task, dan timeline bulanan.</p>
        </header>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="font-['Manrope'] text-xl font-extrabold text-slate-900">Project Status</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($projectStatusCounts as $status => $count)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2.5">
                            <p class="text-sm font-semibold text-slate-700">{{ ucfirst(str_replace('_', ' ', $status)) }}</p>
                            <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-bold text-slate-700">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data project.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="font-['Manrope'] text-xl font-extrabold text-slate-900">Task Status</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($taskStatusCounts as $status => $count)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2.5">
                            <p class="text-sm font-semibold text-slate-700">{{ ucfirst($status) }}</p>
                            <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-bold text-slate-700">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data task.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="font-['Manrope'] text-xl font-extrabold text-slate-900">Task Priority</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($taskPriorityCounts as $priority => $count)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2.5">
                            <p class="text-sm font-semibold text-slate-700">{{ ucfirst($priority) }}</p>
                            <span class="rounded-full bg-slate-200 px-2.5 py-1 text-xs font-bold text-slate-700">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data prioritas task.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="font-['Manrope'] text-xl font-extrabold text-slate-900">Monthly Task Timeline</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-3 py-3">Month</th>
                            <th class="px-3 py-3">Created Tasks</th>
                            <th class="px-3 py-3">Completed Tasks</th>
                            <th class="px-3 py-3">Completion Ratio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach ($timeline as $item)
                            @php
                                $ratio = $item['created'] > 0
                                    ? (int) round(($item['completed'] / $item['created']) * 100)
                                    : 0;
                            @endphp
                            <tr>
                                <td class="px-3 py-3 font-semibold text-slate-900">{{ $item['month'] }}</td>
                                <td class="px-3 py-3">{{ $item['created'] }}</td>
                                <td class="px-3 py-3">{{ $item['completed'] }}</td>
                                <td class="px-3 py-3">{{ $ratio }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="font-['Manrope'] text-xl font-extrabold text-slate-900">Overdue Focus</h2>
            <div class="mt-4 space-y-3">
                @forelse ($overdueList as $task)
                    <article class="rounded-2xl border border-red-100 bg-red-50/70 px-4 py-3">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <p class="font-semibold text-slate-900">{{ $task->title }}</p>
                            <span class="text-xs font-bold uppercase tracking-[0.12em] text-red-600">{{ ucfirst($task->priority) }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-600">
                            Project: {{ $task->project?->name ?? 'General' }} | Assignee: {{ $task->assignee?->name ?? 'Unassigned' }}
                        </p>
                        <p class="text-xs font-semibold text-red-600">Due {{ optional($task->due_date)->format('d M Y') ?? 'No due date' }}</p>
                    </article>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                        Tidak ada task overdue saat ini.
                    </p>
                @endforelse
            </div>
        </section>
    </div>
@endsection

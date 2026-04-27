@extends('admin.layouts.app')

@section('page_title', 'Dashboard Admin')

@section('content')
    <div class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-3xl border border-white/80 bg-white p-4 shadow-sm">
                <div class="mb-3 flex items-center justify-between">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-100 text-blue-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M3.75 4A2.75 2.75 0 0 0 1 6.75v10.5A2.75 2.75 0 0 0 3.75 20h16.5A2.75 2.75 0 0 0 23 17.25V8.5a2.75 2.75 0 0 0-2.75-2.75h-8.7a1.25 1.25 0 0 1-.96-.45l-.74-.87A2.75 2.75 0 0 0 7.67 4z" />
                        </svg>
                    </span>
                    <span class="rounded-full bg-blue-50 px-2 py-1 text-[11px] font-semibold text-blue-600">+{{ $newProjectsThisWeek }}</span>
                </div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Total Projects</p>
                <p class="mt-1 text-3xl font-extrabold tracking-tight text-slate-900">{{ $totalProjects }}</p>
            </article>

            <article class="rounded-3xl border border-white/80 bg-white p-4 shadow-sm">
                <div class="mb-3 flex items-center justify-between">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M6.75 3h10.5A2.75 2.75 0 0 1 20 5.75v12.5A2.75 2.75 0 0 1 17.25 21H6.75A2.75 2.75 0 0 1 4 18.25V5.75A2.75 2.75 0 0 1 6.75 3m2.2 5.3a1 1 0 1 0-1.4 1.4l1.5 1.5a1 1 0 0 0 1.4 0l3.5-3.5a1 1 0 0 0-1.4-1.4l-2.8 2.8zM8 15a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2z" />
                        </svg>
                    </span>
                    <span class="rounded-full bg-orange-50 px-2 py-1 text-[11px] font-semibold text-orange-600">+{{ $newTasksThisWeek }}</span>
                </div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Total Tasks</p>
                <p class="mt-1 text-3xl font-extrabold tracking-tight text-slate-900">{{ $totalTasks }}</p>
            </article>

            <article class="rounded-3xl border border-white/80 bg-white p-4 shadow-sm">
                <div class="mb-3 flex items-center justify-between">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-sky-100 text-sky-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 22a10 10 0 1 1 10-10 10 10 0 0 1-10 10m4.7-13.29a1 1 0 0 0-1.4-1.42l-4.44 4.4-2.14-2.1a1 1 0 0 0-1.4 1.42l2.84 2.8a1 1 0 0 0 1.4 0z" />
                        </svg>
                    </span>
                    <span class="rounded-full bg-blue-50 px-2 py-1 text-[11px] font-semibold text-blue-700">{{ $completionRate }}% rate</span>
                </div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Tasks Completed</p>
                <p class="mt-1 text-3xl font-extrabold tracking-tight text-slate-900">{{ $completedTasks }}</p>
            </article>

            <article class="rounded-3xl border border-white/80 bg-white p-4 shadow-sm">
                <div class="mb-3 flex items-center justify-between">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-red-100 text-red-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2m-.9 5.4a1.1 1.1 0 1 1 2.2 0l-.3 6.1a.8.8 0 0 1-1.6 0zm.9 10.2a1.3 1.3 0 1 1 1.3-1.3 1.3 1.3 0 0 1-1.3 1.3" />
                        </svg>
                    </span>
                    <span class="rounded-full bg-red-50 px-2 py-1 text-[11px] font-semibold text-red-600">Urgent</span>
                </div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Tasks Overdue</p>
                <p class="mt-1 text-3xl font-extrabold tracking-tight text-slate-900">{{ $overdueTasks }}</p>
            </article>
        </div>

        <div class="grid gap-4 xl:grid-cols-[1.5fr_0.85fr]">
            <section class="space-y-4">
                <article class="rounded-3xl border border-white/80 bg-white p-4 shadow-sm sm:p-5">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <h2 class="font-['Manrope'] text-xl font-extrabold text-slate-900">Active Projects</h2>
                        <a href="{{ route('admin.projects.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800">View All Archive</a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($activeProjects as $project)
                            @php
                                $progress = $project->tasks_count > 0
                                    ? (int) round(($project->completed_tasks_count / $project->tasks_count) * 100)
                                    : (int) $project->progress;
                            @endphp
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-blue-100 text-xs font-bold text-blue-700">
                                            {{ strtoupper(substr($project->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-base font-bold text-slate-900">{{ $project->name }}</p>
                                            <p class="text-xs text-slate-500">
                                                Updated {{ $project->updated_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid gap-0.5 text-xs text-slate-600 sm:text-right">
                                        <p>
                                            <span class="font-semibold">Progress</span>
                                            <span class="font-bold text-blue-700">{{ $progress }}%</span>
                                        </p>
                                        <p>
                                            <span class="font-semibold">Tasks</span>
                                            {{ $project->completed_tasks_count }} / {{ $project->tasks_count }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-2.5 h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-blue-700" style="width: {{ $progress }}%" ></div>
                                </div>

                                <div class="mt-2.5 flex justify-end">
                                    <button type="button" onclick="openProjectDetail('{{ addslashes($project->name) }}', '{{ $progress }}', '{{ $project->completed_tasks_count }}', '{{ $project->tasks_count }}')" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:border-blue-200 hover:bg-blue-50">
                                        View Details
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-xs text-slate-500">
                                Belum ada project aktif. Buat project baru dari panel Quick Actions.
                            </p>
                        @endforelse
                    </div>
                </article>

                <article class="rounded-3xl border border-white/80 bg-white p-4 shadow-sm sm:p-5">
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="font-['Manrope'] text-xl font-extrabold text-slate-900">Latest Activity</h2>
                        <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-blue-700">Open Notifications</a>
                    </div>
                    <div class="space-y-2">
                        @forelse ($recentActivities as $activity)
                            <div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-100 px-3 py-2">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $activity->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $activity->description }}</p>
                                </div>
                                <span class="whitespace-nowrap text-[11px] font-semibold text-slate-400">
                                    {{ ($activity->occurred_at ?? $activity->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-xs text-slate-500">
                                Aktivitas terbaru akan muncul di sini.
                            </p>
                        @endforelse
                    </div>
                </article>
            </section>

            <aside class="space-y-4">
                <article class="rounded-3xl border border-white/80 bg-white p-4 shadow-sm sm:p-5">
                    <h2 class="mb-3 font-['Manrope'] text-xl font-extrabold text-slate-900">Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ route('admin.projects.index') }}" class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 transition hover:border-blue-200 hover:bg-blue-50">
                            <span class="text-sm font-bold">Create New Task</span>
                            <span class="grid h-7 w-7 place-items-center rounded-lg bg-blue-100 text-blue-700">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M16.7 5.3a1 1 0 0 0-1.4-1.42L8.5 10.7 6.7 8.9a1 1 0 1 0-1.4 1.42l2.5 2.5a1 1 0 0 0 1.4 0z" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </article>
            </aside>
        </div>
    </div>

    <div id="projectDetailModal" class="hidden relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeProjectDetail()"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 pointer-events-auto">
                    
                    <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between bg-slate-50">
                        <h3 class="text-lg font-extrabold text-slate-900">Project Insight</h3>
                        <button onclick="closeProjectDetail()" class="text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <h2 id="modalProjectName" class="text-2xl font-extrabold text-slate-900 mb-6">Nama Project</h2>
                        
                        <div class="mb-6 rounded-2xl border border-slate-100 p-4 shadow-sm">
                            <div class="flex justify-between items-end mb-2">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Progress</p>
                                    <p class="text-sm font-semibold text-slate-600 mt-1">Tasks: <span id="modalTaskCount">0 / 0</span></p>
                                </div>
                                <span id="modalProgressText" class="text-xl font-extrabold text-blue-700">0%</span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full bg-slate-200">
                                <div id="modalProgressBar" class="h-full rounded-full bg-gradient-to-r from-blue-500 to-blue-700" style="width: 0%"></div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Assigned Team</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2">
                                    <div class="grid h-8 w-8 place-items-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">RP</div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-800">Rapii</p>
                                        <p class="text-[11px] text-slate-500">Backend Engineer</p>
                                    </div>
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-700">Active</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2">
                                    <div class="grid h-8 w-8 place-items-center rounded-full bg-slate-200 text-xs font-bold text-slate-700">AD</div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-800">Admin</p>
                                        <p class="text-[11px] text-slate-500">Project Manager</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 flex justify-end gap-2">
                        <button onclick="closeProjectDetail()" class="rounded-xl px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-200">Tutup</button>
                        <a href="{{ route('admin.projects.index') }}" class="rounded-xl bg-blue-700 px-5 py-2 text-sm font-bold text-white shadow-md transition hover:bg-blue-800">Kelola Project</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function openProjectDetail(name, progress, completedTasks, totalTasks) {
            // Update nama project
            document.getElementById('modalProjectName').innerText = name;
            
            // Update bar progress & text persentase
            document.getElementById('modalProgressBar').style.width = progress + '%';
            document.getElementById('modalProgressText').innerText = progress + '%';
            
            // Update rasio task (contoh: 2 / 5)
            document.getElementById('modalTaskCount').innerText = completedTasks + ' / ' + totalTasks;
            
            // Tampilkan modal
            document.getElementById('projectDetailModal').classList.remove('hidden');
        }

        function closeProjectDetail() {
            // Sembunyikan modal
            document.getElementById('projectDetailModal').classList.add('hidden');
        }
    </script>
@endsection
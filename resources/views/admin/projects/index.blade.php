@extends('admin.layouts.app')

@section('page_title', 'Task Manager - [Nama Project Lu]')

@section('content')
    <div class="space-y-6">
        
        <div class="mb-6 rounded-3xl bg-blue-700 p-6 text-white shadow-lg">
            <h1 class="text-2xl font-extrabold text-white">Project Workspace</h1>
            <p class="text-sm text-blue-200 mt-1">Fokus ngerjain task buat project ini. Keep it up!</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[350px_1fr]">
            
            <aside>
                <form action="#" method="POST" class="sticky top-6 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm sm:p-6">
                    @csrf
                    <div class="mb-5">
                        <h2 class="text-xl font-extrabold text-slate-900">Add New Task</h2>
                        <p class="text-sm text-slate-500">Catat kerjaan baru untuk project ini.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Task Title</label>
                            <input type="text" name="title" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Contoh: Fix bug login">
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Assign To</label>
                            <select name="assigned_to" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="" disabled selected>Pilih anggota tim...</option>
                                <option value="1">Admin</option>
                                <option value="2">Rapii</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Priority</label>
                                <select name="priority" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Due Date</label>
                                <input type="date" name="due_date" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>

                        <button type="submit" class="mt-2 w-full rounded-xl bg-blue-700 py-3 text-sm font-bold text-white shadow-md transition hover:bg-blue-800">
                            Save Task
                        </button>
                    </div>
                </form>
            </aside>

            <section class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Task List</h2>
                        <p class="text-sm text-slate-500">Daftar semua kerjaan yang sudah dicatat.</p>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <input type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none sm:w-64" placeholder="Cari task...">
                        <button class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Find</button>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4 transition hover:border-blue-200 hover:shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex-1">
                                <div class="mb-1 flex items-center gap-2">
                                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-red-700">Urgent</span>
                                    <h3 class="text-base font-bold text-slate-900">Setup Database Migration</h3>
                                </div>
                                
                                <div class="mt-2 flex items-center gap-4">
                                    <div class="flex items-center gap-1.5 text-sm text-slate-500">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        <span>29 Apr 2026</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="grid h-6 w-6 place-items-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-700">RP</div>
                                        <span class="text-sm font-medium text-slate-700">Rapii</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <button onclick="openModal('editModal')" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Edit</button>
                                <button onclick="openModal('detailModal')" class="rounded-lg bg-blue-700 px-4 py-1.5 text-sm font-bold text-white transition hover:bg-blue-800">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div id="editModal" class="hidden relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('editModal')"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto pointer-events-none">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 pointer-events-auto">
                        
                        <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-lg font-extrabold text-slate-900">Edit Task</h3>
                            <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                        
                        <form action="#" method="POST" class="p-6">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Task Title</label>
                                    <input type="text" name="title" value="Setup Database Migration" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Assign To</label>
                                    <select name="assigned_to" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="1">Admin</option>
                                        <option value="2" selected>Rapii</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Priority</label>
                                        <select name="priority" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="high">High</option>
                                            <option value="urgent" selected>Urgent</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Due Date</label>
                                        <input type="date" name="due_date" value="2026-04-29" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 flex items-center justify-end gap-3">
                                <button type="button" onclick="closeModal('editModal')" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Cancel</button>
                                <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-blue-800">Update Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="detailModal" class="hidden relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('detailModal')"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto pointer-events-none">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 pointer-events-auto">
                        
                        <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-lg font-extrabold text-slate-900">Task Detail</h3>
                            <button onclick="closeModal('detailModal')" class="text-slate-400 hover:text-slate-600">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                        
                        <div class="p-6">
                            <div class="mb-4">
                                <span class="mb-2 inline-block rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider text-red-700">Urgent</span>
                                <h2 class="text-2xl font-extrabold text-slate-900">Setup Database Migration</h2>
                            </div>
                            
                            <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Assignee</p>
                                        <div class="mt-1.5 flex items-center gap-2">
                                            <div class="grid h-6 w-6 place-items-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-700">RP</div>
                                            <p class="text-sm font-semibold text-slate-800">Rapii</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Due Date</p>
                                        <p class="mt-1.5 text-sm font-semibold text-slate-800">29 April 2026</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Created At</p>
                                        <p class="mt-1.5 text-sm font-semibold text-slate-800">23 April 2026, 10:00 AM</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 text-right">
                            <button onclick="closeModal('detailModal')" class="rounded-xl bg-slate-800 px-6 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-slate-900">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
@endsection
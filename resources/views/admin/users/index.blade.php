@extends('admin.layouts.app')

@section('page_title', 'Users')

@section('content')
    <div class="space-y-6">
        <header class="rounded-3xl border border-white/80 bg-white p-6 shadow-sm">
            <h1 class="font-['Manrope'] text-3xl font-extrabold text-slate-900">User Management</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau peran pengguna serta kontribusinya di project dan task.</p>

            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-blue-700">Total Users</p>
                    <p class="mt-2 text-3xl font-extrabold text-blue-900">{{ $users->total() }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-600">Admin</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $adminCount }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-600">Member</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $memberCount }}</p>
                </div>
            </div>
        </header>

        <section class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-bold uppercase tracking-[0.14em] text-slate-500">
                            <th class="px-3 py-3">Name</th>
                            <th class="px-3 py-3">Email</th>
                            <th class="px-3 py-3">Role</th>
                            <th class="px-3 py-3">Owned Projects</th>
                            <th class="px-3 py-3">Assigned Tasks</th>
                            <th class="px-3 py-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-3 py-3 font-semibold text-slate-900">{{ $user->name }}</td>
                                <td class="px-3 py-3">{{ $user->email }}</td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3">{{ $user->owned_projects_count }}</td>
                                <td class="px-3 py-3">{{ $user->assigned_tasks_count }}</td>
                                <td class="px-3 py-3">{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-6 text-center text-sm text-slate-500">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </section>
    </div>
@endsection

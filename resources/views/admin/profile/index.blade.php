@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
        <p class="text-gray-600 mt-1">Your admin account overview</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <section class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm lg:col-span-1">
            <div class="flex flex-col items-center text-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-600 text-2xl font-bold text-white">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <span class="mt-3 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="mt-6 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.settings.index') }}" class="btn-primary block text-center">Edit Profile & Settings</a>
            </div>
        </section>

        <section class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-bold text-gray-900">Account Details</h2>
            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Name</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->email }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Joined</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->created_at?->format('M d, Y') ?? '-' }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Email Status</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</p>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-100 pt-5">
                <h3 class="text-sm font-bold text-gray-900">Recent Activity</h3>
                <div class="mt-3 space-y-3">
                    @forelse($recentActivities as $activity)
                        <div class="rounded-lg border border-gray-100 p-3">
                            <p class="text-sm font-semibold text-gray-900">{{ $activity->title }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $activity->description }}</p>
                        </div>
                    @empty
                        <p class="py-4 text-sm italic text-gray-400">No recent activity.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

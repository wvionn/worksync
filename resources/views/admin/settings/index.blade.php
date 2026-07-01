@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-600 mt-1">Manage your admin account and application preferences</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <aside class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <nav class="space-y-1">
                <a href="#profile" class="block rounded-lg bg-blue-50 px-4 py-2 font-medium text-blue-700">Profile</a>
                <a href="#security" class="block rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50">Security</a>
                <a href="#notifications" class="block rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50">Notifications</a>
                <a href="#system" class="block rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-50">System</a>
            </nav>
        </aside>

        <div class="space-y-6 lg:col-span-3">
            <section id="profile" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                    <p class="mt-1 text-sm text-gray-500">Update your admin name and email address.</p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2 {{ $errors->has('name') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }}"
                            required
                        >
                        @error('name')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2 {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }}"
                            required
                        >
                        @error('email')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-5">
                        <button type="submit" class="btn-primary">Save Profile</button>
                    </div>
                </form>
            </section>

            <section id="security" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Security</h2>
                    <p class="mt-1 text-sm text-gray-500">Change your password. Leave fields empty if you only want to update profile data.</p>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="name" value="{{ old('name', $user->name) }}">
                    <input type="hidden" name="email" value="{{ old('email', $user->email) }}">

                    <div>
                        <label for="current_password" class="mb-2 block text-sm font-semibold text-gray-700">Current Password</label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            autocomplete="current-password"
                            class="w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2 {{ $errors->has('current_password') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }}"
                        >
                        @error('current_password')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-gray-700">New Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                                class="w-full rounded-lg border px-4 py-2 text-sm focus:outline-none focus:ring-2 {{ $errors->has('password') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }}"
                            >
                            @error('password')
                                <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-gray-700">Confirm New Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-5">
                        <button type="submit" class="btn-primary">Update Password</button>
                    </div>
                </form>
            </section>

            <section id="notifications" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900">Notifications</h2>
                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Task activity alerts</p>
                        <p class="mt-1 text-sm text-gray-500">New task comments, attachments, blockers, and review updates are shown in Notifications.</p>
                    </div>
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Unread counter</p>
                        <p class="mt-1 text-sm text-gray-500">The top-bar notification badge counts unread activity records.</p>
                    </div>
                </div>
            </section>

            <section id="system" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900">System</h2>
                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Timezone</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ config('app.timezone') }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">App Name</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ config('app.name') }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Environment</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ app()->environment() }}</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

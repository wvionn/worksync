@extends('layouts.member')

@section('header_title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-600 mt-1">Manage your account information and preferences</p>
    </div>

    <!-- Alert Success -->
    @if(session('success_message'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success_message') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">×</button>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Sidebar Navigation -->
        <div class="bg-white rounded-2xl border border-gray-150 p-4 shadow-sm h-fit">
            <nav class="space-y-1">
                <a href="#profile" class="flex items-center gap-3 px-4 py-2.5 bg-teal-50 text-teal-700 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile Settings
                </a>
                <a href="#security" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Security & Password
                </a>
            </nav>
        </div>

        <!-- Settings Form Content -->
        <div class="md:col-span-2 space-y-6">
            <!-- General profile settings card -->
            <div id="profile" class="bg-white rounded-2xl border border-gray-150 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Profile Information</h2>
                        <p class="text-xs text-gray-500">Update your account's profile name and email address</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('member.settings.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Full Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full pl-11 pr-4 py-2.5 border @error('name') border-red-300 focus:ring-red-500 @else border-gray-300 focus:ring-teal-500 focus:border-teal-500 @enderror rounded-xl focus:outline-none focus:ring-2 focus:border-transparent text-sm transition-all"
                                   required>
                        </div>
                        @error('name')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full pl-11 pr-4 py-2.5 border @error('email') border-red-300 focus:ring-red-500 @else border-gray-300 focus:ring-teal-500 focus:border-teal-500 @enderror rounded-xl focus:outline-none focus:ring-2 focus:border-transparent text-sm transition-all"
                                   required>
                        </div>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-semibold text-sm shadow-sm">
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Card -->
            <div id="security" class="bg-white rounded-2xl border border-gray-150 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Security Settings</h2>
                        <p class="text-xs text-gray-500">Change password to ensure your account security</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('member.settings.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden field to keep profile data synchronized during validation fallback -->
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Current Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </span>
                            <input type="password" id="current_password" name="current_password" placeholder="••••••••"
                                   class="w-full pl-11 pr-4 py-2.5 border @error('current_password') border-red-300 focus:ring-red-500 @else border-gray-300 focus:ring-teal-500 focus:border-teal-500 @enderror rounded-xl focus:outline-none focus:ring-2 focus:border-transparent text-sm transition-all">
                        </div>
                        @error('current_password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">New Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" placeholder="••••••••"
                                   class="w-full pl-11 pr-4 py-2.5 border @error('password') border-red-300 focus:ring-red-500 @else border-gray-300 focus:ring-teal-500 focus:border-teal-500 @enderror rounded-xl focus:outline-none focus:ring-2 focus:border-transparent text-sm transition-all">
                        </div>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Confirm New Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••"
                                   class="w-full pl-11 pr-4 py-2.5 border border-gray-300 focus:ring-teal-500 focus:border-teal-500 rounded-xl focus:outline-none focus:ring-2 focus:border-transparent text-sm transition-all">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-semibold text-sm shadow-sm">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

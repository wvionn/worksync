@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-600 mt-1">Manage your application preferences</p>
    </div>

    <!-- Settings Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar -->
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <nav class="space-y-1">
                <a href="#" class="block px-4 py-2 bg-blue-50 text-blue-600 rounded-lg font-medium">General</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Notifications</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Security</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Integrations</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Billing</a>
            </nav>
        </div>

        <!-- Content -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-6">General Profile Settings</h2>
            
            <!-- Success Message -->
            @if(session('success_message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between transition-all">
                <span>{{ session('success_message') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">×</button>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Administrator Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full px-4 py-2 border @error('name') border-red-300 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror rounded-lg focus:outline-none focus:ring-2 focus:border-transparent text-sm"
                           required>
                    @error('name')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Administrator Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full px-4 py-2 border @error('email') border-red-300 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror rounded-lg focus:outline-none focus:ring-2 focus:border-transparent text-sm"
                           required>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Timezone (Mock, but looks nice) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Timezone</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" disabled>
                        <option>Asia/Jakarta (GMT+07:00)</option>
                        <option>UTC</option>
                        <option>America/New_York</option>
                        <option>Europe/London</option>
                        <option>Asia/Tokyo</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">To change system timezone, please update the application configuration.</p>
                </div>

                <!-- Save Button -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

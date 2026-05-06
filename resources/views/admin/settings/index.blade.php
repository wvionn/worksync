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
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">General Settings</h2>
            
            <div class="space-y-6">
                <!-- Application Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                    <input type="text" value="Admin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Timezone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>UTC</option>
                        <option>America/New_York</option>
                        <option>Europe/London</option>
                        <option>Asia/Tokyo</option>
                    </select>
                </div>

                <!-- Language -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>English</option>
                        <option>Spanish</option>
                        <option>French</option>
                        <option>German</option>
                    </select>
                </div>

                <!-- Save Button -->
                <div class="pt-4">
                    <button class="btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

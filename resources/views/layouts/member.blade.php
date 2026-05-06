<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WorkSync') }} - Member</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold text-gray-900">WorkSync</h1>
                                <p class="text-xs text-gray-500">Member Dashboard</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-4">
                        <!-- Profile Dropdown -->
                        <div class="relative">
                            <button id="profileDropdownBtn" class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Member</p>
                                </div>
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <a href="{{ route('member.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Success/Error Messages -->
            @if(session('success_message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success_message') }}
            </div>
            @endif

            @if(session('error_message'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error_message') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- JavaScript for Interactions -->
    <script>
        // Profile Dropdown Toggle
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        if (profileDropdownBtn && profileDropdown) {
            profileDropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!profileDropdownBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });

            // Close dropdown on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    profileDropdown.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>

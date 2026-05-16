<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WorkSync') }} - Member</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-teal-800 text-white flex flex-col shadow-xl">
        <div class="h-16 flex items-center px-6 border-b border-teal-700">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 w-full hover:opacity-80 transition-opacity" title="Go to Dashboard">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-wide text-white">WorkSync</span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('member.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.dashboard') ? 'bg-teal-700 font-medium' : 'hover:bg-teal-700/50 text-teal-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            
            <!-- Projects -->
            <a href="{{ route('member.projects') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.projects') ? 'bg-teal-700 font-medium' : 'hover:bg-teal-700/50 text-teal-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Projects
            </a>

            <!-- Tasks / Deadlines -->
            <a href="{{ route('member.deadlines') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.deadlines') ? 'bg-teal-700 font-medium' : 'hover:bg-teal-700/50 text-teal-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Deadlines
            </a>

            <!-- Team Chat -->
            <a href="{{ route('member.chat') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.chat') ? 'bg-teal-700 font-medium' : 'hover:bg-teal-700/50 text-teal-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                Team Chat
            </a>
            
            <div class="pt-4 mt-4 border-t border-teal-700/50"></div>

            <!-- Hubungi Admin -->
            <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20ingin%20bertanya%20seputar%20jobdesk/task%20saya" target="_blank" class="flex items-center px-3 py-2.5 rounded-xl transition-colors hover:bg-teal-700/50 text-teal-50">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                Hubungi Admin
            </a>

            <!-- Profile -->
            <a href="{{ route('member.profile') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.profile') ? 'bg-teal-700 font-medium' : 'hover:bg-teal-700/50 text-teal-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profile
            </a>

            <!-- Settings -->
            <a href="{{ route('member.settings') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('member.settings') ? 'bg-teal-700 font-medium' : 'hover:bg-teal-700/50 text-teal-50' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
        </nav>
        
        <!-- Bottom profile summary in sidebar -->
        <div class="p-4 border-t border-teal-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-teal-200 truncate">Member</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-teal-200 hover:text-white rounded-lg hover:bg-teal-700 transition-colors" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 z-10 shadow-sm">
            <!-- Left side -->
            <div class="flex items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    @yield('header_title', 'Dashboard')
                </h2>
            </div>
            
            <!-- Right Side Notifications & Profile -->
            <div class="flex items-center gap-5">
                <!-- Notifications -->
                <button class="relative p-2 text-gray-400 hover:text-teal-600 transition-colors rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                </button>
                
                <!-- Simple Profile -->
                <a href="{{ route('member.profile') }}" class="flex items-center gap-2 cursor-pointer">
                    <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center text-teal-700 font-semibold border border-teal-200 shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50/50 p-6">
            <!-- Success/Error Messages -->
            @if(session('success_message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success_message') }}
            </div>
            @endif

            @if(session('error_message'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error_message') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>

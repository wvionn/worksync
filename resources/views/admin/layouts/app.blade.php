<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WorkSync') }} | @yield('page_title', 'Admin Workspace')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-theme min-h-screen bg-[#eef1f8] text-slate-900">
        @php
            $menuItems = [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'M3 4.75A1.75 1.75 0 0 1 4.75 3h6.5A1.75 1.75 0 0 1 13 4.75v6.5A1.75 1.75 0 0 1 11.25 13h-6.5A1.75 1.75 0 0 1 3 11.25zm9 0A1.75 1.75 0 0 1 13.75 3h5.5A1.75 1.75 0 0 1 21 4.75v3.5A1.75 1.75 0 0 1 19.25 10h-5.5A1.75 1.75 0 0 1 12 8.25zm1.75 6.25A1.75 1.75 0 0 0 12 12.75v6.5A1.75 1.75 0 0 0 13.75 21h5.5A1.75 1.75 0 0 0 21 19.25v-6.5A1.75 1.75 0 0 0 19.25 11zm-9 1A1.75 1.75 0 0 0 3 13.75v5.5A1.75 1.75 0 0 0 4.75 21h6.5A1.75 1.75 0 0 0 13 19.25v-5.5A1.75 1.75 0 0 0 11.25 12z'],
                ['label' => 'Projects', 'route' => 'admin.projects.index', 'active' => 'admin.projects.*', 'icon' => 'M3.75 4A2.75 2.75 0 0 0 1 6.75v10.5A2.75 2.75 0 0 0 3.75 20h16.5A2.75 2.75 0 0 0 23 17.25V8.5a2.75 2.75 0 0 0-2.75-2.75h-8.7a1.25 1.25 0 0 1-.96-.45l-.74-.87A2.75 2.75 0 0 0 7.67 4z'],
                ['label' => 'Users', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'M12 4.5a4 4 0 1 1 0 8 4 4 0 0 1 0-8m-7 14a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5 1 1 0 1 1-2 0 3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3 1 1 0 1 1-2 0m14.4-7.5a3.1 3.1 0 1 1 .001 6.2 1 1 0 0 1 0-2 1.1 1.1 0 1 0 0-2.2 1 1 0 1 1 0-2m-.4 10a1 1 0 0 1 1-1h1a2 2 0 1 0 0-4 1 1 0 1 1 0-2 4 4 0 0 1 0 8h-1a1 1 0 0 1-1-1'],
                ['label' => 'Reports', 'route' => 'admin.reports.index', 'active' => 'admin.reports.*', 'icon' => 'M4.75 3h14.5A1.75 1.75 0 0 1 21 4.75v14.5A1.75 1.75 0 0 1 19.25 21H4.75A1.75 1.75 0 0 1 3 19.25V4.75A1.75 1.75 0 0 1 4.75 3M7 15.25a1 1 0 0 0 2 0v-3.5a1 1 0 1 0-2 0zm4 0a1 1 0 0 0 2 0v-6a1 1 0 1 0-2 0zm4 0a1 1 0 1 0 2 0v-2a1 1 0 1 0-2 0z'],
                ['label' => 'Notifications', 'route' => 'admin.notifications.index', 'active' => 'admin.notifications.*', 'icon' => 'M12 2a5 5 0 0 0-5 5v2.06c0 .61-.2 1.2-.56 1.69l-1.3 1.72A2 2 0 0 0 6.72 16h10.56a2 2 0 0 0 1.58-3.53l-1.3-1.72A2.8 2.8 0 0 1 17 9.06V7a5 5 0 0 0-5-5m0 20a3 3 0 0 0 2.83-2h-5.66A3 3 0 0 0 12 22'],
                ['label' => 'Settings', 'route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'icon' => 'M19.14 12.94a7.2 7.2 0 0 0 .05-.94 7.2 7.2 0 0 0-.05-.94l2.03-1.58a.58.58 0 0 0 .14-.73l-1.92-3.32a.58.58 0 0 0-.7-.25l-2.39.96a7.3 7.3 0 0 0-1.63-.94l-.36-2.54A.57.57 0 0 0 13.74 2h-3.48a.57.57 0 0 0-.57.48L9.33 5.02a7.3 7.3 0 0 0-1.63.94l-2.39-.96a.58.58 0 0 0-.7.25L2.69 8.57a.58.58 0 0 0 .14.73l2.03 1.58a7.2 7.2 0 0 0-.05.94c0 .32.02.64.05.94l-2.03 1.58a.58.58 0 0 0-.14.73l1.92 3.32a.58.58 0 0 0 .7.25l2.39-.96c.5.39 1.05.7 1.63.94l.36 2.54a.57.57 0 0 0 .57.48h3.48a.57.57 0 0 0 .57-.48l.36-2.54c.58-.24 1.13-.55 1.63-.94l2.39.96a.58.58 0 0 0 .7-.25l1.92-3.32a.58.58 0 0 0-.14-.73zm-7.14 2.56a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7'],
            ];

            $unreadNotificationsCount = \App\Models\Activity::query()
                ->where('is_read', false)
                ->count();
        @endphp

        <div x-data="{ sidebarOpen: false }" class="relative min-h-screen bg-[radial-gradient(circle_at_top_right,_#dce4ff_0,_#eef1f8_52%)]">
            <div class="mx-auto flex min-h-screen w-full max-w-[1680px]">
                <aside
                    class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-200/80 bg-white/95 p-5 backdrop-blur transition-transform duration-300 lg:static lg:translate-x-0"
                    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                >
                    <div class="flex items-center gap-3">
                        <div class="grid h-11 w-11 place-items-center rounded-xl bg-[#1652c5] text-white shadow-lg shadow-blue-200">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M5 3a2 2 0 0 0-2 2v6h8V3zm10 0v8h6V5a2 2 0 0 0-2-2zM3 13v6a2 2 0 0 0 2 2h8v-8zm12 0v8h4a2 2 0 0 0 2-2v-6z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-['Manrope'] text-lg font-extrabold tracking-tight text-slate-900">WorkSync</p>
                            <p class="text-[11px] uppercase tracking-[0.2em] text-slate-400">Admin Workspace</p>
                        </div>
                    </div>

                    <nav class="mt-8 space-y-1.5">
                        @foreach ($menuItems as $item)
                            <a
                                href="{{ route($item['route']) }}"
                                class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ request()->routeIs($item['active']) ? 'bg-[#1652c5] text-white shadow-md shadow-blue-300/50' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                            >
                                <svg class="h-5 w-5 {{ request()->routeIs($item['active']) ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="{{ $item['icon'] }}" />
                                </svg>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </aside>

                <div
                    x-show="sidebarOpen"
                    x-transition.opacity
                    class="fixed inset-0 z-30 bg-slate-900/30 lg:hidden"
                    @click="sidebarOpen = false"
                ></div>

                <div class="flex min-h-screen flex-1 flex-col lg:pl-0">
                    <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-[#eef1f8]/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden"
                                    @click="sidebarOpen = true"
                                >
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                                    </svg>
                                </button>

                                <form action="{{ route('admin.tasks.index') }}" method="GET" class="relative hidden min-[560px]:block">
                                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 1 0 3.47 9.77l3.63 3.63a1 1 0 0 0 1.4-1.42l-3.62-3.62A5.5 5.5 0 0 0 8.5 3M5 8.5a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" clip-rule="evenodd" />
                                    </svg>
                                    <input
                                        type="text"
                                        name="search"
                                        placeholder="Search tasks..."
                                        class="w-[300px] rounded-2xl border-slate-200 bg-white/90 py-2.5 pl-10 pr-4 text-sm text-slate-700 shadow-sm focus:border-blue-400 focus:ring-blue-400"
                                    >
                                </form>
                            </div>

                            <div class="flex items-center gap-3">
                                <a
                                    href="{{ route('admin.notifications.index') }}"
                                    class="relative grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:text-blue-600"
                                >
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M12 2a5 5 0 0 0-5 5v2.06c0 .61-.2 1.2-.56 1.69l-1.3 1.72A2 2 0 0 0 6.72 16h10.56a2 2 0 0 0 1.58-3.53l-1.3-1.72A2.8 2.8 0 0 1 17 9.06V7a5 5 0 0 0-5-5m0 20a3 3 0 0 0 2.83-2h-5.66A3 3 0 0 0 12 22" />
                                    </svg>
                                    @if ($unreadNotificationsCount > 0)
                                        <span class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                                            {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                                        </span>
                                    @endif
                                </a>

                                <div class="hidden items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm sm:flex">
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-500">{{ ucfirst(auth()->user()->role) }}</p>
                                    </div>
                                    <div class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-xs font-bold text-white">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                </div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:text-blue-600"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                        @if (session('success_message'))
                            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                                {{ session('success_message') }}
                            </div>
                        @endif

                        @if (session('error_message'))
                            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                                {{ session('error_message') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                                <p class="mb-2 font-semibold">Periksa kembali input Anda:</p>
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>

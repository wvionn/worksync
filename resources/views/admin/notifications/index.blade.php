@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600 mt-1">Stay updated with recent activities</p>
        </div>
        
        @if(($unreadCount ?? 0) > 0)
        <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                Mark all as read
            </button>
        </form>
        @else
        <button class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-sm font-semibold cursor-not-allowed" disabled>
            Mark all as read
        </button>
        @endif
    </div>

    <!-- Success Message -->
    @if(session('success_message'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between transition-all">
        <span>{{ session('success_message') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">×</button>
    </div>
    @endif

    <!-- Notifications List -->
    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-200 shadow-sm overflow-hidden">
        @forelse($activities ?? [] as $activity)
        <div @if(!$activity->is_read) onclick="document.getElementById('mark-read-{{ $activity->id }}').submit();" @endif 
             class="p-4 hover:bg-gray-50 transition-colors {{ $activity->is_read ? 'opacity-75' : 'bg-blue-50/50 cursor-pointer font-medium' }}">
            
            @if(!$activity->is_read)
            <form id="mark-read-{{ $activity->id }}" method="POST" action="{{ route('admin.notifications.markRead', $activity) }}" class="hidden">
                @csrf
                @method('PATCH')
            </form>
            @endif

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 {{ $activity->is_read ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-600' }} rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $activity->title }}</p>
                        @if(!$activity->is_read)
                        <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase rounded bg-blue-100 text-blue-700 flex-shrink-0">
                            New
                        </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $activity->description }}</p>
                    <div class="flex items-center gap-3 mt-2 text-[10px] text-gray-400">
                        <p class="font-medium">{{ $activity->occurred_at ? $activity->occurred_at->diffForHumans() : $activity->created_at->diffForHumans() }}</p>
                        @if($activity->user)
                        <span class="flex items-center gap-1">
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <span>by {{ $activity->user->name }}</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="text-sm font-semibold text-gray-900">No notifications</p>
            <p class="text-xs text-gray-500 mt-1">You're all caught up!</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>
@endsection

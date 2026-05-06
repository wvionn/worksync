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
        <button class="btn-secondary">Mark all as read</button>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-200">
        @forelse($activities ?? [] as $activity)
        <div class="p-4 hover:bg-gray-50 cursor-pointer {{ $activity->is_read ? '' : 'bg-blue-50' }}">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ $activity->title }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <p class="text-xs text-gray-400">{{ $activity->occurred_at ? $activity->occurred_at->diffForHumans() : $activity->created_at->diffForHumans() }}</p>
                        @if($activity->user)
                        <span class="text-xs text-gray-500">by {{ $activity->user->name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="text-lg font-medium">No notifications</p>
            <p class="text-sm mt-1">You're all caught up!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

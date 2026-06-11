@extends('layouts.admin')

@section('title', 'Chat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Team Chat</h1>
            <p class="text-gray-600 mt-1">Group communication and direct messages with team members</p>
        </div>
    </div>

    <!-- Team Chat Component -->
    <livewire:admin.team-chat-component key="admin-team-chat" />
</div>
@endsection

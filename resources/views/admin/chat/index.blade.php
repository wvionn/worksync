@extends('layouts.admin')

@section('title', 'Chat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Chat</h1>
            <p class="text-gray-600 mt-1">Communicate with your team members</p>
        </div>
    </div>

    <!-- Livewire Chat Component -->
    <livewire:admin.chat-component />
</div>
@endsection

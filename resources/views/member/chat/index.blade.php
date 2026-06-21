@extends('layouts.member')

@section('header_title', 'Team Chat')

@section('content')

    <div class="p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-blue-600">
                Team Chat
            </h1>
            <p class="text-gray-500 mt-2">
                Diskusi group project dan komunikasi dengan team members
            </p>
        </div>

        <!-- Team Chat Component (Group + Members) -->
        <livewire:member.team-chat-component />
    </div>

@endsection
@extends('layouts.member')

@section('header_title', 'Hubungi Admin')

@section('content')

    <div class="p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-blue-600">
                Hubungi Admin
            </h1>
            <p class="text-gray-500 mt-2">
                Komunikasi langsung dengan admin untuk bantuan dan pertanyaan
            </p>
        </div>

        <!-- Direct Admin Chat Component -->
        <livewire:member.chat-component />
    </div>

@endsection
<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('admin/dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

Route::view('member/dashboard', 'member.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('member.dashboard');

require __DIR__.'/auth.php';

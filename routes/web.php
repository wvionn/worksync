<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
Route::view('/', 'welcome');
// Route untuk Admin Dashboard (Pastikan sudah login)
Route::middleware(['auth'])->group(function () {
    Volt::route('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

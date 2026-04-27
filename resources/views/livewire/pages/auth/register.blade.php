<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col h-full bg-white text-black">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Daftar Akun</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">
            Bergabunglah dengan <span class="text-[#1d72dd] font-bold">#Worksync</span>
        </p>
    </div>

    <form wire:submit="register" class="flex-grow">
        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold text-black mb-2">
                {{ __('Nama Lengkap') }} <span class="text-red-500">*</span>
            </label>
            <input wire:model="name" id="name" 
                   type="text" name="name" 
                   placeholder="Masukkan Nama Lengkap" 
                   class="block w-full px-4 py-3 bg-white text-black border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1d72dd] focus:border-[#1d72dd] outline-none transition duration-200"
                   required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-semibold text-black mb-2">
                {{ __('Alamat Email') }} <span class="text-red-500">*</span>
            </label>
            <input wire:model="email" id="email" 
                   type="email" name="email" 
                   placeholder="Masukkan Alamat Email" 
                   class="block w-full px-4 py-3 bg-white text-black border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1d72dd] focus:border-[#1d72dd] outline-none transition duration-200"
                   required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-6 relative" x-data="{ showPassword: false }">
            <label for="password" class="block text-sm font-semibold text-black mb-2">
                {{ __('Password') }} <span class="text-red-500">*</span>
            </label>

            <div class="relative">
                <input wire:model="password" id="password" 
                       x-bind:type="showPassword ? 'text' : 'password'"
                       name="password"
                       placeholder="**********"
                       class="block w-full px-4 py-3 bg-white text-black border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1d72dd] focus:border-[#1d72dd] outline-none transition duration-200"
                       required autocomplete="new-password" />
                
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-semibold text-black mb-2">
                {{ __('Konfirmasi Password') }} <span class="text-red-500">*</span>
            </label>
            <input wire:model="password_confirmation" id="password_confirmation" 
                   type="password" name="password_confirmation" 
                   placeholder="**********" 
                   class="block w-full px-4 py-3 bg-white text-black border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1d72dd] focus:border-[#1d72dd] outline-none transition duration-200"
                   required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-[#1d72dd] hover:underline font-bold" href="{{ route('login') }}" wire:navigate>
                {{ __('Sudah terdaftar? Login di sini') }}
            </a>
        </div>

        <div class="mt-10">
            <button type="submit" class="w-full py-4 bg-[#1d72dd] hover:bg-[#155cb8] text-white font-bold rounded-full shadow-lg transition duration-200 uppercase tracking-widest text-sm">
                {{ __('Register') }}
            </button>
        </div>

        <div class="mt-8 flex flex-col items-center">
            <div class="flex items-center justify-center mb-2">
                <div class="w-10 h-10 bg-[#1d72dd] rounded-xl flex items-center justify-center text-white shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">Powered by kelompok 5</p>
        </div>
    </form>

    <div class="mt-12 flex justify-between items-center text-[10px] text-gray-400 font-bold border-t border-gray-100 pt-6">
        <span>@ 2026 WorkSync ALL RIGHTS RESERVED.</span>
        <div class="space-x-4 flex">
            <a href="#" class="hover:text-[#1d72dd] transition duration-200">PRIVACY POLICY</a>
            <a href="#" class="hover:text-[#1d72dd] transition duration-200">TERMS OF SERVICE</a>
            <a href="#" class="hover:text-[#1d72dd] transition duration-200">SUPPORT</a>
        </div>
    </div>
</div>



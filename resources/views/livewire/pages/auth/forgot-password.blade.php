<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="flex flex-col h-full bg-white text-black">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Lupa Password</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">
            Atur ulang akses akun <span class="text-[#1d72dd] font-bold">#Worksync</span> Anda
        </p>
    </div>

    <div class="mb-6 text-sm text-gray-600 font-medium">
        {{ __('Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex-grow">
        <!-- Email Address -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-semibold text-black mb-2">
                {{ __('Alamat Email') }} <span class="text-red-500">*</span>
            </label>
            <input wire:model="email" id="email" 
                   type="email" name="email" 
                   placeholder="Masukkan Alamat Email" 
                   class="block w-full px-4 py-3 bg-white text-black border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1d72dd] focus:border-[#1d72dd] outline-none transition duration-200"
                   required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-[#1d72dd] hover:underline font-bold" href="{{ route('login') }}" wire:navigate>
                {{ __('Kembali ke Login') }}
            </a>
        </div>

        <div class="mt-10">
            <button type="submit" class="w-full py-4 bg-[#1d72dd] hover:bg-[#155cb8] text-white font-bold rounded-full shadow-lg transition duration-200 uppercase tracking-widest text-sm">
                {{ __('Kirim Link Reset') }}
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



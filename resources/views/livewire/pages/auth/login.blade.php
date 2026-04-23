<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<style>
    .header {
        margin-bottom: 2rem;
    }
    .header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #000000;
    }
    .header p {
        font-size: 0.875rem;
        color: #6B7280;
    }
    .header span.brand-tag {
        color: #1d72dd;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #374151;
    }
    .form-group label .required {
        color: #ef4444;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-wrapper input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 6px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s ease;
        outline: none;
        color: #111827;
    }
    .input-wrapper input::placeholder {
        color: #9CA3AF;
    }
    .input-wrapper input:focus {
        border-color: #1d72dd;
        box-shadow: 0 0 0 3px rgba(29, 114, 221, 0.15);
    }
    .input-wrapper input.input-error-border {
        border-color: #ef4444;
    }

    .toggle-password {
        position: absolute;
        right: 1rem;
        cursor: pointer;
        color: #9CA3AF;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        padding: 0;
    }
    .toggle-password:hover {
        color: #4B5563;
    }

    .form-error {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.35rem;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        margin-top: 1rem;
    }

    /* Kustomisasi Checkbox */
    .checkbox-container {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 0.75rem;
        color: #6B7280;
        font-weight: 500;
    }
    .checkbox-container input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    .checkmark {
        height: 14px;
        width: 14px;
        min-width: 14px;
        background-color: #fff;
        border: 1px solid #D1D5DB;
        border-radius: 3px;
        margin-right: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.2s;
    }
    .checkbox-container input:checked ~ .checkmark {
        background-color: #1d72dd;
        border-color: #1d72dd;
    }
    .checkmark::after {
        content: "";
        display: none;
        width: 3px;
        height: 6px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        margin-bottom: 2px;
    }
    .checkbox-container input:checked ~ .checkmark::after {
        display: block;
    }
    .checkbox-container:hover input ~ .checkmark {
        border-color: #1d72dd;
    }

    .forgot-password {
        font-size: 0.75rem;
        color: #1d72dd;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .forgot-password:hover {
        color: #155cb8;
        text-decoration: underline;
    }

    .btn-login {
        width: 100%;
        padding: 0.75rem;
        background-color: #1d72dd;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        letter-spacing: 0.5px;
        font-family: 'Inter', sans-serif;
    }
    .btn-login:hover {
        background-color: #155cb8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(29, 114, 221, 0.2);
    }
    .btn-login:active {
        transform: translateY(0);
    }

    .footer {
        margin-top: 4rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-align: center;
    }
    .footer p {
        font-size: 0.7rem;
        color: #9CA3AF;
    }
    .footer .logo-svg {
        width: 48px;
        height: 48px;
    }
</style>

<div>
    <!-- Header -->
    <div class="header">
        <h1>Log In Akun</h1>
        <p>Hi, Selamat Datang <span class="brand-tag">#Worksync</span></p>
    </div>

    <!-- Session Status (flash message) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" id="loginForm">

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email <span class="required">*</span></label>
            <div class="input-wrapper">
                <input
                    wire:model="form.email"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Masukkan Email"
                    required
                    autofocus
                    autocomplete="username"
                    class="{{ $errors->has('form.email') ? 'input-error-border' : '' }}"
                >
            </div>
            @error('form.email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password <span class="required">*</span></label>
            <div class="input-wrapper">
                <input
                    wire:model="form.password"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••••"
                    required
                    autocomplete="current-password"
                    class="{{ $errors->has('form.password') ? 'input-error-border' : '' }}"
                >
                <!-- Tombol Toggle Password -->
                <button type="button" class="toggle-password" id="togglePasswordBtn" title="Tampilkan/Sembunyikan Password">
                    <!-- Ikon Mata Terbuka (default: password tersembunyi) -->
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <!-- Ikon Mata Tertutup (tersembunyi) -->
                    <svg id="eyeClosed" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                </button>
            </div>
            @error('form.password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Opsi: Ingat Saya & Lupa Password -->
        <div class="form-options">
            <label class="checkbox-container">
                <input wire:model="form.remember" type="checkbox" id="rememberMe" name="remember">
                <span class="checkmark"></span>
                Ingat Saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password" wire:navigate>
                    Lupa Password
                </a>
            @endif
        </div>

        <!-- Tombol Login -->
        <button type="submit" class="btn-login">
            LOGIN
        </button>

    </form>

    <!-- Footer -->
    <div class="footer">
        <svg class="logo-svg" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="shieldGrad" x1="32" y1="4" x2="32" y2="60" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#141E30" />
                    <stop offset="1" stop-color="#243B55" />
                </linearGradient>
                <linearGradient id="innerShieldGrad" x1="32" y1="8" x2="32" y2="55.5" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#1d72dd" />
                    <stop offset="1" stop-color="#0e4b99" />
                </linearGradient>
            </defs>
            <path d="M32 4L8 16V30.5C8 44.5 18 56 32 60C46 56 56 44.5 56 30.5V16L32 4Z" fill="url(#shieldGrad)" />
            <path d="M32 8L12 18V30.5C12 42.5 20.5 52 32 55.5C43.5 52 52 42.5 52 30.5V18L32 8Z" fill="url(#innerShieldGrad)" />
            <path d="M32 12L16 20V30.5C16 40.5 23 48.5 32 51.5C41 48.5 48 40.5 48 30.5V20L32 12Z" fill="white" />
            <path d="M40 28C38.5 25.5 35.5 24 32 24C27.5 24 24 27.5 24 32C24 36.5 27.5 40 32 40C35.5 40 38.5 38.5 40 36"
                stroke="#1d72dd" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="32" cy="32" r="2.5" fill="#141E30" />
        </svg>
        <p style="margin-bottom: 0.5rem;">Powered by kelompok 5</p>
        <p>
            &copy; 2026 WorkSync ALL RIGHTS RESERVED. &nbsp;
            <a href="#" style="color:#9CA3AF; text-decoration:none;">PRIVACY POLICY</a> &nbsp;
            <a href="#" style="color:#9CA3AF; text-decoration:none;">TERMS OF SERVICE</a> &nbsp;
            <a href="#" style="color:#9CA3AF; text-decoration:none;">SUPPORT</a>
        </p>
    </div>
</div>

<script>
    // Toggle password visibility
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passInput = document.getElementById('password');
    const eyeOpen   = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    toggleBtn.addEventListener('click', function () {
        const isHidden = passInput.getAttribute('type') === 'password';
        passInput.setAttribute('type', isHidden ? 'text' : 'password');
        eyeOpen.style.display   = isHidden ? 'none'  : 'block';
        eyeClosed.style.display = isHidden ? 'block' : 'none';
    });
</script>
@extends('admin.layouts.app')

@section('page_title', 'Settings')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        <section class="rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
            <h1 class="font-['Manrope'] text-2xl font-extrabold text-slate-900">Admin Settings</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui detail akun admin untuk workspace ini.</p>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-5 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Display Name</label>
                    <input id="name" name="name" type="text" required value="{{ old('name', $user->name) }}" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email Address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email', $user->email) }}" class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                </div>

                <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-800">
                    Save Settings
                </button>
            </form>
        </section>

        <aside class="space-y-4 rounded-3xl border border-white/80 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="font-['Manrope'] text-2xl font-extrabold text-slate-900">Account Snapshot</h2>

            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Role</p>
                <p class="mt-1 text-lg font-bold text-slate-900">{{ ucfirst($user->role) }}</p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Email Verification</p>
                <p class="mt-1 text-lg font-bold text-slate-900">
                    {{ $user->email_verified_at ? 'Verified' : 'Not verified' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Security</p>
                <p class="mt-1 text-sm text-slate-600">Gunakan halaman profile bawaan untuk update password.</p>
                <a href="{{ route('profile') }}" class="mt-3 inline-flex rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Open Profile Settings
                </a>
            </div>
        </aside>
    </div>
@endsection

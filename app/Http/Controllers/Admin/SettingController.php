<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.settings.index', [
            'user' => $request->user(),
        ]);
    }

    public function profile(Request $request): View
    {
        $user = $request->user();

        return view('admin.profile.index', [
            'user' => $user,
            'recentActivities' => $user->activities()
                ->latest('occurred_at')
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:120',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ];

        $isChangingPassword = $request->filled('current_password') ||
            $request->filled('password') ||
            $request->filled('password_confirmation');

        if ($isChangingPassword) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        if ($validated['email'] !== $user->email) {
            $validated['email_verified_at'] = null;
        }

        if ($isChangingPassword) {
            $validated['password'] = Hash::make($validated['password']);
            unset($validated['current_password'], $validated['password_confirmation']);
        }

        $user->update($validated);

        Activity::create([
            'user_id' => $user->id,
            'title' => 'Settings updated',
            'description' => 'Admin account settings were updated.',
            'category' => 'user',
            'is_read' => false,
            'link' => route('admin.settings.index'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success_message', 'Pengaturan akun berhasil diperbarui.');
    }
}

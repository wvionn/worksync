<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        return view('member.settings.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Validate profile information
        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:120',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ];

        // 2. Validate password if user wants to change it
        $isChangingPassword = $request->filled('password') || $request->filled('current_password') || $request->filled('password_confirmation');
        
        if ($isChangingPassword) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
        }

        $validated = $request->validate($rules);

        // Update profile fields
        $user->name = $validated['name'];
        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
        }

        // Update password if changed
        if ($isChangingPassword) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Log activity
        Activity::create([
            'user_id' => $user->id,
            'title' => 'Settings updated',
            'description' => 'Account settings and profile information were updated.',
            'category' => 'user',
            'is_read' => false,
            'link' => route('member.settings'),
            'occurred_at' => now(),
        ]);

        return redirect()
            ->route('member.settings')
            ->with('success_message', 'Pengaturan akun Anda berhasil diperbarui.');
    }
}

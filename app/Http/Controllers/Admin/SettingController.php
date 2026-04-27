<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:120',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        if ($validated['email'] !== $user->email) {
            $validated['email_verified_at'] = null;
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->withCount(['ownedProjects', 'assignedTasks'])
            ->orderBy('name')
            ->paginate(12);

        return view('admin.users.index', [
            'users' => $users,
            'adminCount' => User::query()->where('role', 'admin')->count(),
            'memberCount' => User::query()->where('role', 'member')->count(),
        ]);
    }
}

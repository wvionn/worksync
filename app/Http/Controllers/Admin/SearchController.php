<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->string('q'));

        $projects = collect();
        $tasks = collect();
        $users = collect();

        if ($query !== '') {
            $projects = Project::with('owner')
                ->where(function ($builder) use ($query): void {
                    $builder->where('name', 'like', "%{$query}%")
                        ->orWhere('client_name', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(8)
                ->get();

            $tasks = Task::with(['project', 'user'])
                ->where(function ($builder) use ($query): void {
                    $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(12)
                ->get();

            $users = User::query()
                ->where(function ($builder) use ($query): void {
                    $builder->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->orderBy('name')
                ->limit(8)
                ->get();
        }

        return view('admin.search.index', [
            'query' => $query,
            'projects' => $projects,
            'tasks' => $tasks,
            'users' => $users,
        ]);
    }
}

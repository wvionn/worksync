<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $projectStatusCounts = Project::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $taskStatusCounts = Task::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $taskPriorityCounts = Task::query()
            ->selectRaw('priority, COUNT(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority');

        $timeline = collect(range(0, 5))
            ->map(function (int $offset): array {
                $start = Carbon::now()->subMonths(5 - $offset)->startOfMonth();
                $end = $start->copy()->endOfMonth();

                return [
                    'month' => $start->format('M Y'),
                    'created' => Task::query()->whereBetween('created_at', [$start, $end])->count(),
                    'completed' => Task::query()->where('status', 'done')->whereBetween('updated_at', [$start, $end])->count(),
                ];
            })
            ->all();

        $overdueList = Task::query()
            ->with(['project', 'assignee'])
            ->where('status', '!=', 'done')
            ->where(function ($query): void {
                $query
                    ->where('status', 'overdue')
                    ->orWhereDate('due_date', '<', now()->toDateString());
            })
            ->orderBy('due_date')
            ->take(8)
            ->get();

        return view('admin.reports.index', [
            'projectStatusCounts' => $projectStatusCounts,
            'taskStatusCounts' => $taskStatusCounts,
            'taskPriorityCounts' => $taskPriorityCounts,
            'timeline' => $timeline,
            'overdueList' => $overdueList,
        ]);
    }
}

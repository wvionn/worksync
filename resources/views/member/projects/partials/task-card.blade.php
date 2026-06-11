<div class="bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer">
    <div class="flex items-start justify-between gap-2 mb-2">
        <div class="flex-1 min-w-0">
            <h4 class="font-medium text-gray-900 text-sm line-clamp-2">
                {{ $task->title }}
            </h4>
        </div>
        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded whitespace-nowrap flex-shrink-0">
            {{ ucfirst($task->priority) }}
        </span>
    </div>

    @if($task->description)
    <p class="text-xs text-gray-600 line-clamp-2 mb-2">
        {{ $task->description }}
    </p>
    @endif

    <div class="flex items-center justify-between text-xs">
        <div class="flex items-center gap-1">
            @if($task->user)
            <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                {{ substr($task->user->name, 0, 1) }}
            </div>
            @endif
            @if($task->due_date)
            <span class="text-gray-500">
                {{ $task->due_date->format('M d') }}
            </span>
            @endif
        </div>
        
        @if($task->isOverdue())
        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded">
            Overdue
        </span>
        @endif
    </div>
</div>

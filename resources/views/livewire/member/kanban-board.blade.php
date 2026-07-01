<div class="space-y-6" wire:poll.5s>
    <!-- Task Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Total Tasks</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $todoTasks->count() + $doingTasks->count() + $doneTasks->count() + $overdueTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-sm font-medium text-gray-500 mb-2 font-semibold">To Do</h3>
            <p class="text-3xl font-bold text-gray-600">{{ $todoTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-sm font-medium text-gray-500 mb-2 font-semibold">In Progress</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $doingTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-sm font-medium text-gray-500 mb-2 font-semibold">Completed</h3>
            <p class="text-3xl font-bold text-green-600">{{ $doneTasks->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-red-200 p-6 shadow-sm">
            <h3 class="text-sm font-medium text-red-600 mb-2 font-semibold">Overdue</h3>
            <p class="text-3xl font-bold text-red-600">{{ $overdueTasks->count() }}</p>
        </div>
    </div>

    <!-- Livewire Session Alerts inside Component -->
    <div>
        @if(session()->has('success_message'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between mb-4 transition-all">
                <span>{{ session('success_message') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">×</button>
            </div>
        @endif
        @if(session()->has('error_message'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between mb-4 transition-all">
                <span>{{ session('error_message') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">×</button>
            </div>
        @endif
    </div>

    <!-- Kanban Board Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- To Do Column -->
        <div class="bg-gray-100 rounded-xl p-4 flex flex-col h-full min-h-[500px]">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                    <h3 class="text-base font-bold text-gray-900">To Do</h3>
                </div>
                <span class="px-2 py-0.5 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">
                    {{ $todoTasks->count() }}
                </span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto">
                @forelse($todoTasks as $task)
                <div class="bg-white rounded-xl p-4 border border-gray-200 hover:shadow-md transition-shadow relative group">
                    <div class="flex items-start justify-between mb-2 gap-2">
                        <h4 class="font-semibold text-gray-900 text-sm leading-snug">{{ $task->title }}</h4>
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full flex-shrink-0
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-xs text-gray-600 mb-3 line-clamp-3 leading-relaxed">{{ $task->description }}</p>
                    @endif
                    @if($task->project)
                    <div class="flex items-center gap-1.5 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        <span class="text-[10px] text-gray-500 font-medium truncate">Project: {{ $task->project->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-1 text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-[11px] font-medium">
                                {{ $task->formatted_due_date ?: 'No deadline' }}
                            </span>
                        </div>
                        <button wire:click="updateStatus({{ $task->id }}, 'doing')" 
                                class="text-xs text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-0.5 transition-colors">
                            <span>Start</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
                    <p class="text-xs">No tasks in To Do</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Doing Column -->
        <div class="bg-blue-50 rounded-xl p-4 flex flex-col h-full min-h-[500px]">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <h3 class="text-base font-bold text-gray-900">Doing</h3>
                </div>
                <span class="px-2 py-0.5 bg-blue-200 text-blue-700 text-xs font-semibold rounded-full">
                    {{ $doingTasks->count() }}
                </span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto">
                @forelse($doingTasks as $task)
                <div class="bg-white rounded-xl p-4 border border-blue-200 hover:shadow-md transition-shadow relative group">
                    <div class="flex items-start justify-between mb-2 gap-2">
                        <h4 class="font-semibold text-gray-900 text-sm leading-snug">{{ $task->title }}</h4>
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full flex-shrink-0
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-xs text-gray-600 mb-3 line-clamp-3 leading-relaxed">{{ $task->description }}</p>
                    @endif
                    @if($task->project)
                    <div class="flex items-center gap-1.5 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        <span class="text-[10px] text-gray-500 font-medium truncate">Project: {{ $task->project->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-1 text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-[11px] font-medium">
                                {{ $task->formatted_due_date ?: 'No deadline' }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="updateStatus({{ $task->id }}, 'todo')" 
                                    class="text-[11px] text-gray-500 hover:text-gray-700 font-semibold flex items-center gap-0.5 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span>Back</span>
                            </button>
                            <button wire:click="updateStatus({{ $task->id }}, 'done')" 
                                    class="text-xs text-green-600 hover:text-green-700 font-semibold flex items-center gap-0.5 transition-colors">
                                <span>Done</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
                    <p class="text-xs">No tasks in progress</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Done Column -->
        <div class="bg-green-50 rounded-xl p-4 flex flex-col h-full min-h-[500px]">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    <h3 class="text-base font-bold text-gray-900">Done</h3>
                </div>
                <span class="px-2 py-0.5 bg-green-200 text-green-700 text-xs font-semibold rounded-full">
                    {{ $doneTasks->count() }}
                </span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto">
                @forelse($doneTasks as $task)
                <div class="bg-white rounded-xl p-4 border border-green-200 hover:shadow-md transition-shadow relative group">
                    <div class="flex items-start justify-between mb-2 gap-2">
                        <h4 class="font-semibold text-gray-900 text-sm leading-snug line-through opacity-75">{{ $task->title }}</h4>
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full flex-shrink-0
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-xs text-gray-500 mb-3 line-clamp-3 leading-relaxed opacity-75">{{ $task->description }}</p>
                    @endif
                    @if($task->project)
                    <div class="flex items-center gap-1.5 mb-3 opacity-75">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        <span class="text-[10px] text-gray-500 font-medium truncate">Project: {{ $task->project->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-1 text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-[11px] font-semibold text-green-600">Completed</span>
                        </div>
                        <button wire:click="updateStatus({{ $task->id }}, 'doing')" 
                                class="text-xs text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-0.5 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6H16"></path>
                            </svg>
                            <span>Reopen</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
                    <p class="text-xs">No completed tasks yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Overdue Column -->
        <div class="bg-red-50 rounded-xl p-4 flex flex-col h-full min-h-[500px]">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <h3 class="text-base font-bold text-gray-900">Overdue</h3>
                </div>
                <span class="px-2 py-0.5 bg-red-200 text-red-700 text-xs font-semibold rounded-full">
                    {{ $overdueTasks->count() }}
                </span>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto">
                @forelse($overdueTasks as $task)
                <div class="bg-white rounded-xl p-4 border-2 border-red-300 hover:shadow-md transition-shadow relative group">
                    <div class="flex items-start justify-between mb-2 gap-2">
                        <h4 class="font-semibold text-gray-900 text-sm leading-snug">{{ $task->title }}</h4>
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full flex-shrink-0
                            {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                    @if($task->description)
                    <p class="text-xs text-gray-500 mb-3 line-clamp-3 leading-relaxed">{{ $task->description }}</p>
                    @endif
                    @if($task->project)
                    <div class="flex items-center gap-1.5 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        <span class="text-[10px] text-gray-500 font-medium truncate">Project: {{ $task->project->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-1 text-red-600">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            @if($task->due_date)
                            <span class="text-[11px] font-bold">{{ $task->formatted_due_date }}</span>
                            @else
                            <span class="text-[11px] font-bold">Overdue</span>
                            @endif
                        </div>
                        <button wire:click="updateStatus({{ $task->id }}, 'doing')" 
                                class="text-xs text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-0.5 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Start</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
                    <p class="text-xs">No overdue tasks</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

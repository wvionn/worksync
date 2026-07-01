<div>
    <!-- Alert Messages -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
        x-on:show-alert.window="show = true; message = $event.detail[0].message; type = $event.detail[0].type; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-transition
         class="fixed top-4 right-4 z-50 max-w-md">
        <div :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'" 
             class="text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="message"></span>
        </div>
    </div>



    <!-- Kanban Board -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">My Assigned Tasks</h2>
            <p class="text-sm text-gray-500">Only tasks assigned to you</p>
        </div>

        <!-- Kanban Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" wire:poll.5s>
            
            <!-- To Do Column -->
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">To Do</h3>
                    <span class="px-2 py-1 bg-gray-200 text-gray-700 text-sm font-medium rounded-full">
                        {{ $todoTasks->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($todoTasks as $task)
                    <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-semibold text-gray-900 text-sm">
                                <a href="{{ route('member.tasks.show', $task->id) }}" class="hover:text-teal-600 hover:underline">
                                    {{ $task->title }}
                                </a>
                            </h4>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                        
                        @if($task->description)
                        <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                        @endif
                        
                        @if($task->due_date)
                        <div class="flex items-center gap-1 text-xs text-gray-500 mb-3">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $task->formatted_due_date }}</span>
                        </div>
                        @endif
                        
                        <!-- Status Dropdown -->
                        <div class="relative">
                            <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer">
                                <option value="todo" selected>To Do</option>
                                <option value="doing">Doing</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <p class="text-sm">No tasks</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Doing Column -->
            <div class="bg-blue-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Doing</h3>
                    <span class="px-2 py-1 bg-blue-200 text-blue-700 text-sm font-medium rounded-full">
                        {{ $doingTasks->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($doingTasks as $task)
                    <div class="bg-white rounded-lg border border-blue-200 p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-semibold text-gray-900 text-sm">
                                <a href="{{ route('member.tasks.show', $task->id) }}" class="hover:text-teal-600 hover:underline">
                                    {{ $task->title }}
                                </a>
                            </h4>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                        
                        @if($task->description)
                        <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                        @endif
                        
                        @if($task->due_date)
                        <div class="flex items-center gap-1 text-xs text-gray-500 mb-3">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $task->formatted_due_date }}</span>
                        </div>
                        @endif
                        
                        <!-- Status Dropdown -->
                        <div class="relative">
                            <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="w-full px-3 py-2 text-sm border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer">
                                <option value="todo">To Do</option>
                                <option value="doing" selected>Doing</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <p class="text-sm">No tasks</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Done Column -->
            <div class="bg-green-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Done</h3>
                    <span class="px-2 py-1 bg-green-200 text-green-700 text-sm font-medium rounded-full">
                        {{ $doneTasks->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($doneTasks as $task)
                    <div class="bg-white rounded-lg border border-green-200 p-4 hover:shadow-md transition opacity-75">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-semibold text-gray-900 text-sm line-through">
                                <a href="{{ route('member.tasks.show', $task->id) }}" class="hover:text-teal-600 hover:underline">
                                    {{ $task->title }}
                                </a>
                            </h4>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                        
                        @if($task->completed_at)
                        <div class="flex items-center gap-1 text-xs text-green-600 mb-3">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Completed {{ $task->completed_at->diffForHumans() }}</span>
                        </div>
                        @endif
                        
                        <!-- Status Dropdown -->
                        <div class="relative">
                            <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="w-full px-3 py-2 text-sm border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white cursor-pointer">
                                <option value="todo">To Do</option>
                                <option value="doing">Doing</option>
                                <option value="done" selected>Done</option>
                            </select>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <p class="text-sm">No tasks</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Overdue Column -->
            <div class="bg-red-50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Overdue</h3>
                    <span class="px-2 py-1 bg-red-200 text-red-700 text-sm font-medium rounded-full">
                        {{ $overdueTasks->count() }}
                    </span>
                </div>
                <div class="space-y-3">
                    @forelse($overdueTasks as $task)
                    <div class="bg-white rounded-lg border border-red-200 p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-semibold text-gray-900 text-sm">
                                <a href="{{ route('member.tasks.show', $task->id) }}" class="hover:text-teal-600 hover:underline">
                                    {{ $task->title }}
                                </a>
                            </h4>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $task->priority === 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                        
                        @if($task->description)
                        <p class="text-xs text-gray-600 mb-3">{{ Str::limit($task->description, 60) }}</p>
                        @endif
                        
                        @if($task->due_date)
                        <div class="flex items-center gap-1 text-xs text-red-600 mb-3 font-medium">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $task->formatted_due_date }} ({{ $task->due_date->diffForHumans() }})</span>
                        </div>
                        @endif
                        
                        <!-- Status Dropdown -->
                        <div class="relative">
                            <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="w-full px-3 py-2 text-sm border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white cursor-pointer">
                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="doing" {{ $task->status === 'doing' ? 'selected' : '' }}>Doing</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400">
                        <p class="text-sm">No overdue tasks</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

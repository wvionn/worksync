<div class="flex items-center justify-between bg-gray-50 rounded-2xl border p-5">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
            ⚙️
        </div>

        <div>
            <h3 class="font-bold text-gray-800">
                {{ $task->title }}
            </h3>

            <p class="text-sm text-gray-500 uppercase">
                {{ $task->project->name ?? 'No Project' }}
                •
                {{ $task->priority }}
            </p>
        </div>
    </div>

    <!-- Status Dropdown -->
    <div class="flex items-center gap-3">
        <select wire:change="updateStatus($event.target.value)"
                class="px-3 py-2 text-xs font-semibold border-0 rounded-full cursor-pointer focus:ring-2 focus:ring-blue-500
                @if($task->status == 'doing')
                    bg-blue-100 text-blue-600
                @elseif($task->status == 'done')
                    bg-green-100 text-green-600
                @else
                    bg-gray-100 text-gray-600
                @endif">
            <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
            <option value="doing" {{ $task->status === 'doing' ? 'selected' : '' }}>Doing</option>
            <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-4 gap-6 h-[calc(100vh-200px)]" wire:poll.3s>
    <!-- Sidebar - Projects List -->
    <div class="col-span-1 bg-white rounded-xl border border-gray-200 flex flex-col overflow-hidden">
        <!-- Search Projects -->
        <div class="p-4 border-b border-gray-200 bg-blue-50">
            <h3 class="font-bold text-gray-900 mb-2">Group Chats</h3>
            <input type="text" placeholder="Search projects..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                   x-data x-on:input="
                       const term = $event.target.value.toLowerCase();
                       document.querySelectorAll('.project-item').forEach(el => {
                           const name = el.getAttribute('data-name').toLowerCase();
                           el.style.display = name.includes(term) ? 'block' : 'none';
                       });
                   ">
        </div>

        <!-- Projects List -->
        <div class="flex-1 overflow-y-auto">
            @forelse($projects as $project)
            <button class="project-item w-full text-left px-4 py-3 hover:bg-blue-50 border-b border-gray-100 transition-colors {{ $selectedProjectId == $project->id ? 'bg-blue-100 border-l-4 border-l-blue-600' : '' }}"
                    data-name="{{ $project->name }}"
                    wire:click="selectProject({{ $project->id }})">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-900 truncate text-sm">{{ $project->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $project->members_count }} {{ $project->members_count > 1 ? 'members' : 'member' }}
                        </p>
                        <div class="flex -space-x-2 mt-2">
                            @foreach($project->members->take(3) as $member)
                            <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-[10px] font-bold border-2 border-white"
                                 title="{{ $member->name }}">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            @endforeach
                            @if($project->members_count > 3)
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-[10px] font-bold border-2 border-white">
                                +{{ $project->members_count - 3 }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </button>
            @empty
            <div class="p-4 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-sm">No projects with members</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Chat Area -->
    <div class="col-span-3 bg-white rounded-xl border border-gray-200 flex flex-col overflow-hidden">
        @if($selectedProjectId)
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $selectedProjectName }}</p>
                        <p class="text-xs text-gray-500">{{ count($members) }} members in this group</p>
                    </div>
                </div>

                <!-- Members Avatars -->
                <div class="flex -space-x-2">
                    @foreach($members->take(5) as $member)
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white shadow-sm"
                         title="{{ $member->name }}">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                    @endforeach
                    @if(count($members) > 5)
                    <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white shadow-sm">
                        +{{ count($members) - 5 }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chatMessagesArea" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"
                 x-data
                 x-init="
                     $el.scrollTop = $el.scrollHeight;
                     $wire.on('scroll-chat-to-bottom', () => {
                         setTimeout(() => { $el.scrollTop = $el.scrollHeight; }, 50);
                     });
                 "
                 @scroll-chat-to-bottom.window="setTimeout(() => { $el.scrollTop = $el.scrollHeight; }, 50)">
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-md">
                            @if($msg->sender_id !== Auth::id())
                            <!-- Sender Info -->
                            <div class="flex items-center gap-2 mb-1 ml-1">
                                <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($msg->sender->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-semibold text-gray-700">{{ $msg->sender->name }}</span>
                                <span class="text-xs text-gray-400">
                                    {{ $msg->sender->role === 'admin' ? 'Admin' : 'Member' }}
                                </span>
                            </div>
                            @endif
                            
                            <!-- Message Bubble -->
                            <div class="rounded-xl p-3 shadow-sm {{ $msg->sender_id === Auth::id() ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-none' }}">
                                <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                <p class="text-[10px] text-right mt-1 {{ $msg->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-400' }}">
                                    {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-gray-400 text-sm">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                            <p class="font-medium">No messages yet</p>
                            <p class="text-xs mt-1">Start the conversation with your team!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-gray-200">
                @if (session()->has('error'))
                    <div class="mb-3 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                
                @error('messageInput')
                    <div class="mb-3 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
                        {{ $message }}
                    </div>
                @enderror

                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <input type="text" 
                           wire:model.live="messageInput" 
                           placeholder="Type a message to the group..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                           wire:keydown.enter.prevent="sendMessage">
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendMessage">Send</span>
                        <span wire:loading wire:target="sendMessage">Sending...</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">Select a project group</p>
                    <p class="text-sm text-gray-500 mt-1">Choose a project from the list to start group chat</p>
                </div>
            </div>
        @endif
    </div>
</div>

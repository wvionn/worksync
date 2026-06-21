<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" wire:poll.3s>
    <div class="grid grid-cols-4 gap-0 h-[calc(100vh-200px)]">
        <!-- Sidebar - Groups & Members -->
        <div class="col-span-1 border-r border-gray-200 flex flex-col overflow-hidden">
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="font-bold text-gray-900 text-sm">Team Communication</h3>
                <p class="text-xs text-gray-500 mt-1">Groups & Members</p>
            </div>

            <div class="flex-1 overflow-y-auto">
                <!-- Group Projects Section -->
                @if($projects->count() > 0)
                <div class="p-3 bg-gray-50 border-b">
                    <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Project Groups
                    </h4>
                </div>

                @foreach($projects as $project)
                <button class="project-item w-full text-left px-4 py-3 hover:bg-blue-50 border-b border-gray-100 transition-colors {{ $chatType === 'group' && $selectedId == $project->id ? 'bg-blue-100 border-l-4 border-l-blue-600' : '' }}"
                        wire:click="selectGroup({{ $project->id }})">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-900 truncate text-sm">{{ $project->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $project->members_count }} {{ $project->members_count > 1 ? 'members' : 'member' }}
                            </p>
                        </div>
                        <div class="flex -space-x-1 mt-1">
                            @foreach($project->members->take(3) as $member)
                            <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-[10px] font-bold border-2 border-white"
                                 title="{{ $member->name }}">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </button>
                @endforeach
                @endif

                <!-- Individual Members Section -->
                @if($teamMembers->count() > 0)
                <div class="p-3 bg-gray-50 border-b mt-2">
                    <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Team Members
                    </h4>
                </div>

                @foreach($teamMembers as $member)
                <button class="member-item w-full text-left px-4 py-3 hover:bg-green-50 border-b border-gray-100 transition-colors flex items-center justify-between {{ $chatType === 'individual' && $selectedId == $member->id ? 'bg-green-100 border-l-4 border-l-green-600' : '' }}"
                        wire:click="selectMember({{ $member->id }})">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900 truncate text-sm">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $member->role }}</p>
                        </div>
                    </div>
                    
                    @if(isset($unreadCounts[$member->id]) && $unreadCounts[$member->id] > 0)
                    <span class="w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">
                        {{ $unreadCounts[$member->id] }}
                    </span>
                    @endif
                </button>
                @endforeach
                @endif

                @if($projects->count() === 0 && $teamMembers->count() === 0)
                <div class="p-8 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-xs">No team chats available</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-span-3 flex flex-col overflow-hidden">
            @if($selectedId)
                <!-- Chat Header -->
                <div class="p-5 border-b border-gray-200 {{ $chatType === 'group' ? 'bg-gradient-to-r from-blue-50 to-white' : 'bg-gradient-to-r from-green-50 to-white' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($chatType === 'group')
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white shadow-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            @else
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                {{ substr($selectedName, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <p class="font-bold text-gray-900 text-lg">{{ $selectedName }}</p>
                                <p class="text-sm text-gray-500">
                                    @if($chatType === 'group')
                                        <span class="flex items-center gap-1">
                                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            {{ count($members) }} members
                                        </span>
                                    @else
                                        <span>Direct Message</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        @if($chatType === 'group' && count($members) > 0)
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
                        @endif
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="chatMessagesArea" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50"
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
                                <div class="flex items-center gap-2 mb-1 ml-1">
                                    <div class="w-6 h-6 bg-gradient-to-br from-{{ $chatType === 'group' ? 'blue' : 'green' }}-400 to-{{ $chatType === 'group' ? 'blue' : 'green' }}-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($msg->sender->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">{{ $msg->sender->name }}</span>
                                    @if($msg->sender->role === 'admin')
                                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-[10px] font-bold">
                                        ADMIN
                                    </span>
                                    @endif
                                </div>
                                @endif
                                
                                <div class="rounded-2xl p-3 shadow-sm {{ $msg->sender_id === Auth::id() ? 'bg-blue-600 text-white rounded-tr-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-sm' }}">
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                    <p class="text-[10px] text-right mt-1 {{ $msg->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-400' }}">
                                        {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                        @if($msg->sender_id === Auth::id() && $chatType === 'individual')
                                            <span class="ml-1">{{ $msg->is_read ? '✓✓' : '✓' }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium">No messages yet</p>
                                <p class="text-xs mt-1">Start the conversation!</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="p-5 border-t border-gray-200 bg-white">
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
                               placeholder="Type a message..." 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                               wire:keydown.enter.prevent="sendMessage">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold text-sm flex items-center gap-2 shadow-md hover:shadow-lg disabled:opacity-50">
                            <span wire:loading.remove wire:target="sendMessage">Send</span>
                            <span wire:loading wire:target="sendMessage">Sending...</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex-1 flex items-center justify-center bg-slate-50">
                    <div class="text-center p-8">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Select a Chat</h3>
                        <p class="text-sm text-gray-500">Choose a project group or team member to start chatting</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

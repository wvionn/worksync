<div class="grid grid-cols-4 gap-6 h-[calc(100vh-200px)]" wire:poll.3s>
    <!-- Sidebar - Members List -->
    <div class="col-span-1 bg-white rounded-xl border border-gray-200 flex flex-col overflow-hidden">
        <!-- Search Members -->
        <div class="p-4 border-b border-gray-200">
            <input type="text" placeholder="Search members..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                   x-data x-on:input="
                       const term = $event.target.value.toLowerCase();
                       document.querySelectorAll('.member-item').forEach(el => {
                           const name = el.getAttribute('data-name').toLowerCase();
                           el.style.display = name.includes(term) ? 'block' : 'none';
                       });
                   ">
        </div>

        <!-- Members List -->
        <div class="flex-1 overflow-y-auto">
            @forelse($members as $member)
            <button class="member-item w-full text-left px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors flex items-center justify-between {{ $selectedMemberId == $member->id ? 'bg-blue-50 border-l-4 border-l-blue-600' : '' }}"
                    data-name="{{ $member->name }}"
                    wire:click="selectMember({{ $member->id }})">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm flex-shrink-0">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-gray-900 truncate text-sm">{{ $member->name }}</p>
                        <p class="text-xs text-gray-500">Member</p>
                    </div>
                </div>
                
                <!-- Unread Count Badge -->
                @if(isset($unreadCounts[$member->id]) && $unreadCounts[$member->id] > 0)
                <span class="w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">
                    {{ $unreadCounts[$member->id] }}
                </span>
                @endif
            </button>
            @empty
            <div class="p-4 text-center text-gray-500">
                <p class="text-sm">No members available</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Chat Area -->
    <div class="col-span-3 bg-white rounded-xl border border-gray-200 flex flex-col overflow-hidden">
        @if($selectedMemberId)
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">
                        {{ substr($selectedMemberName, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $selectedMemberName }}</p>
                        <p class="text-xs text-gray-500">Active chat session</p>
                    </div>
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
                        <div class="max-w-xs md:max-w-md rounded-xl p-3 shadow-sm {{ $msg->sender_id === Auth::id() ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-none' }}">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                            <p class="text-[10px] text-right mt-1 {{ $msg->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-400' }}">
                                {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                @if($msg->sender_id === Auth::id())
                                    <span class="ml-1">{{ $msg->is_read ? '✓✓' : '✓' }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-gray-400 text-sm">
                        No messages yet. Send a message to start the conversation!
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-gray-200">
                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <input type="text" wire:model="messageInput" placeholder="Type your message..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center gap-2">
                        <span>Send</span>
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
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-lg font-semibold text-gray-900">Select a member to start chatting</p>
                    <p class="text-sm text-gray-500 mt-1">Choose a team member from the list on the left</p>
                </div>
            </div>
        @endif
    </div>
</div>

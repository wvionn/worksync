<div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden h-[calc(100vh-250px)]" wire:poll.3s>
    <!-- Direct Chat with Admin (No Sidebar) -->
    <div class="flex flex-col h-full">
        @if($selectedAdminId)
            <!-- Chat Header -->
            <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">
                            {{ substr($selectedAdminName, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-xl">{{ $selectedAdminName }}</p>
                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                <span class="flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    Active now
                                </span>
                                <span class="text-gray-300">•</span>
                                <span>Admin Team</span>
                            </p>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Chat Support</p>
                        <p class="text-xs font-semibold text-blue-600"> Live Chat</p>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chatMessagesArea" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50" x-data x-init="
                         $el.scrollTop = $el.scrollHeight;
                         $wire.on('scroll-chat-to-bottom', () => {
                             setTimeout(() => { $el.scrollTop = $el.scrollHeight; }, 50);
                         });
                     " @scroll-chat-to-bottom.window="setTimeout(() => { $el.scrollTop = $el.scrollHeight; }, 50)">
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-lg rounded-2xl p-4 shadow-sm {{ $msg->sender_id === Auth::id() ? 'bg-blue-600 text-white rounded-tr-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-sm' }}">
                            @if($msg->sender_id !== Auth::id())
                                <div class="flex items-center gap-2 mb-2">
                                    <div
                                        class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($msg->sender->name, 0, 1) }}
                                    </div>
                                    <p class="text-xs font-semibold text-blue-600">{{ $msg->sender->name }}</p>
                                </div>
                            @endif
                            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                            <p
                                class="text-[10px] text-right mt-2 {{ $msg->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-400' }}">
                                {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                @if($msg->sender_id === Auth::id())
                                    <span class="ml-1">{{ $msg->is_read ? '✓✓' : '✓' }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600">Belum ada pesan</p>
                            <p class="text-xs mt-1">Kirim pesan untuk memulai percakapan dengan admin</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="p-5 border-t border-gray-200 bg-white">
                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <input type="text" wire:model="messageInput" placeholder="Ketik pesan Anda di sini..."
                        class="flex-1 px-5 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        @keydown.enter.prevent="$wire.sendMessage()">
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-all font-semibold text-sm flex items-center gap-2 shadow-md hover:shadow-lg hover:scale-105">
                        <span>Kirim</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @else
            <!-- Loading State -->
            <div class="flex-1 flex items-center justify-center bg-slate-50">
                <div class="text-center p-8">
                    <div
                        class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4">
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Menghubungkan ke admin...</p>
                </div>
            </div>
        @endif
    </div>
</div>
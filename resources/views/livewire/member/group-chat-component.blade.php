<div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden h-[calc(100vh-250px)]" wire:poll.3s>
    @if($selectedProjectId)
        <!-- Direct Group Chat (No Sidebar) -->
        <div class="flex flex-col h-full">
            <!-- Chat Header -->
            <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-xl">{{ $selectedProjectName }}</p>
                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                <span class="flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    {{ count($members) }} members active
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Members Avatars -->
                    <div class="flex -space-x-2">
                        @foreach($members->take(5) as $member)
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold border-2 border-white shadow-md"
                             title="{{ $member->name }}">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        @endforeach
                        @if(count($members) > 5)
                        <div class="w-10 h-10 bg-gray-400 rounded-full flex items-center justify-center text-white text-sm font-bold border-2 border-white shadow-md">
                            +{{ count($members) - 5 }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Project Selector (if multiple projects) -->
                @if($projects->count() > 1)
                <div class="mt-4">
                    <select wire:change="selectProject($event.target.value)" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $project->id == $selectedProjectId ? 'selected' : '' }}>
                            {{ $project->name }} ({{ $project->members_count }} members)
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
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
                        <div class="max-w-lg">
                            @if($msg->sender_id !== Auth::id())
                            <!-- Sender Info -->
                            <div class="flex items-center gap-2 mb-1 ml-1">
                                <div class="w-7 h-7 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
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
                            
                            <!-- Message Bubble -->
                            <div class="rounded-2xl p-4 shadow-sm {{ $msg->sender_id === Auth::id() ? 'bg-blue-600 text-white rounded-tr-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-sm' }}">
                                <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                <p class="text-[10px] text-right mt-2 {{ $msg->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-400' }}">
                                    {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600">Belum ada pesan</p>
                            <p class="text-xs mt-1">Mulai diskusi dengan tim project Anda!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="p-5 border-t border-gray-200 bg-white">
                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <input type="text" 
                           wire:model="messageInput" 
                           placeholder="Ketik pesan ke group..." 
                           class="flex-1 px-5 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                           @keydown.enter.prevent="$wire.sendMessage()">
                    <button type="submit" 
                            class="px-8 py-3 bg-blue-600 text-white rounded-2xl hover:bg-blue-700 transition-all font-semibold text-sm flex items-center gap-2 shadow-md hover:shadow-lg hover:scale-105">
                        <span>Kirim</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- No Projects State -->
        <div class="flex-1 flex items-center justify-center h-full bg-slate-50">
            <div class="text-center p-8">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-gray-700">Tidak Ada Project</p>
                <p class="text-sm text-gray-500 mt-2">Anda belum di-assign ke project apapun</p>
            </div>
        </div>
    @endif
</div>

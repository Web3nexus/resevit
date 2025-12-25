<x-filament-panels::page>
    <div x-data="{ showNewChatModal: false }" class="flex h-[calc(100vh-12rem)] bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
        
        <!-- New Chat Modal -->
        <div x-show="showNewChatModal" 
             x-transition
             class="absolute inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
             style="display: none;">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden" @click.away="showNewChatModal = false">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg">Start New Chat</h3>
                    <button @click="showNewChatModal = false" class="text-gray-400 hover:text-gray-600">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>
                <div class="max-h-96 overflow-y-auto p-2">
                    @forelse($this->availableStaff as $user)
                        <button 
                            wire:click="startConversation({{ $user->id }}); showNewChatModal = false"
                            class="w-full text-left p-3 hover:bg-gray-50 rounded-lg flex items-center gap-3 transition-colors"
                        >
                            <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold text-xs">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </button>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-sm">No other staff members found.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="w-1/4 border-r border-gray-200 flex flex-col bg-gray-50">
            <div class="p-4 border-b border-gray-200 space-y-3">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Chats</h3>
                    <button @click="showNewChatModal = true" class="text-primary-600 hover:text-primary-700 p-1 hover:bg-primary-50 rounded">
                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                    </button>
                </div>
                <input type="text" wire:model.live="searchQuery" placeholder="Search chats..." class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            
            <div class="flex-1 overflow-y-auto">
                @foreach($this->conversations as $conversation)
                    <button 
                        wire:click="selectConversation({{ $conversation->id }})"
                        class="w-full text-left p-4 hover:bg-gray-100 transition-colors {{ $selectedConversationId == $conversation->id ? 'bg-white border-l-4 border-primary-500 shadow-sm' : '' }}"
                    >
                        <div class="font-semibold text-gray-800">{{ $conversation->name ?? 'Chat' }}</div>
                        <div class="text-xs text-gray-500 truncate">
                            {{ $conversation->participants->count() }} participants
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col bg-white">
            @if($this->activeConversation)
                <!-- Header -->
                <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-white z-10">
                    <h2 class="font-bold text-lg text-gray-800">{{ $this->activeConversation->name }}</h2>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">
                         {{ $this->activeConversation->type }}
                    </span>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50/50" 
                     id="messages-container"
                     x-data 
                     x-init="$el.scrollTop = $el.scrollHeight"
                     @message-sent.window="$nextTick(() => $el.scrollTop = $el.scrollHeight)"
                     wire:poll.3s
                >
                    @forelse($this->messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%]">
                                @if($message->sender_id !== auth()->id())
                                    <div class="text-xs text-gray-500 mb-1 ml-1">{{ $message->sender->name ?? 'User' }}</div>
                                @endif
                                
                                <div class="px-4 py-2 rounded-2xl shadow-sm text-sm {{ $message->sender_id === auth()->id() ? 'bg-primary-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-700 rounded-bl-none' }}">
                                    {{ $message->message }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right mr-1' : 'ml-1' }}">
                                    {{ $message->created_at->format('g:i A') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex justify-center items-center h-full text-gray-400 text-sm">
                            No messages yet. Say hello!
                        </div>
                    @endforelse
                </div>

                <!-- Input -->
                <div class="p-4 border-t border-gray-200 bg-white">
                    <form wire:submit="sendMessage" class="flex gap-2">
                        <input 
                            type="text" 
                            wire:model="newMessage" 
                            placeholder="Type a message..." 
                            class="flex-1 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            autofocus
                        >
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Send
                        </button>
                    </form>
                </div>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-400 bg-gray-50">
                    <div class="text-center">
                        <x-heroicon-o-chat-bubble-left-right class="w-12 h-12 mx-auto mb-2 text-gray-300" />
                        <p>Select a conversation to start chatting</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>

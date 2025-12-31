<x-filament-panels::page>
    <div
        class="flex h-[calc(100vh-12rem)] overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">

        <!-- Sidebar: Chat List -->
        <div class="w-1/3 border-r border-gray-200 dark:border-gray-700 flex flex-col">
            <!-- Search / Filter -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search conversations..."
                    class="w-full px-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-600">

                <div class="flex gap-2 mt-2">
                    <button wire:click="$set('filter', 'all')"
                        class="text-xs px-2 py-1 rounded {{ $filter === 'all' ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 text-gray-600' }}">All</button>
                    <button wire:click="$set('filter', 'open')"
                        class="text-xs px-2 py-1 rounded {{ $filter === 'open' ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 text-gray-600' }}">Open</button>
                </div>
            </div>

            <!-- List -->
            <div class="flex-1 overflow-y-auto">
                @forelse($this->chats as $chat)
                            <div wire:key="chat-{{ $chat->id }}" wire:click="selectChat({{ $chat->id }})"
                                class="p-4 border-b border-gray-100 dark:border-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 {{ $selectedChatId === $chat->id ? 'bg-primary-50 dark:bg-gray-800' : '' }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white text-sm">
                                            @php
                                                $name = $chat->customer_name ?? 'Unknown Customer';
                                                if (!auth()->user()->hasAnyRole(['owner', 'manager'])) {
                                                    $name = substr($name, 0, 1) . '****' . substr($name, -1);
                                                }
                                            @endphp
                                            {{ $name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1 truncate w-40">{{ $chat->uuid }}</p>
                                        <!-- In real app, show last message preview here -->
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="text-[10px] text-gray-400">{{ $chat->last_message_at ? $chat->last_message_at->shortAbsoluteDiffForHumans() : '' }}</span>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium capitalize
                                                                                        {{ $chat->source === 'whatsapp' ? 'bg-green-100 text-green-800' :
                    ($chat->source === 'facebook' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800') }}">
                                                @if($chat->source === 'whatsapp')
                                                    <x-heroicon-m-chat-bubble-left-ellipsis class="w-3 h-3" />
                                                @elseif($chat->source === 'facebook')
                                                    <x-heroicon-m-chat-bubble-oval-left-ellipsis class="w-3 h-3" />
                                                @elseif($chat->source === 'instagram')
                                                    <x-heroicon-m-camera class="w-3 h-3" />
                                                @endif
                                                {{ $chat->source }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @if($chat->unread_count > 0)
                                    <div class="mt-2 text-right">
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $chat->unread_count }}</span>
                                    </div>
                                @endif
                            </div>
                @empty
                    <div class="p-8 text-center text-gray-500 text-sm">
                        No conversations found.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main: Chat Area -->
        <div class="w-2/3 flex flex-col bg-gray-50 dark:bg-gray-900/50">
            @if($this->activeChat)
                <!-- Header -->
                <div
                    class="p-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center shadow-sm">
                    <div>
                        <h2 class="font-bold text-gray-900 dark:text-white">
                            @php
                                $name = $this->activeChat->customer_name;
                                if (!auth()->user()->hasAnyRole(['owner', 'manager'])) {
                                    $name = substr($name, 0, 1) . '****' . substr($name, -1);
                                }
                            @endphp
                            {{ $name }}
                        </h2>
                        <span class="text-xs text-gray-500">via {{ ucfirst($this->activeChat->source) }}</span>
                    </div>
                    <div class="flex gap-2">
                        {{ ($this->createReservationAction)() }}
                        <!-- Optional Actions: Close, Archive -->
                    </div>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages-container">
                    @foreach($this->messages as $msg)
                        <div class="flex {{ $msg->direction === 'outbound' ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-[70%] {{ $msg->direction === 'outbound' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700' }} rounded-lg px-4 py-2 shadow-sm text-sm">
                                <p>{{ $msg->content }}</p>
                                <div class="mt-1 text-[10px] opacity-70 text-right">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if($msg->direction === 'outbound')
                                        <span class="ml-1">{{ $msg->status }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Input -->
                <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    <form wire:submit="sendMessage" class="flex gap-2">
                        <textarea wire:model="newMessage" wire:keydown.enter.prevent="sendMessage" rows="1"
                            class="flex-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm focus:border-primary-500 focus:ring-primary-500 resize-none"
                            placeholder="Type a message..."></textarea>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <x-heroicon-m-paper-airplane class="w-4 h-4" />
                        </button>
                    </form>
                </div>

                <!-- Helper to scroll to bottom -->
                <script>
                    window.addEventListener('message-sent', event => {
                        const container = document.getElementById('messages-container');
                        if (container) container.scrollTop = container.scrollHeight;
                    });
                    document.addEventListener('livewire:initialized', () => {
                        const container = document.getElementById('messages-container');
                        if (container) container.scrollTop = container.scrollHeight;
                    });
                </script>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <x-heroicon-o-chat-bubble-left-right class="w-12 h-12 mx-auto text-gray-400 mb-2" />
                        <p>Select a conversation to start messaging</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
<div class="flex flex-col h-[600px] bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    {{-- Header --}}
    <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-brand-primary flex items-center justify-center text-white font-bold">
                {{ substr($conversation->name ?? 'G', 0, 1) }}
            </div>
            <div>
                <h3 class="font-bold text-gray-900">{{ $conversation->name ?? 'Guest' }}</h3>
                <p class="text-xs text-gray-500">{{ $conversation->email ?? 'No email' }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button wire:click="closeChat"
                class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold rounded-lg transition-colors">
                Close Chat
            </button>
        </div>
    </div>

    {{-- Messages --}}
    <div class="flex-grow overflow-y-auto p-4 space-y-4 bg-gray-50" id="admin-chat-messages" wire:poll.3s>
        @foreach($messages as $msg)
            <div
                class="flex items-end space-x-2 {{ $msg->sender_type === 'admin' ? 'flex-row-reverse space-x-reverse' : '' }}">
                {{-- Avatar Icon --}}
                <div
                    class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $msg->sender_type === 'admin' ? 'bg-brand-primary text-white' : 'bg-brand-accent text-brand-primary' }}">
                    @if($msg->sender_type === 'admin')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    @endif
                </div>

                <div
                    class="max-w-[70%] px-4 py-2 rounded-2xl text-sm {{ $msg->sender_type === 'admin' ? 'bg-brand-primary text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-200 rounded-bl-none shadow-sm' }}">
                    {{ $msg->body }}
                    <div
                        class="text-[10px] mt-1 opacity-50 {{ $msg->sender_type === 'admin' ? 'text-white' : 'text-gray-500' }}">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Input --}}
    <div class="flex items-center space-x-2">
        <input type="text" wire:model.defer="message" wire:keydown.enter="sendMessage" placeholder="Type your reply..."
            class="flex-grow px-4 py-1.5 rounded-xl border-gray-200 focus:border-brand-accent focus:ring-brand-accent text-sm">
        <button type="button" wire:click="sendMessage"
            class="px-6 py-1.5 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-secondary transition-colors shadow-sm">
            Send
        </button>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const scrollDown = () => {
                const chatMessages = document.getElementById('admin-chat-messages');
                if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
            };
            scrollDown();
            @this.on('messageSent', () => {
                setTimeout(scrollDown, 10);
            });
        });
    </script>
</div>
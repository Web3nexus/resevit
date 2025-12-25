<div class="flex flex-col h-[500px]">
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 rounded-lg border border-gray-200" id="chat-container">
        @forelse($messages->reverse() as $msg)
            @php
                $isMe = $msg->user_id === Filament\Facades\Filament::auth()->id() && $msg->user_type === get_class(Filament\Facades\Filament::auth()->user());
            @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%]">
                    <div class="flex items-center gap-2 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }} mb-1">
                        <span class="text-xs font-medium text-gray-500">{{ $msg->sender->name ?? 'Unknown' }}</span>
                        <span class="text-[10px] text-gray-400">{{ $msg->created_at->format('M d, H:i') }}</span>
                    </div>
                    <div
                        class="p-3 rounded-2xl {{ $isMe ? 'bg-primary-600 text-white rounded-tr-none' : 'bg-white border border-gray-200 text-gray-800 rounded-tl-none' }}">
                        <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex justify-center items-center h-full">
                <p class="text-gray-400 text-sm">No messages yet. Start the conversation!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        <form wire:submit="sendMessage" class="relative">
            <textarea wire:model="message"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm pr-12 resize-none"
                rows="3" placeholder="Type your message..." required></textarea>
            <button type="submit"
                class="absolute bottom-2 right-2 p-2 text-primary-600 hover:text-primary-700 disabled:opacity-50"
                wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path
                        d="M3.478 2.404a.75.75 0 00-.926.941l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.404z" />
                </svg>
            </button>
        </form>
    </div>
</div>
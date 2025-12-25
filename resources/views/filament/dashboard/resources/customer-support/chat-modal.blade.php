<div x-data="{ message: '' }">
    <div class="h-64 overflow-y-auto border rounded p-4 mb-4 bg-gray-50 flex flex-col space-y-3">
        @forelse($conversation->messages()->orderBy('created_at')->get() as $msg)
            <div class="flex {{ $msg->sender_type === 'staff' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] p-3 rounded-lg {{ $msg->sender_type === 'staff' ? 'bg-primary-100 text-primary-900' : 'bg-white border text-gray-800' }}">
                    <p class="text-sm">{{ $msg->message }}</p>
                    <p class="text-[10px] text-gray-500 mt-1">{{ $msg->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 text-sm">No messages yet.</p>
        @endforelse
    </div>

    <!-- 
        Note: Real reply implementation would require a Livewire component to handle the submission 
        inside this modal without closing it, or use a custom page instead of a modal action. 
        For MVP demonstration, we show the history. 
        To make it functional, we would wrap this in a Livewire component.
    -->
    <div class="text-center text-xs text-gray-500">
        (Reply functionality to be fully integrated with Livewire in next iteration)
    </div>
</div>

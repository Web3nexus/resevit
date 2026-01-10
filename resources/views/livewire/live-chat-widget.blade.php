<div class="fixed bottom-6 right-6 z-[99999]" 
    x-data="{ 
        open: false,
        init() {
            this.open = @js($isOpen);
        }
    }"
    style="font-family: 'Inter', sans-serif;">
    
    <style>
        [x-cloak] { display: none !important; }
        .chat-glow {
            box-shadow: 0 0 20px rgba(245, 158, 11, 0.2);
        }
    </style>

    {{-- Chat Trigger Button --}}
    <button @click="open = !open; @this.set('isOpen', open)"
        type="button"
        class="w-16 h-16 bg-[#0F172A] rounded-full shadow-[0_10px_40px_rgba(0,0,0,0.4)] flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-300 border-4 border-white relative z-[100001] group">
        
        {{-- Fun Vibrant Chat Icon --}}
        <div x-show="!open" class="text-[#F59E0B] flex items-center justify-center">
            <svg class="w-10 h-10 drop-shadow-sm" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.477 2 2 6.477 2 12c0 1.891.527 3.66 1.439 5.166L2 22l4.834-1.439C8.34 21.473 10.109 22 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z" style="display:none;"/>
                <path d="M12 2C6.48 2 2 6.48 2 12c0 2.17.7 4.19 1.94 5.86L3 22l4.14-1.1c1.51.71 3.2 1.1 4.86 1.1 5.52 0 10-4.48 10-10S17.52 2 12 2zm0 18c-1.47 0-2.85-.39-4.03-1.09l-2.91.49.49-2.91A7.97 7.97 0 0112 4c4.41 0 8 3.59 8 8s-3.59 8-8 8z" />
                <path d="M14 9h2v2h-2V9zm-4 0h2v2h-2V9zm-4 0h2v2H6V9z" opacity=".5"/>
            </svg>
        </div>

        {{-- Close Icon --}}
        <div x-show="open" x-cloak class="text-white flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
    </button>

    {{-- Chat Window --}}
    <div x-show="open" 
        x-cloak
        x-on:click.away="open = false; @this.set('isOpen', false)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
        class="absolute bottom-20 right-0 w-[360px] bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-slate-100 overflow-hidden flex flex-col max-h-[500px] min-h-[400px] z-[100000]">
        
        {{-- Header --}}
        <div class="bg-[#0F172A] p-5 text-white flex items-center justify-between shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center border border-white/20 text-[#F59E0B]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg leading-tight">Live Support</h3>
                    <div class="flex items-center text-xs text-[#F59E0B]">
                        <span class="w-2 h-2 bg-[#F59E0B] rounded-full mr-1.5 shadow-[0_0_8px_#F59E0B] animate-pulse"></span>
                        Online
                    </div>
                </div>
            </div>
            <button @click.stop="open = false; @this.set('isOpen', false)" 
                class="p-2 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-grow overflow-y-auto p-6 bg-white space-y-4" id="chat-messages" wire:poll.5s>
            @if($showEmailForm)
                <div class="bg-slate-50 p-6 rounded-[2rem] space-y-4 border border-slate-100 shadow-inner">
                    <div class="space-y-1">
                        <p class="text-slate-800 font-bold text-base">Hello! 👋</p>
                        <p class="text-xs text-slate-500">Please introduce yourself to start.</p>
                    </div>
                    <div class="space-y-3">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Name</label>
                            <input type="text" wire:model="name" placeholder="John Doe" 
                                class="w-full px-5 py-3.5 rounded-xl border-slate-200 focus:border-[#0F172A] focus:ring-4 focus:ring-[#0F172A]/5 text-sm transition-all shadow-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Email</label>
                            <input type="email" wire:model="email" placeholder="john@example.com" 
                                class="w-full px-5 py-3.5 rounded-xl border-slate-200 focus:border-[#0F172A] focus:ring-4 focus:ring-[#0F172A]/5 text-sm transition-all shadow-sm">
                        </div>
                        <button wire:click="startConversation" 
                            class="w-full py-4 bg-[#0F172A] text-white font-bold rounded-xl hover:bg-black transition-all shadow-lg active:scale-[0.97] mt-2">
                            Start Chatting
                        </button>
                    </div>
                </div>
            @else
                @forelse($messages as $msg)
                    <div class="flex items-end space-x-2 {{ $msg->sender_type === 'admin' ? '' : 'flex-row-reverse space-x-reverse' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 shadow-sm {{ $msg->sender_type === 'admin' ? 'bg-[#0F172A] text-white' : 'bg-[#F59E0B] text-[#0F172A]' }}">
                            @if($msg->sender_type === 'admin')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <div class="max-w-[85%] px-4 py-3 rounded-2xl text-sm {{ $msg->sender_type === 'admin' ? 'bg-slate-100 text-slate-800 rounded-bl-none' : 'bg-[#0F172A] text-white rounded-br-none shadow-md' }}">
                            {{ $msg->body }}
                            <div class="text-[10px] mt-1.5 opacity-50 font-bold {{ $msg->sender_type === 'admin' ? 'text-slate-500' : 'text-slate-200' }}">
                                {{ $msg->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center space-y-4 py-12">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                             <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="space-y-1 px-8">
                            <p class="text-slate-800 font-bold text-base">We're here to help!</p>
                            <p class="text-slate-500 text-xs">Our team is online and ready for you.</p>
                        </div>
                    </div>
                @endforelse
            @endif
        </div>

        {{-- Footer/Input --}}
        @if(!$showEmailForm)
            <div class="p-5 pb-8 bg-white border-t border-slate-50">
                <form wire:submit.prevent="sendMessage" wire:key="chat-form" class="flex items-center space-x-2">
                    <input type="text" wire:model="message" placeholder="Write a message..."
                        class="flex-grow px-5 py-2.5 rounded-xl border-none bg-slate-100 focus:bg-white focus:ring-2 focus:ring-[#0F172A] text-sm shadow-inner transition-all placeholder:text-slate-400">
                    <button type="submit" wire:loading.attr="disabled" wire:target="sendMessage"
                        class="w-12 h-10 bg-[#0F172A] text-white rounded-xl flex items-center justify-center hover:bg-black transition-all shadow-lg active:scale-90 group shrink-0 disabled:opacity-50">
                        <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                             <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                        </svg>
                        <svg wire:loading wire:target="sendMessage" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('messageSent', () => {
                setTimeout(() => {
                    const chatMessages = document.getElementById('chat-messages');
                    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 100);
            });
        });
    </script>
</div>
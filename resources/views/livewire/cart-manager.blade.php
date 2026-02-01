<div class="z-50">
    <!-- Floating Cart Button -->
    <div class="fixed bottom-6 right-6 z-50" style="display: {{ count($cart) > 0 ? 'block' : 'none' }}">
        <button wire:click="$toggle('isCartOpen')"
            class="bg-primary hover:opacity-90 text-white rounded-full p-4 shadow-2xl flex items-center gap-3 transition-transform hover:scale-105 group relative">
            <div class="relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span
                    class="absolute -top-2 -right-2 bg-white text-primary text-[10px] font-black rounded-full h-5 w-5 flex items-center justify-center shadow-sm">
                    {{ count($cart) }}
                </span>
            </div>
            <span class="font-bold text-xs uppercase tracking-widest hidden sm:block">View Cart</span>
        </button>
    </div>

    <!-- Cart Slide-over -->
    <div x-data="{ open: @entangle('isCartOpen') }" x-show="open" class="fixed inset-0 z-60 overflow-hidden"
        style="display: none;">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Overlay -->
            <div x-show="open" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"
                class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="open" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md">
                    <div class="flex h-full flex-col bg-white shadow-2xl">
                        <!-- Header -->
                        <div class="px-6 py-8 bg-gray-50 flex items-center justify-between border-b border-gray-100">
                            <h2 class="text-xl font-black uppercase tracking-widest text-dark">Your Order</h2>
                            <button @click="open = false" class="text-gray-400 hover:text-dark transition">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Items -->
                        <div class="flex-1 overflow-y-auto px-6 py-6 custom-scrollbar">
                            @if(count($cart) > 0)
                                <ul class="space-y-8">
                                    @foreach($cart as $item)
                                        <li class="flex items-center gap-6 group">
                                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-gray-100">
                                                <img src="{{ \App\Helpers\StorageHelper::getUrl($item['image']) ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=200' }}"
                                                    class="h-full w-full object-cover group-hover:scale-110 transition duration-500">
                                            </div>

                                            <div class="flex flex-1 flex-col">
                                                <div class="flex justify-between items-start mb-1">
                                                    <h3 class="text-sm font-bold text-dark uppercase tracking-tight">
                                                        {{ $item['name'] }}
                                                    </h3>
                                                    <p class="text-sm font-black text-primary">
                                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                                </div>

                                                <div class="flex items-center justify-between mt-auto">
                                                    <div
                                                        class="flex items-center border border-gray-100 rounded-lg p-1 bg-gray-50">
                                                        <button wire:click="updateQuantity('{{ $item['id'] }}', -1)"
                                                            class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-primary transition">-</button>
                                                        <span
                                                            class="w-8 text-center text-xs font-bold">{{ $item['quantity'] }}</span>
                                                        <button wire:click="updateQuantity('{{ $item['id'] }}', 1)"
                                                            class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-primary transition">+</button>
                                                    </div>
                                                    <button wire:click="removeFromCart('{{ $item['id'] }}')"
                                                        class="text-[10px] font-black text-gray-300 hover:text-red-500 uppercase tracking-widest transition">Remove</button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-center">
                                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-dark mb-2">Cart is empty</h3>
                                    <p class="text-sm text-gray-400 mb-8">Looks like you haven't added anything yet.</p>
                                    <button @click="open = false"
                                        class="bg-primary text-white px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:opacity-90 transition">Start
                                        Ordering</button>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        @if(count($cart) > 0)
                            <div class="border-t border-gray-100 p-6 space-y-6">
                                <div class="flex justify-between items-center text-lg">
                                    <span class="font-bold text-gray-400 uppercase tracking-widest text-xs">Subtotal</span>
                                    <span class="font-black text-dark text-2xl">${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <a href="{{ route('tenant.checkout') }}"
                                    class="w-full flex items-center justify-center rounded-full bg-primary py-4 text-xs font-black text-white shadow-xl hover:opacity-90 transition uppercase tracking-[0.2em]">Checkout
                                    Now</a>
                                <p class="text-center text-[10px] text-gray-400 font-medium italic">Taxes and fees
                                    calculated at checkout</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #eee;
            border-radius: 10px;
        }
    </style>
</div>
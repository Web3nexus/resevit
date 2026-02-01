@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#FF2E5B';
    $borderRadius = $settings['border_radius'] ?? 'lg';
    $radiusMap = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        '3xl' => 'rounded-3xl',
        'full' => 'rounded-full',
    ];
    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-lg';
@endphp

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 font-sans"
    style="--primary-color: {{ $primaryColor }}">
    <style>
        .text-primary {
            color: var(--primary-color);
        }

        .bg-primary {
            background-color: var(--primary-color);
        }

        .border-primary {
            border-color: var(--primary-color);
        }

        .focus\:ring-primary:focus {
            --tw-ring-color: var(--primary-color);
        }

        .focus\:border-primary:focus {
            border-color: var(--primary-color);
        }
    </style>

    <div class="max-w-4xl mx-auto">
        <div class="flex flex-col items-center mb-12">
            @if($website->content['logo'] ?? false)
                <img src="{{ \App\Helpers\StorageHelper::getUrl($website->content['logo']) }}" class="h-16 w-auto mb-6"
                    alt="Logo">
            @endif
            <h1 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Checkout</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">{{ $website->content['business_name'] ?? tenant('name') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Order Details -->
            <div class="lg:col-span-7">
                <div
                    class="bg-white dark:bg-gray-800 {{ $radiusClass }} shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                            <span
                                class="w-8 h-8 bg-primary/10 text-primary rounded-full flex items-center justify-center text-sm">1</span>
                            Customer Information
                        </h2>

                        <form wire:submit.prevent="placeOrder" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full
                                        Name</label>
                                    <input type="text" wire:model="customerName"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border-none px-4 py-3 {{ $radiusClass }} text-sm focus:ring-2 focus:ring-primary shadow-inner dark:text-white"
                                        placeholder="John Doe">
                                    @error('customerName') <span
                                        class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Phone
                                        Number</label>
                                    <input type="tel" wire:model="customerPhone"
                                        class="w-full bg-gray-50 dark:bg-gray-700 border-none px-4 py-3 {{ $radiusClass }} text-sm focus:ring-2 focus:ring-primary shadow-inner dark:text-white"
                                        placeholder="+1 (555) 000-0000">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Select
                                    Order Type</label>
                                <div class="grid grid-cols-3 gap-4">
                                    @foreach(['takeout' => 'Takeout', 'pickup' => 'Pickup', 'dine-in' => 'Booking'] as $val => $label)
                                                                    <button type="button" wire:click="$set('type', '{{ $val }}')"
                                                                        class="py-4 px-2 {{ $radiusClass }} border-2 transition text-center flex flex-col items-center gap-2
                                                                            {{ $type === $val
                                        ? 'border-primary bg-primary/5 text-primary'
                                        : 'border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 text-gray-400 dark:text-gray-500' }}">
                                                                        <span class="text-2xl">
                                                                            @if($val === 'takeout') 🛍️
                                                                            @elseif($val === 'pickup') 🚗
                                                                            @else 🍽️ @endif
                                                                        </span>
                                                                        <span
                                                                            class="text-[10px] font-black uppercase tracking-tighter">{{ $label }}</span>
                                                                    </button>
                                    @endforeach
                                </div>
                                <input type="hidden" wire:model="type">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Special
                                    Notes / Table #</label>
                                <textarea wire:model="notes" rows="3"
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-none px-4 py-3 {{ $radiusClass }} text-sm focus:ring-2 focus:ring-primary shadow-inner dark:text-white"
                                    placeholder="Any allergies, special requests, or your table number if dining in..."></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-primary text-white font-black py-5 {{ $radiusClass }} hover:opacity-90 transition tracking-[0.2em] shadow-lg shadow-primary/30 uppercase text-sm mt-8 border-none cursor-pointer">
                                Confirm & Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-5">
                <div
                    class="bg-white dark:bg-gray-800 {{ $radiusClass }} shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-24">
                    <div class="p-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-8">Order Summary</h2>
                        <div class="space-y-6 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($cart as $item)
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 {{ $radiusClass }} bg-gray-100 dark:bg-gray-700 overflow-hidden shrink-0">
                                            @if($item['image'] ?? false)
                                                <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">🥘
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $item['name'] }}
                                            </h4>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Qty:
                                                {{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-sm font-black text-gray-900 dark:text-white">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t-2 border-dashed border-gray-100 dark:border-gray-700 mt-8 pt-8">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">Subtotal</span>
                                <span
                                    class="font-bold text-gray-900 dark:text-white">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xl font-black">
                                <span class="text-dark dark:text-white uppercase tracking-tighter">Total Amount</span>
                                <span class="text-primary">${{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-12 text-center">
                            <a href="{{ route('tenant.menu') }}"
                                class="text-[10px] font-black text-gray-400 hover:text-primary transition uppercase tracking-[0.3em]">
                                ← Add more items
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
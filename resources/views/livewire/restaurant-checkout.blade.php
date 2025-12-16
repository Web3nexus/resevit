<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Complete Your Order</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Order Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Your Details</h2>
                <form wire:submit.prevent="placeOrder" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" wire:model="customerName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        @error('customerName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input type="tel" wire:model="customerPhone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order Type</label>
                        <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                            <option value="dine-in">Dine-in</option>
                            <option value="pickup">Pickup</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes / Table Number</label>
                        <textarea wire:model="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" placeholder="Any special requests or table number if dining in..."></textarea>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-6">
                        Place Order (${{ number_format($subtotal, 2) }})
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Summary</h2>
                <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($cart as $item)
                    <li class="py-4 flex justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Qty: {{ $item['quantity'] }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                    </li>
                    @endforeach
                </ul>
                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4 flex justify-between items-center">
                    <span class="font-bold text-gray-900 dark:text-white">Total</span>
                    <span class="font-bold text-xl text-indigo-600 dark:text-indigo-400">${{ number_format($subtotal, 2) }}</span>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('tenant.menu') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">← Back to Menu</a>
                </div>
            </div>
        </div>
    </div>
</div>
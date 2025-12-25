<div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[calc(100vh-100px)]">
    <!-- Left: Menu & Categories -->
    <div class="md:col-span-2 flex flex-col gap-4 overflow-hidden">
        <!-- Categories -->
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})"
                    class="px-4 py-2 rounded-lg whitespace-nowrap transition-colors {{ $selectedCategory === $category->id ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- Menu Items Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto pr-2 pb-20">
            @forelse($menuItems as $item)
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer flex flex-col justify-between h-full"
                    @if($item->variants->isEmpty())
                        wire:click="addToCart({{ $item->id }})"
                    @endif
                >
                    <div>
                        <div class="h-32 w-full bg-gray-100 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-800 line-clamp-2">{{ $item->name }}</h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $item->description }}</p>
                    </div>
                    
                    <div class="mt-3">
                        @if($item->variants->count() > 0)
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($item->variants as $variant)
                                    <button 
                                        wire:click="addToCart({{ $item->id }}, {{ $variant->id }})"
                                        class="text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded border border-gray-200">
                                        {{ $variant->name }} (+${{ number_format($variant->price_adjustment, 2) }})
                                    </button>
                                @endforeach
                            </div>
                        @else
                           <div class="flex items-center justify-between mt-2">
                                <span class="text-lg font-bold text-primary-600">${{ number_format($item->base_price, 2) }}</span>
                                <button class="p-2 bg-primary-50 text-primary-600 rounded-full hover:bg-primary-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                           </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-gray-500">
                    No items in this category.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right: Cart & Checkout -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg flex flex-col h-full overflow-hidden">
        <!-- Cart Header -->
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="font-bold text-lg text-gray-800">New Order</h2>
            <div class="text-sm text-gray-500">
                Table: 
                <select wire:model.live="selectedTable" class="ml-2 p-1 border rounded bg-white text-gray-700 text-sm focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Select Table</option>
                    @foreach($tables as $table)
                        <option value="{{ $table->id }}">{{ $table->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse($cart as $key => $item)
                <div class="flex justify-between items-start gap-2 pb-3 border-b border-gray-100 last:border-0">
                    <div class="flex-1">
                        <div class="font-medium text-gray-800">{{ $item['name'] }}</div>
                        @if($item['variant_name'])
                            <div class="text-xs text-gray-500">{{ $item['variant_name'] }}</div>
                        @endif
                        <div class="text-xs text-gray-400 mt-1">${{ number_format($item['price'], 2) }} each</div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 bg-gray-50 rounded-lg p-1">
                            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})" class="p-1 hover:bg-gray-200 rounded text-gray-600 disabled:opacity-50" @if($item['quantity'] <= 1) wire:click="removeFromCart('{{ $key }}')" @endif>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <span class="w-6 text-center text-sm font-medium">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})" class="p-1 hover:bg-gray-200 rounded text-gray-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                        <div class="font-semibold text-gray-800 w-16 text-right">
                            ${{ number_format($item['subtotal'], 2) }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400 flex flex-col items-center">
                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p>Cart is empty</p>
                    <p class="text-xs mt-1">Select items from the menu to add them here.</p>
                </div>
            @endforelse
        </div>

        <!-- Checkout Section -->
        <div class="border-t border-gray-200 bg-gray-50 p-4 space-y-4">
             <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Assign Waiter</label>
                <select wire:model="selectedStaff" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <option value="">No Waiter Assigned</option>
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->user->name ?? 'Staff #' . $staff->id }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>${{ number_format($this->total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tax (0%)</span>
                    <span>$0.00</span>
                </div>
                <div class="flex justify-between font-bold text-lg text-gray-800 pt-2 border-t border-gray-200">
                    <span>Total</span>
                    <span>${{ number_format($this->total, 2) }}</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Received Amount</label>
                <input type="number" wire:model.live.debounce.500ms="amountReceived" class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="0.00">
                @if($this->amountReceived > 0)
                    <div class="flex justify-between text-sm {{ $this->change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <span>Change:</span>
                        <span class="font-bold">${{ number_format($this->change, 2) }}</span>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-2">
                 <button wire:click="$set('paymentMethod', 'cash')" class="px-3 py-2 text-sm rounded-lg border {{ $paymentMethod === 'cash' ? 'bg-primary-50 border-primary-500 text-primary-700' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                    Cash
                 </button>
                 <button wire:click="$set('paymentMethod', 'card')" class="px-3 py-2 text-sm rounded-lg border {{ $paymentMethod === 'card' ? 'bg-primary-50 border-primary-500 text-primary-700' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                    Card
                 </button>
            </div>

            <button 
                wire:click="checkout"
                wire:loading.attr="disabled"
                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl shadow-lg transition-transform transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center">
                <span wire:loading.remove>Checkout Now</span>
                <span wire:loading>Processing...</span>
            </button>
            
            <div x-data="{ lastOrder: null }" 
                 @order-completed.window="lastOrder = $event.detail.orderId">
                <button 
                    x-show="lastOrder"
                    @click="$wire.printReceipt(lastOrder)"
                    class="w-full mt-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 rounded-xl text-sm transition-colors border border-gray-300">
                    Print Last Receipt
                </button>
            </div>
        </div>
    </div>
</div>

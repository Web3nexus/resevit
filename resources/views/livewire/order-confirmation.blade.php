<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full mb-4">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
            <p class="text-lg text-gray-600">Thank you for your order</p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Order Number</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Order Type</p>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($order->order_type) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4">
                            @if($item['image'] ?? false)
                                <img src="{{ \App\Helpers\StorageHelper::getUrl($item['image']) }}" alt="{{ $item['name'] }}"
                                    class="w-16 h-16 object-cover rounded-lg">
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-500">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Totals -->
            <div class="border-t border-gray-200 pt-6 space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->tax > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Tax</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                @endif
                @if($order->delivery_fee > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Delivery Fee</span>
                        <span>${{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t">
                    <span>Total</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-yellow-800">
                        Payment Status: <span class="font-bold">{{ ucfirst($order->payment_status) }}</span>
                    </p>
                </div>
                @if($order->payment_status === 'unpaid')
                    <p class="text-sm text-yellow-700 mt-2">
                        Payment will be collected upon {{ $order->order_type === 'delivery' ? 'delivery' : 'pickup' }}.
                    </p>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="{{ route('tenant.menu') }}"
                class="flex-1 bg-primary text-white text-center py-3 rounded-lg font-semibold hover:opacity-90 transition">
                Continue Shopping
            </a>
            <a href="{{ route('tenant.order.status', $order) }}"
                class="flex-1 bg-gray-800 text-white text-center py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Track Order
            </a>
        </div>

        <!-- Contact Info -->
        @if($website)
            <div class="mt-8 text-center text-gray-600">
                <p class="text-sm">Questions about your order?</p>
                @if($website->content['phone'] ?? false)
                    <p class="text-sm font-medium">Call us at {{ $website->content['phone'] }}</p>
                @endif
            </div>
        @endif
    </div>
</div>
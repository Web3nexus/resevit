<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-md w-full text-center">
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 dark:bg-green-900 mb-6">
            <svg class="h-12 w-12 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Order Confirmed!</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-8">
            Thank you for your order. We've received it and are preparing it now.
        </p>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden mb-8 text-left">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Order #{{ $order->id }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    {{ $order->created_at->format('M d, Y h:i A') }}
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white uppercase font-bold tracking-wider">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                {{ $order->status }}
                            </span>
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-bold">
                            ${{ number_format($order->total_amount, 2) }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <a href="{{ route('tenant.menu') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Make another order
        </a>
    </div>
</div>
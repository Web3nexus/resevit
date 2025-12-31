<x-filament-panels::page>
    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-3">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-600" />
            <div class="text-sm text-red-700">
                {!! session('error') !!}
            </div>
        </div>
    @endif

    @if(session('status'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 flex items-center gap-3">
            <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
            <div class="text-sm text-green-700">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Balance Card -->
        <div
            class="col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Balance</h3>
                <p class="text-4xl font-extrabold text-brand-primary dark:text-white mt-2">
                    ${{ number_format($balance, 2) }}
                </p>
            </div>
            <div class="mt-6">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Use this balance to pay your staff members directly.
                </p>
            </div>
        </div>

        <!-- Transactions History -->
        <div
            class="col-span-1 md:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Transactions</h3>
                <a href="#" class="text-sm text-brand-accent hover:underline">View All</a>
            </div>

            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($transactions as $transaction)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-full">
                                @if($transaction->type === 'deposit')
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                        </path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $transaction->description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->amount > 0 ? '+' : '' }}${{ number_format($transaction->amount, 2) }}
                            </p>
                            <p class="text-[10px] uppercase font-bold text-gray-400">{{ $transaction->status }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-gray-200 dark:text-gray-700 mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <p class="text-gray-500 mt-4">No transactions found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
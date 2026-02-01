<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Stripe Connect Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-[#635BFF] rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 32 32" fill="currentColor">
                        <path
                            d="M11.6667 15.6883C11.6667 15.005 12.1817 14.545 13.5683 14.225L17.5833 13.315C19.0433 12.975 19.6433 12.3583 19.6433 11.4583C19.6433 10.3883 18.6783 9.76834 16.985 9.76834C15.2283 9.76834 14.37 10.4317 14.1983 11.565L10.3367 10.945C10.7433 8.35167 13.08 6.66 17.0283 6.66C21.49 6.66 23.9767 8.52167 23.9767 11.3933C23.9767 13.065 23.0117 14.1567 21.06 14.6283L17.2183 15.55C16.0383 15.8283 15.3533 16.2783 15.3533 17.0717C15.3533 18.0133 16.36 18.6783 17.9283 18.6783C19.88 18.6783 20.9517 17.9283 21.145 16.6633L25.0467 17.2633C24.51 19.9833 22.1933 21.7833 17.865 21.7833C13.255 21.7833 11.0233 19.9633 11.0233 17.2833L11.6667 15.6883Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Stripe Payments</h2>
                    <p class="text-sm text-gray-500">Accept credit cards and get payouts directly to your bank.</p>
                </div>
            </div>

            @if($stripeAccountStatus === 'active')
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                    <div class="flex items-center gap-3">
                        <x-heroicon-s-check-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                        <div>
                            <p class="font-semibold text-green-800 dark:text-green-300">Payments Active</p>
                            <p class="text-sm text-green-700 dark:text-green-400">Your account is connected and ready to
                                accept payments.</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <x-filament::button color="gray" href="https://dashboard.stripe.com" tag="a" target="_blank">
                        Open Stripe Dashboard
                    </x-filament::button>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <x-heroicon-m-check class="w-4 h-4 text-primary" />
                            <span>Accept Visa, Mastercard, Amex</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-heroicon-m-check class="w-4 h-4 text-primary" />
                            <span>Direct daily payouts</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-heroicon-m-check class="w-4 h-4 text-primary" />
                            <span>Secure & PCI Compliant</span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col gap-3">
                    @if($stripeAccountStatus === 'pending')
                        <x-filament::button wire:click="connectStripe" color="warning">
                            Continue Setup
                        </x-filament::button>
                        <x-filament::button wire:click="checkStatus" color="gray" size="sm">
                            Check Status
                        </x-filament::button>
                    @else
                        <x-filament::button wire:click="connectStripe" size="lg">
                            Connect with Stripe
                        </x-filament::button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Platform Fees Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-lg mb-4">Fee Structure</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Platform Fee</span>
                    <span class="font-medium">3.0%</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Stripe Processing</span>
                    <span class="font-medium">2.9% + $0.30</span>
                </div>
                <div
                    class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-blue-800 dark:text-blue-300">
                    <p>Fees are automatically deducted from each transaction. You receive the net amount directly in
                        your payout.</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @livewire(\App\Filament\Invest\Widgets\InvestorStatsWidget::class)
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-bold">Investment History</h2>
        <p class="text-gray-500">View your active and past investments in the menu.</p>
    </div>
</x-filament-panels::page>
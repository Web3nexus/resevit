<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($plans as $plan)
            <div @class([
                'flex flex-col p-6 space-y-6 rounded-2xl border transition-all duration-300',
                'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800' => $currentPlan?->id !== $plan->id,
                'bg-primary-50 dark:bg-primary-900/10 border-primary-500 ring-1 ring-primary-500' => $currentPlan?->id === $plan->id,
            ])>
                <div class="space-y-2">
                    <h3 class="text-xl font-bold tracking-tight">{{ $plan->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $plan->description }}
                    </p>
                </div>

                <div class="flex items-baseline space-x-1">
                    <span class="text-4xl font-bold tracking-tight">${{ number_format($plan->price_monthly, 0) }}</span>
                    <span class="text-gray-500 dark:text-gray-400">/mo</span>
                </div>

                @if ($currentPlan?->id === $plan->id)
                    <div
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-primary-700 bg-primary-100 rounded-lg dark:bg-primary-900/30 dark:text-primary-400">
                        Current Plan
                    </div>
                @else
                    <x-filament::button size="lg" outlined wire:click="upgrade('{{ $plan->slug }}')">
                        {{ $plan->cta_text }}
                    </x-filament::button>
                @endif

                <ul class="space-y-3 text-sm">
                    @foreach ($plan->features as $feature)
                        @if ($feature->pivot->is_included)
                            <li class="flex items-center space-x-3">
                                <x-heroicon-o-check class="w-5 h-5 text-success-500" />
                                <span>
                                    {{ $feature->name }}
                                    @if ($feature->pivot->value)
                                        <span class="text-gray-400">({{ $feature->pivot->value }})</span>
                                    @endif
                                </span>
                            </li>
                        @else
                            <li class="flex items-center space-x-3 text-gray-400 line-through">
                                <x-heroicon-o-x-mark class="w-5 h-5 text-gray-300" />
                                <span>{{ $feature->name }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
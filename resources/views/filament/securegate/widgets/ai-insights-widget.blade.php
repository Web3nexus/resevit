<x-filament-widgets::widget>
    <x-filament::section
        class="h-full bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 border !border-primary-200 dark:!border-primary-900 shadow-sm relative overflow-hidden">

        <!-- Background decoration -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-primary-500/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-secondary-500/10 rounded-full blur-2xl"></div>

        <x-slot name="heading">
            <div class="flex items-center gap-2 relative z-10">
                <div class="p-1.5 bg-primary-100 dark:bg-primary-900/50 rounded-lg">
                    <x-filament::icon icon="heroicon-m-sparkles"
                        class="h-5 w-5 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <span
                        class="font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-secondary-600 dark:from-primary-400 dark:to-secondary-400">
                        Official AI Strategic Insights
                    </span>
                    <p class="text-[10px] text-gray-500 font-normal uppercase tracking-wider">Powered by OpenAI Analysis
                    </p>
                </div>

            </div>
        </x-slot>

        <div class="space-y-4 max-h-[300px] overflow-y-auto relative z-10 pr-2 custom-scrollbar">
            @foreach($insights as $insight)
                <div
                    class="group relative flex gap-4 p-4 rounded-xl bg-white dark:bg-gray-800/80 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300 hover:border-{{ $insight['type'] }}-200">
                    <div class="shrink-0 pt-1">
                        <div
                            class="h-8 w-8 rounded-full bg-{{ $insight['type'] }}-50 dark:bg-{{ $insight['type'] }}-900/30 flex items-center justify-center">
                            <x-filament::icon :icon="$insight['icon']"
                                class="h-4 w-4 text-{{ $insight['type'] }}-600 dark:text-{{ $insight['type'] }}-400" />
                        </div>
                    </div>
                    <div>
                        <h4
                            class="font-semibold text-sm text-gray-900 dark:text-white mb-1 group-hover:text-{{ $insight['type'] }}-600 transition-colors">
                            {{ $insight['title'] }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ $insight['message'] }}
                        </p>
                    </div>
                </div>
            @endforeach
            <div class="flex items-center justify-center gap-1 text-[10px] text-gray-400 pt-2 opacity-70">
                <x-filament::icon icon="heroicon-m-clock" class="h-3 w-3" />
                <span>Generated live {{ now()->diffForHumans() }}</span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
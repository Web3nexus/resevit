<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold tracking-tight">System Service Health</h2>
            <div class="flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-success-500"></span>
                </span>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Live
                    Monitoring</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($services as $service)
                <div
                    class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 flex justify-between items-start">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $service['name'] }}</h3>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 uppercase tracking-wider">
                            {{ $service['description'] }}</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span
                            class="text-[10px] font-bold text-success-600 dark:text-success-400 uppercase tracking-tighter">Operational</span>
                        <span class="w-2 h-2 rounded-full bg-success-500 shadow-[0_0_5px_rgba(34,197,94,0.4)]"></span>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
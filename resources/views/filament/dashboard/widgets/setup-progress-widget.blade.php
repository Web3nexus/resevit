<x-filament::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold">Setup Progress</h2>
            <span class="text-sm font-medium text-gray-500">{{ $progress }}% Completed</span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-6 dark:bg-gray-700">
            <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%">
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach($steps as $step)
                <a href="{{ $step['action'] }}"
                    class="block p-4 border rounded-lg hover:bg-gray-50 transition {{ $step['completed'] ? 'border-green-200 bg-green-50' : 'border-gray-200' }}">
                    <div class="flex items-start gap-4">
                        <div
                            class="p-2 rounded-full {{ $step['completed'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                            <x-filament::icon icon="{{ $step['icon'] }}" class="h-6 w-6" />
                        </div>
                        <div>
                            <h3 class="font-semibold {{ $step['completed'] ? 'text-green-900' : 'text-gray-900' }}">
                                {{ $step['label'] }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $step['description'] }}</p>
                            @if($step['completed'])
                                <span class="inline-flex items-center mt-2 text-xs font-medium text-green-700">
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 mr-1" /> Completed
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament::widget>
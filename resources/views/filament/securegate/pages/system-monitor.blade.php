<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-filament::section>
            <x-slot name="heading">
                System Status
            </x-slot>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Service Status</span>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                        Operational
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-500">30-Day Uptime</span>
                    <span class="font-bold text-primary-600">{{ $uptimePercentage }}%</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Last Pulse</span>
                    <span>{{ $lastPulse?->created_at?->diffForHumans() ?? 'Never' }}</span>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Environment Info
            </x-slot>

            <div class="space-y-4">
                @foreach($systemInfo as $label => $value)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">{{ $label }}</span>
                        <span class="font-mono">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>

    @if($lastPulse)
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::card class="p-4 border-l-4 border-success-500">
                <div class="text-sm text-gray-500 uppercase font-bold">Current CPU Load</div>
                <div class="text-2xl font-bold">{{ number_format($lastPulse->cpu_usage, 1) }}%</div>
            </x-filament::card>

            <x-filament::card class="p-4 border-l-4 border-primary-500">
                <div class="text-sm text-gray-500 uppercase font-bold">Memory Usage</div>
                <div class="text-2xl font-bold">{{ number_format($lastPulse->memory_usage, 1) }}MB</div>
            </x-filament::card>

            <x-filament::card class="p-4 border-l-4 border-warning-500">
                <div class="text-sm text-gray-500 uppercase font-bold">Disk Space Used</div>
                <div class="text-2xl font-bold">{{ number_format($lastPulse->disk_usage, 1) }}%</div>
            </x-filament::card>
        </div>
    @endif
</x-filament-panels::page>
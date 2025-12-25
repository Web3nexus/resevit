<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    {{ $this->form }}
                </div>
                <x-filament::button 
                    wire:click="runOptimization" 
                    icon="heroicon-m-sparkles"
                    :disabled="$isOptimizing"
                >
                    Run AI Optimization
                </x-filament::button>
            </div>
        </x-filament::section>

        @if(count($suggestions) > 0)
            <x-filament::section>
                <x-slot name="heading">AI Suggestions</x-slot>
                
                <div class="divide-y divide-gray-200">
                    @foreach($suggestions as $resId => $tableId)
                        @php 
                            $res = \App\Models\Reservation::find($resId);
                            $table = \App\Models\Table::find($tableId);
                        @endphp
                        <div class="py-3 flex justify-between items-center">
                            <div>
                                <span class="font-bold">#{{ $resId }}</span> ({{ $res->party_size }} guests) 
                                at {{ $res->reservation_time->format('H:i') }}
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400">Move to</span>
                                <x-filament::badge color="success">{{ $table->name }}</x-filament::badge>
                            </div>
                        </div>
                    @endforeach
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end">
                        <x-filament::button wire:click="applySuggestions" color="success">
                            Apply All Changes
                        </x-filament::button>
                    </div>
                </x-slot>
            </x-filament::section>
        @endif

        <x-filament::section>
            <x-slot name="heading">Current Reservations ({{ $this->date }})</x-slot>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left divide-y divide-gray-200">
                    <thead class="bg-gray-50 uppercase text-xs font-medium">
                        <tr>
                            <th class="px-4 py-3">Res ID</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Time</th>
                            <th class="px-4 py-3">Current Table</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($this->reservations as $res)
                            <tr>
                                <td class="px-4 py-3">#{{ $res->id }}</td>
                                <td class="px-4 py-3">{{ $res->guest_name ?? $res->customer?->name }}</td>
                                <td class="px-4 py-3">{{ $res->reservation_time->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <x-filament::badge>{{ $res->table?->name ?? 'None' }}</x-filament::badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>

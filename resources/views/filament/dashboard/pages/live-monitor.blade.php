<x-filament-panels::page>
    <div
        x-data="{
            tables: $wire.entangle('tables'),
            statuses: $wire.entangle('statuses'),
            activeTable: null,
            dragOffset: { x: 0, y: 0 },
            isDirty: false,
            dragHandler: null,
            stopHandler: null,

            init() {
                 this.$watch('statuses', (newStatuses) => {
                    this.tables.forEach(table => {
                        if (newStatuses[table.id]) {
                            table.status = newStatuses[table.id];
                        }
                    });
                 });
            },

            startDrag(event, tableId) {
                const tableIndex = this.tables.findIndex(t => t.id === tableId);
                if (tableIndex === -1) return;

                this.activeTable = tableIndex;
                const table = this.tables[tableIndex];
                
                this.dragOffset = {
                    x: event.clientX - table.x,
                    y: event.clientY - table.y
                };
                
                // create bound functions
                this.dragHandler = this.handleDrag.bind(this);
                this.stopHandler = this.stopDrag.bind(this);

                window.addEventListener('mousemove', this.dragHandler);
                window.addEventListener('mouseup', this.stopHandler);
            },

            handleDrag(event) {
                if (this.activeTable === null) return;
                
                this.tables[this.activeTable].x = event.clientX - this.dragOffset.x;
                this.tables[this.activeTable].y = event.clientY - this.dragOffset.y;
                this.isDirty = true;
            },

            stopDrag() {
                if (this.dragHandler) {
                    window.removeEventListener('mousemove', this.dragHandler);
                    this.dragHandler = null;
                }
                if (this.stopHandler) {
                    window.removeEventListener('mouseup', this.stopHandler);
                    this.stopHandler = null;
                }
                this.activeTable = null;
            },

            save() {
                $wire.saveLayout(this.tables);
                this.isDirty = false;
            },

            getColorClass(status) {
                switch(status) {
                    case 'occupied': return 'bg-red-500 border-red-600 text-white';
                    case 'reserved': return 'bg-yellow-500 border-yellow-600 text-white';
                    case 'maintenance': return 'bg-gray-500 border-gray-600 text-white';
                    default: return 'bg-green-500 border-green-600 text-white'; 
                }
            }
        }"
        class="flex flex-col gap-4">
        <!-- Polling Trigger for Statuses Only -->
        <div wire:poll.10s="refreshStatuses" class="hidden"></div>

        @if($this->rooms->isEmpty())
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center">
            <p class="text-gray-500">No rooms configured. Go to "Rooms" to set up your floor plan.</p>
        </div>
        @else
        <!-- Toolbar -->
        <div class="flex justify-between items-center bg-white dark:bg-gray-900 p-4 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <!-- Room Tabs -->
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                @foreach($this->rooms as $room)
                <a
                    href="#"
                    wire:click.prevent="$set('selectedRoomId', {{ $room->id }})"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors
                            {{ $selectedRoomId == $room->id 
                                ? 'border-primary-500 text-primary-600 dark:text-primary-400' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                    {{ $room->name }}
                </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-4">
                <!-- Legends -->
                <div class="hidden sm:flex gap-3 text-xs">
                    <div class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Available</div>
                    <div class="flex items-center"><span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span> Occupied</div>
                    <div class="flex items-center"><span class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></span> Reserved</div>
                </div>

                <x-filament::button
                    x-show="isDirty"
                    x-on:click="save"
                    size="sm">
                    Save Layout
                </x-filament::button>
            </div>
        </div>

        <!-- Canvas -->
        <div
            wire:ignore
            class="relative w-full h-[600px] bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700 select-none">
            <div class="absolute inset-0 pointer-events-none opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>

            <template x-for="table in tables" :key="table.id">
                <div
                    class="absolute flex items-center justify-center shadow-md border-2 transition-colors cursor-move"
                    :class="[
                            getColorClass(table.status),
                            table.shape === 'circle' ? 'rounded-full' : 'rounded-lg'
                        ]"
                    :style="`left: ${table.x}px; top: ${table.y}px; width: ${table.width}px; height: ${table.height}px; transform: rotate(${table.rotation}deg);`"
                    @mousedown="startDrag($event, table.id)"
                    :title="`${table.name} (${table.seats} seats)`">

                    <div class="text-center relative z-10 pointer-events-none">
                        <span class="block font-bold text-xs" x-text="table.name"></span>
                        <span class="block text-[10px] opacity-90" x-text="table.seats + 'p'"></span>
                    </div>

                    <!-- Chairs -->
                    <template x-for="i in parseInt(table.seats)" :key="i">
                        <div
                            class="absolute w-3 h-3 bg-gray-200 dark:bg-gray-700 border border-gray-400 dark:border-gray-600 rounded-full"
                            :style="`
                                    left: 50%; 
                                    top: 50%;
                                    margin-left: -6px;
                                    margin-top: -6px; 
                                    transform: 
                                        rotate(${ (i - 1) * (360 / table.seats) }deg) 
                                        translate(${ table.width / 2 + 10 }px) 
                                        rotate(${ -((i - 1) * (360 / table.seats)) }deg);
                                `"></div>
                    </template>
                </div>
            </template>
        </div>
        @endif
    </div>
</x-filament-panels::page>
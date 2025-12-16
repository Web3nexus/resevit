<x-filament-panels::page>
    <div
        x-data="{
            tables: $wire.entangle('tables'),
            activeTable: null,
            dragOffset: { x: 0, y: 0 },
            
            init() {
                // Future: Add resize logic or more advanced interactions
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
                
                window.addEventListener('mousemove', this.handleDrag);
                window.addEventListener('mouseup', this.stopDrag);
            },

            handleDrag(event) {
                if (this.activeTable === null) return;
                
                // You might want to clamp values to canvas boundaries here
                this.tables[this.activeTable].x = event.clientX - this.dragOffset.x;
                this.tables[this.activeTable].y = event.clientY - this.dragOffset.y;
            },

            stopDrag() {
                window.removeEventListener('mousemove', this.handleDrag);
                window.removeEventListener('mouseup', this.stopDrag);
                this.activeTable = null;
            },

            save() {
                $wire.saveLayout(this.tables);
            }
        }"
        class="flex flex-col gap-4">
        <!-- Toolbar -->
        <div class="flex justify-between items-center bg-white dark:bg-gray-900 p-4 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium">Canvas</h2>
            <x-filament::button x-on:click="save">
                Save Layout
            </x-filament::button>
        </div>

        <!-- Canvas -->
        <div class="relative w-full h-[600px] bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700 select-none">
            <!-- Grid Background (Optional) -->
            <div class="absolute inset-0 pointer-events-none opacity-10" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>

            <template x-for="table in tables" :key="table.id">
                <div
                    class="absolute flex items-center justify-center cursor-move shadow-sm border-2 transition-colors"
                    :class="{
                        'border-primary-500 bg-primary-50 dark:bg-primary-900/20': activeTable !== null && tables[activeTable]?.id === table.id,
                        'border-gray-400 bg-white dark:bg-gray-700': activeTable === null || tables[activeTable]?.id !== table.id,
                        'rounded-full': table.shape === 'circle',
                        'rounded': table.shape === 'rect',
                    }"
                    :style="`left: ${table.x}px; top: ${table.y}px; width: ${table.width}px; height: ${table.height}px; transform: rotate(${table.rotation}deg);`"
                    @mousedown="startDrag($event, table.id)">
                    <div class="text-center relative z-10 w-full h-full flex flex-col items-center justify-center">
                        <span class="block font-bold text-xs" x-text="table.name"></span>
                        <span class="block text-[10px] text-gray-500 dark:text-gray-400" x-text="table.seats + ' seats'"></span>
                    </div>

                    <!-- Chairs -->
                    <template x-for="i in parseInt(table.seats)" :key="i">
                        <div
                            class="absolute w-3 h-3 bg-gray-300 dark:bg-gray-600 border border-gray-400 dark:border-gray-500 rounded-full"
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
    </div>
</x-filament-panels::page>
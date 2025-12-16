<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Calendar Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    @if($viewMode === 'week')
                    Week of {{ \Carbon\Carbon::parse($currentDate)->startOfWeek()->format('M j, Y') }}
                    @else
                    {{ \Carbon\Carbon::parse($currentDate)->format('l, F j, Y') }}
                    @endif
                </h2>
            </div>

            <div class="flex items-center gap-2">
                {{-- View Switcher --}}
                <div class="inline-flex rounded-lg border border-gray-200 dark:border-gray-700 p-1">
                    <button
                        wire:click="switchView('week')"
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $viewMode === 'week' ? 'bg-primary-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        Week
                    </button>
                    <button
                        wire:click="switchView('day')"
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $viewMode === 'day' ? 'bg-primary-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        Day
                    </button>
                </div>

                {{-- Navigation --}}
                <div class="flex items-center gap-2">
                    <button
                        wire:click="previousPeriod"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        wire:click="goToToday"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        Today
                    </button>
                    <button
                        wire:click="nextPeriod"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                {{-- Create Event Button --}}
                <button
                    wire:click="openCreateEventModal"
                    class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors text-sm font-medium">
                    + New Event
                </button>
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="overflow-x-auto">
            <div class="min-w-full">
                {{-- Day Headers --}}
                <div class="grid {{ $viewMode === 'week' ? 'grid-cols-8' : 'grid-cols-2' }} border-b border-gray-200 dark:border-gray-700">
                    <div class="p-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                        Time
                    </div>
                    @foreach($this->days as $day)
                    <div class="p-4 text-center">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $day->format('D') }}
                        </div>
                        <div class="text-2xl font-bold {{ $day->isToday() ? 'text-primary-500' : 'text-gray-900 dark:text-white' }}">
                            {{ $day->format('j') }}
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Time Slots Grid --}}
                <div class="relative">
                    @foreach($this->timeSlots as $timeSlot)
                    <div class="grid {{ $viewMode === 'week' ? 'grid-cols-8' : 'grid-cols-2' }} border-b border-gray-100 dark:border-gray-800">
                        {{-- Time Label --}}
                        <div class="p-2 text-xs text-gray-500 dark:text-gray-400 border-r border-gray-100 dark:border-gray-800">
                            {{ \Carbon\Carbon::createFromFormat('H:i', $timeSlot)->format('g:i A') }}
                        </div>

                        {{-- Day Cells --}}
                        @foreach($this->days as $day)
                        @php
                        $cellDateTime = $day->format('Y-m-d') . ' ' . $timeSlot;
                        @endphp
                        <div
                            wire:click="openCreateEventModal('{{ $day->format('Y-m-d') }}', '{{ $timeSlot }}')"
                            class="relative p-2 min-h-[60px] border-r border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer transition-colors group">
                            {{-- Events in this slot --}}
                            @foreach($this->getEvents() as $event)
                            @php
                            $eventStart = $event->start_time;
                            $eventEnd = $event->end_time;
                            $slotStart = \Carbon\Carbon::parse($cellDateTime);
                            $slotEnd = $slotStart->copy()->addMinutes($viewMode === 'day' ? 15 : 30);

                            // Check if event overlaps with this slot
                            $overlaps = $eventStart->lt($slotEnd) && $eventEnd->gt($slotStart);
                            $isFirstSlot = $eventStart->format('Y-m-d H:i') === $cellDateTime;
                            @endphp

                            @if($overlaps && $isFirstSlot)
                            @php
                            $durationMinutes = $eventStart->diffInMinutes($eventEnd);
                            $slotHeight = $viewMode === 'day' ? 60 : 60; // Height of one slot in pixels
                            $slotDuration = $viewMode === 'day' ? 15 : 30;
                            $eventHeight = ($durationMinutes / $slotDuration) * $slotHeight;
                            @endphp
                            <div
                                wire:click.stop="openEventDetailsModal({{ $event->id }})"
                                class="absolute left-1 right-1 rounded-lg p-2 text-xs font-medium text-white shadow-sm hover:shadow-md transition-shadow cursor-pointer overflow-hidden"
                                style="
                                                    background-color: {{ $event->event_color }};
                                                    height: {{ min($eventHeight, 200) }}px;
                                                    z-index: 10;
                                                ">
                                <div class="font-semibold truncate">{{ $event->title }}</div>
                                <div class="text-xs opacity-90 truncate">
                                    {{ $event->start_time->format('g:i A') }}
                                </div>
                            </div>
                            @endif
                            @endforeach

                            {{-- Hover indicator --}}
                            <div class="absolute inset-0 border-2 border-primary-500 rounded opacity-0 group-hover:opacity-20 pointer-events-none transition-opacity"></div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <x-filament-actions::modals />
    </x-filament::section>
</x-filament-widgets::widget>
<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\CalendarEvent;
use App\Services\CalendarService;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class CalendarWidget extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected string $view = 'filament.dashboard.widgets.calendar-widget';

    protected int|string|array $columnSpan = 'full';

    public string $viewMode = 'week'; // 'week' or 'day'
    public string $currentDate;
    public ?array $selectedSlot = null;

    protected \App\Services\TimezoneService $timezoneService;

    public function boot(\App\Services\TimezoneService $timezoneService): void
    {
        $this->timezoneService = $timezoneService;
    }

    public function mount(): void
    {
        $this->currentDate = now()->toDateString();
    }

    /**
     * Get current tenant's timezone for display.
     */
    public function getTenantTimezoneProperty(): string
    {
        return $this->timezoneService->getCurrentTenantTimezone();
    }

    /**
     * Get timezone offset for display (e.g., "UTC-8").
     */
    public function getTimezoneOffsetProperty(): string
    {
        return $this->timezoneService->getTimezoneOffset();
    }

    public function getEvents()
    {
        $service = app(CalendarService::class);
        $current = Carbon::parse($this->currentDate);

        if ($this->viewMode === 'week') {
            $start = $current->copy()->startOfWeek();
            $end = $current->copy()->endOfWeek();
        } else {
            $start = $current->copy()->startOfDay();
            $end = $current->copy()->endOfDay();
        }

        return $service->getEventsForDateRange($start, $end);
    }

    public function getDaysProperty()
    {
        $current = Carbon::parse($this->currentDate);

        if ($this->viewMode === 'week') {
            $days = [];
            $start = $current->copy()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $days[] = $start->copy()->addDays($i);
            }
            return $days;
        }

        return [$current];
    }

    public function getTimeSlotsProperty()
    {
        $slots = [];
        $increment = $this->viewMode === 'day' ? 15 : 30; // 15min for day, 30min for week

        // Full 24-hour support: 00:00 to 23:59
        for ($hour = 0; $hour <= 23; $hour++) {
            for ($min = 0; $min < 60; $min += $increment) {
                $time = sprintf('%02d:%02d', $hour, $min);
                $slots[] = $time;
            }
        }

        return $slots;
    }

    public function switchView(string $mode): void
    {
        $this->viewMode = $mode;
    }

    public function nextPeriod(): void
    {
        $current = Carbon::parse($this->currentDate);
        $this->currentDate = $this->viewMode === 'week'
            ? $current->addWeek()->toDateString()
            : $current->addDay()->toDateString();
    }

    public function previousPeriod(): void
    {
        $current = Carbon::parse($this->currentDate);
        $this->currentDate = $this->viewMode === 'week'
            ? $current->subWeek()->toDateString()
            : $current->subDay()->toDateString();
    }

    public function goToToday(): void
    {
        $this->currentDate = now()->toDateString();
    }

    /**
     * Refresh calendar data.
     */
    public function refreshCalendar(): void
    {
        // Dispatch event to force re-render
        $this->dispatch('calendar-refreshed');

        // Reset to current view
        $this->js('$wire.$refresh()');
    }

    /**
     * Get header actions for the widget.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn() => $this->refreshCalendar()),
            Action::make('today')
                ->label('Today')
                ->icon('heroicon-o-calendar')
                ->color('primary')
                ->action(fn() => $this->goToToday()),
        ];
    }

    public function openCreateEventModal(?string $date = null, ?string $time = null): void
    {
        $this->selectedSlot = [
            'date' => $date ?? now()->toDateString(),
            'time' => $time ?? '09:00',
        ];

        $this->mountAction('createEvent');
    }

    public function openEventDetailsModal(int $eventId): void
    {
        $this->mountAction('viewEvent', ['event' => $eventId]);
    }

    public function createEventAction(): Action
    {
        return Action::make('createEvent')
            ->label('Create Event')
            ->form([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->rows(3),
                Forms\Components\Select::make('event_type')
                    ->label('Event Type')
                    ->options([
                        'appointment' => 'Appointment',
                        'personal' => 'Personal Event',
                        'time_off' => 'Time Off',
                    ])
                    ->required()
                    ->default('appointment'),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(fn() => $this->selectedSlot['date'] ?? now()->toDateString()),
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->seconds(false)
                    ->default(fn() => $this->selectedSlot['time'] ?? '09:00'),
                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->seconds(false)
                    ->default(fn() => Carbon::createFromFormat('H:i', $this->selectedSlot['time'] ?? '09:00')->addHour()->format('H:i')),
                Forms\Components\Toggle::make('all_day')
                    ->label('All Day Event'),
                Forms\Components\ColorPicker::make('color')
                    ->label('Event Color'),
            ])
            ->action(function (array $data) {
                $service = app(CalendarService::class);

                // Combine date and time
                $data['start_time'] = Carbon::parse($data['date'] . ' ' . $data['start_time']);
                $data['end_time'] = Carbon::parse($data['date'] . ' ' . $data['end_time']);
                unset($data['date']);

                $service->createEvent($data);

                $this->dispatch('eventCreated');
            })
            ->modalWidth('lg');
    }

    public function viewEventAction(): Action
    {
        return Action::make('viewEvent')
            ->label('Event Details')
            ->fillForm(function (array $arguments): array {
                $event = CalendarEvent::find($arguments['event']);

                if (!$event) {
                    return [];
                }

                return [
                    'title' => $event->title,
                    'description' => $event->description ?? 'No description',
                    'event_type' => ucfirst($event->event_type),
                    'time_range' => $event->formatted_time_range,
                    'is_reservation' => $event->isReservationEvent(),
                    'event_id' => $event->id,
                ];
            })
            ->form([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->disabled(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->disabled()
                    ->rows(2),
                Forms\Components\TextInput::make('event_type')
                    ->label('Event Type')
                    ->disabled(),
                Forms\Components\TextInput::make('time_range')
                    ->label('Time')
                    ->disabled(),
                Forms\Components\Placeholder::make('reservation_note')
                    ->label('Note')
                    ->content('This event is linked to a reservation and cannot be edited manually.')
                    ->visible(fn($get) => $get('is_reservation') === true),
                Forms\Components\Hidden::make('event_id'),
                Forms\Components\Hidden::make('is_reservation'),
            ])
            ->modalSubmitAction(false)
            ->extraModalFooterActions(function (array $arguments) {
                $event = CalendarEvent::find($arguments['event']);

                if (!$event || $event->isReservationEvent()) {
                    return [];
                }

                return [
                    Action::make('edit')
                        ->label('Edit Event')
                        ->icon('heroicon-o-pencil')
                        ->url(route('filament.dashboard.resources.calendar-events.edit', ['record' => $event->id])),
                    Action::make('delete')
                        ->label('Delete')
                        ->icon('heroicon-o-trash')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Event')
                        ->modalDescription('Are you sure you want to delete this event? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete it')
                        ->action(function () use ($event) {
                            $service = app(CalendarService::class);
                            $service->deleteEvent($event);
                        })
                        ->after(function () {
                            // Refresh calendar
                            $this->dispatch('eventDeleted');

                            // Show success notification
                            \Filament\Notifications\Notification::make()
                                ->title('Event deleted successfully')
                                ->success()
                                ->send();

                            // Force refresh the component
                            $this->js('window.location.reload()');
                        })
                        ->color('danger')
                        ->closeModalByClickingAway(false),
                ];
            })
            ->modalWidth('lg');
    }
}

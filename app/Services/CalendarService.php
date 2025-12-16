<?php

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarService
{
    protected TimezoneService $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
    }

    /**
     * Create a new calendar event.
     */
    public function createEvent(array $data): CalendarEvent
    {
        // Set creator if not provided
        if (!isset($data['created_by'])) {
            $data['created_by'] = Auth::id();
        }

        // Convert times from tenant timezone to UTC
        $data = $this->convertTimesToUTC($data);

        // Validate and adjust times
        $data = $this->validateAndAdjustTimes($data);

        return CalendarEvent::create($data);
    }

    /**
     * Update an existing calendar event.
     */
    public function updateEvent(CalendarEvent $event, array $data): CalendarEvent
    {
        // Don't allow editing reservation-linked events
        if ($event->isReservationEvent()) {
            throw new \Exception('Cannot manually edit reservation-linked events.');
        }

        // Convert times from tenant timezone to UTC
        $data = $this->convertTimesToUTC($data);

        // Validate and adjust times
        $data = $this->validateAndAdjustTimes($data);

        $event->update($data);
        return $event->fresh();
    }

    /**
     * Delete a calendar event.
     */
    public function deleteEvent(CalendarEvent $event): bool
    {
        // Don't allow deleting reservation-linked events
        if ($event->isReservationEvent()) {
            throw new \Exception('Cannot manually delete reservation-linked events.');
        }

        return $event->delete();
    }

    /**
     * Get events for a specific date range.
     */
    public function getEventsForDateRange(Carbon $start, Carbon $end): \Illuminate\Database\Eloquent\Collection
    {
        return CalendarEvent::with(['reservation', 'creator'])
            ->inDateRange($start, $end)
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Sync a reservation to the calendar.
     */
    public function syncReservationToCalendar(Reservation $reservation): CalendarEvent
    {
        // Check if calendar event already exists
        $event = $reservation->calendarEvent;

        $eventData = [
            'title' => "Reservation: {$reservation->guest_name}",
            'description' => $this->buildReservationDescription($reservation),
            'event_type' => 'reservation',
            'start_time' => $reservation->reservation_time,
            'end_time' => $reservation->reservation_time->copy()->addHours(2), // Default 2-hour duration
            'all_day' => false,
            'reservation_id' => $reservation->id,
            'metadata' => [
                'party_size' => $reservation->party_size,
                'table_id' => $reservation->table_id,
                'status' => $reservation->status,
            ],
            'created_by' => Auth::id() ?? 1, // Fallback to system user
        ];

        if ($event) {
            $event->update($eventData);
            return $event->fresh();
        }

        return CalendarEvent::create($eventData);
    }

    /**
     * Build description for reservation event.
     */
    protected function buildReservationDescription(Reservation $reservation): string
    {
        $parts = [
            "Party of {$reservation->party_size}",
        ];

        if ($reservation->table) {
            $parts[] = "Table: {$reservation->table->name}";
        }

        if ($reservation->special_requests) {
            $parts[] = "Notes: {$reservation->special_requests}";
        }

        return implode(' | ', $parts);
    }

    /**
     * Convert times to UTC for storage.
     */
    protected function convertTimesToUTC(array $data): array
    {
        if (isset($data['start_time'])) {
            $data['start_time'] = $this->timezoneService->convertToUTC(
                Carbon::parse($data['start_time'])
            );
        }

        if (isset($data['end_time'])) {
            $data['end_time'] = $this->timezoneService->convertToUTC(
                Carbon::parse($data['end_time'])
            );
        }

        return $data;
    }

    /**
     * Validate and adjust event times.
     */
    protected function validateAndAdjustTimes(array $data): array
    {
        // Ensure start_time is before end_time
        if (isset($data['start_time']) && isset($data['end_time'])) {
            $start = Carbon::parse($data['start_time']);
            $end = Carbon::parse($data['end_time']);

            if ($start->greaterThanOrEqualTo($end)) {
                throw new \Exception('End time must be after start time.');
            }
        }

        // If all_day is true, set times to start/end of day
        if (isset($data['all_day']) && $data['all_day']) {
            if (isset($data['start_time'])) {
                $data['start_time'] = Carbon::parse($data['start_time'])->startOfDay();
                $data['end_time'] = Carbon::parse($data['start_time'])->endOfDay();
            }
        }

        return $data;
    }
}

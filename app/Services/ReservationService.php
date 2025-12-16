<?php

namespace App\Services;

use App\Models\Table;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ReservationService
{
    /**
     * Check if table availability for a specific time and party size.
     * Note: This is a basic implementation. A real system would check time slots overlap.
     */
    public function checkAvailability(Carbon $time, int $partySize): Collection
    {
        // 1. Find tables with enough capacity
        $tables = Table::where('capacity', '>=', $partySize)
                       ->where('status', 'available')
                       ->get();

        // 2. Filter out tables that are already reserved at this time (assuming 2 hours duration)
        // This is a simplified check.
        $availableTables = $tables->filter(function ($table) use ($time) {
            $conflictingReservation = $table->reservations()
                ->whereIn('status', ['confirmed', 'seated'])
                ->whereBetween('reservation_time', [
                     $time->copy()->subHours(2), 
                     $time->copy()->addHours(2)
                ])
                ->exists();
            
            return !$conflictingReservation;
        });

        return $availableTables;
    }

    /**
     * Create a new reservation.
     */
    public function createReservation(array $data): Reservation
    {
        // Validate or manipulate data if needed
        return Reservation::create($data);
    }

    /**
     * Assign a table to a reservation.
     */
    public function assignTable(Reservation $reservation, Table $table): bool
    {
        // Verify capacity
        if ($table->capacity < $reservation->party_size) {
            // Can throw exception or handle error
            // mostly for UI feedback, but strict service might want to block it
            // For now, allow override but maybe log warning (or just proceed)
        }

        $reservation->table_id = $table->id;
        $reservation->status = 'confirmed'; // Auto-confirm when table is assigned? 
        return $reservation->save();
    }
}

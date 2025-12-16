<?php

namespace App\Policies;

use App\Models\CalendarEvent;
use App\Models\User;

class CalendarEventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view calendar
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CalendarEvent $calendarEvent): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create manual events
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CalendarEvent $calendarEvent): bool
    {
        // Cannot edit reservation-linked events
        if ($calendarEvent->isReservationEvent()) {
            return false;
        }

        // Only creator or business_owner can edit
        return $calendarEvent->created_by === $user->id
            || $user->hasRole('business_owner');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CalendarEvent $calendarEvent): bool
    {
        // Cannot delete reservation-linked events
        if ($calendarEvent->isReservationEvent()) {
            return false;
        }

        // Only creator or business_owner can delete
        return $calendarEvent->created_by === $user->id
            || $user->hasRole('business_owner');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->hasRole('business_owner');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CalendarEvent $calendarEvent): bool
    {
        return $user->hasRole('business_owner');
    }
}

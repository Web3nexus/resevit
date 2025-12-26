<?php

namespace App\Policies;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class CalendarEventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;

        // Check if reservations feature is enabled
        return has_feature('reservations');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, CalendarEvent $calendarEvent): bool
    {
        if ($user instanceof Admin)
            return true;
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin)
            return true;
        return true; // All authenticated users can create manual events
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, CalendarEvent $calendarEvent): bool
    {
        if ($user instanceof Admin)
            return true;

        // Cannot edit reservation-linked events
        if ($calendarEvent->isReservationEvent()) {
            return false;
        }

        // Only creator or business_owner can edit
        return ($user instanceof User && $calendarEvent->created_by === $user->id)
            || ($user instanceof User && $user->hasRole('business_owner'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, CalendarEvent $calendarEvent): bool
    {
        if ($user instanceof Admin)
            return true;

        // Cannot delete reservation-linked events
        if ($calendarEvent->isReservationEvent()) {
            return false;
        }

        // Only creator or business_owner can delete
        return ($user instanceof User && $calendarEvent->created_by === $user->id)
            || ($user instanceof User && $user->hasRole('business_owner'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Authenticatable $user, CalendarEvent $calendarEvent): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole('business_owner');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Authenticatable $user, CalendarEvent $calendarEvent): bool
    {
        if ($user instanceof Admin)
            return true;
        return $user instanceof User && $user->hasRole('business_owner');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CalendarEvent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'start_time',
        'end_time',
        'all_day',
        'reservation_id',
        'color',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'all_day' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Default colors for event types
     */
    const COLORS = [
        'reservation' => '#3b82f6', // Blue
        'appointment' => '#10b981', // Green
        'personal' => '#8b5cf6',    // Purple
        'time_off' => '#ef4444',    // Red
    ];

    /**
     * Get the reservation associated with this event.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the user who created this event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(TenantUser::class, 'created_by');
    }

    /**
     * Scope to filter events within a date range.
     */
    public function scopeInDateRange(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->where(function ($q) use ($start, $end) {
            $q->whereBetween('start_time', [$start, $end])
                ->orWhereBetween('end_time', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('start_time', '<=', $start)
                        ->where('end_time', '>=', $end);
                });
        });
    }

    /**
     * Scope to filter by event type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope to exclude reservation-linked events.
     */
    public function scopeManualOnly(Builder $query): Builder
    {
        return $query->whereNull('reservation_id');
    }

    /**
     * Check if this event is linked to a reservation.
     */
    public function isReservationEvent(): bool
    {
        return $this->reservation_id !== null;
    }

    /**
     * Get formatted time range for display.
     */
    public function getFormattedTimeRangeAttribute(): string
    {
        if ($this->all_day) {
            return 'All Day';
        }

        return $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
    }

    /**
     * Get the event color (use default if not set).
     */
    public function getEventColorAttribute(): string
    {
        return $this->color ?? self::COLORS[$this->event_type] ?? '#6b7280';
    }

    /**
     * Get duration in minutes.
     */
    public function getDurationInMinutesAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Get start time in tenant's timezone.
     */
    public function getStartTimeInTenantTimezoneAttribute(): Carbon
    {
        $timezoneService = app(\App\Services\TimezoneService::class);
        return $timezoneService->convertToTenantTimezone($this->start_time);
    }

    /**
     * Get end time in tenant's timezone.
     */
    public function getEndTimeInTenantTimezoneAttribute(): Carbon
    {
        $timezoneService = app(\App\Services\TimezoneService::class);
        return $timezoneService->convertToTenantTimezone($this->end_time);
    }

    /**
     * Get formatted time range in tenant's timezone.
     */
    public function getFormattedTimeRangeInTenantTimezoneAttribute(): string
    {
        if ($this->all_day) {
            return 'All Day';
        }

        return $this->start_time_in_tenant_timezone->format('g:i A') . ' - ' .
            $this->end_time_in_tenant_timezone->format('g:i A');
    }

    /**
     * Boot method to set default color.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (!$event->color && isset(self::COLORS[$event->event_type])) {
                $event->color = self::COLORS[$event->event_type];
            }
        });
    }
}

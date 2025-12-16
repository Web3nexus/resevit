<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'table_id',
        'customer_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'party_size',
        'reservation_time',
        'duration_minutes',
        'status',
        'special_requests',
        'confirmed_at',
        'reminder_sent_at',
        'confirmation_code',
    ];

    protected $casts = [
        'party_size' => 'integer',
        'duration_minutes' => 'integer',
        'reservation_time' => 'datetime',
        'confirmed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'customer_id' => 'integer',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the calendar event associated with this reservation.
     */
    public function calendarEvent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CalendarEvent::class);
    }

    // Customer relationship logic will be refined when Customer module is fully integrated (global vs tenant)
    // For now we just store the ID.

    // Accessors & Helpers

    /**
     * Get the computed end time based on reservation_time + duration.
     */
    public function getEndTimeAttribute(): \Carbon\Carbon
    {
        return $this->reservation_time->copy()->addMinutes($this->duration_minutes ?? 120);
    }

    /**
     * Check if reservation is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed' && $this->confirmed_at !== null;
    }

    /**
     * Check if reservation needs reminder.
     */
    public function needsReminder(int $hoursBeforeReservation): bool
    {
        if ($this->reminder_sent_at) {
            return false; // Already sent
        }

        $reminderTime = $this->reservation_time->copy()->subHours($hoursBeforeReservation);
        return now()->greaterThanOrEqualTo($reminderTime) && now()->lessThan($this->reservation_time);
    }

    /**
     * Generate unique confirmation code.
     */
    public static function generateConfirmationCode(): string
    {
        return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }

    // Scopes
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('reservation_time', '>=', now())
            ->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('reservation_time', today());
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}

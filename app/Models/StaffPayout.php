<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffPayout extends Model
{
    protected $fillable = [
        'staff_id',
        'amount',
        'payout_date',
        'hours_worked',
        'notes',
        'status',
    ];

    protected $casts = [
        'payout_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the staff member associated with this payout.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Scope to get only paid payouts.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope to get only pending payouts.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Mark this payout as paid.
     */
    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    /**
     * Cancel this payout.
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}

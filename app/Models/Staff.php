<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'position',
        'phone',
        'emergency_contact',
        'hire_date',
        'hourly_rate',
        'status',
        'availability',
    ];

    protected $casts = [
        'availability' => 'array',
        'hire_date' => 'date',
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * Get the user associated with this staff member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(TenantUser::class);
    }

    /**
     * Get all payouts for this staff member.
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(StaffPayout::class);
    }

    /**
     * Get the total amount paid to this staff member.
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payouts()
            ->where('status', 'paid')
            ->sum('amount');
    }

    /**
     * Get the pending payout amount for this staff member.
     */
    public function getPendingPayoutAttribute(): float
    {
        return $this->payouts()
            ->where('status', 'pending')
            ->sum('amount');
    }
}

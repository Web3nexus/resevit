<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\BelongsToBranch;

class Staff extends Model
{
    protected $connection = 'tenant';
    use SoftDeletes, BelongsToBranch;



    protected $table = 'staff';

    protected $fillable = [
        'branch_id',
        'user_id',
        'position',
        'date_of_birth',
        'phone',
        'address',
        'emergency_contact',
        'hire_date',
        'hourly_rate',
        'status',
        'availability',
        'bank_name',
        'account_holder_name',
        'account_number',
        'branch_code',
        'swift_bic',
        'tenant_id',
    ];

    protected $casts = [
        'availability' => 'array',
        'hire_date' => 'date',
        'date_of_birth' => 'date',
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

    /**
     * Get all tasks assigned to this staff member.
     */
    public function receivedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to_staff_id');
    }

    /**
     * Get all work logs for this staff member.
     */
    public function workLogs(): HasMany
    {
        return $this->hasMany(StaffWorkLog::class);
    }
}

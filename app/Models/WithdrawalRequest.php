<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends TenantModel
{
    protected $connection = 'landlord';

    protected $fillable = [
        'requester_id',
        'requester_type',
        'amount',
        'currency',
        'status',
        'bank_details',
        'admin_note',
        'processed_at',
    ];

    protected $casts = [
        'bank_details' => 'array',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function requester(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relationship for when the requester is an Influencer
     * This is used by Filament forms
     */
    public function influencer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Influencer::class, 'requester_id');
    }
}

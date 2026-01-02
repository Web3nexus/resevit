<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WithdrawalRequest extends Model
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
}

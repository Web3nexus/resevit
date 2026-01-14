<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends TenantModel
{
    protected $connection = 'landlord';

    protected $fillable = [
        'user_id',
        'transactionable_type',
        'transactionable_id',
        'amount',
        'type',
        'status',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionable()
    {
        return $this->morphTo();
    }
}

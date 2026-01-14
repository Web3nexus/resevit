<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $connection = 'tenant';


    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'notes',
    ];

    protected $guarded = [
        'id',
        'amount',      // Should match order total
        'status',      // Controlled by payment gateway
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $connection = 'tenant';
    

    protected $guarded = [];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // public function customer() relation depends on if Customer is in global or tenant.
    // If Global, we might need a custom relation or just store ID.

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}

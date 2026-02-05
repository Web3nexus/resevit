<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $connection = 'tenant';

    use BelongsToBranch;

    protected $fillable = [
        'order_number',
        'table_id',
        'staff_id',
        'branch_id',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'order_type',
        'status',
        'notes',
        'items',
        'subtotal',
        'items_total',
        'tax',
        'tax_total',
        'service_fee',
        'delivery_fee',
        'total',
        'total_amount',
        'payment_intent_id',
        'payment_status',
        'paid_at',
        'delivery_address',
        'order_source',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'items_total' => 'decimal:2',
        'tax' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOnline($query)
    {
        return $query->where('order_source', 'online');
    }

    // Accessors
    public function getOrderNumberAttribute(): string
    {
        return 'ORD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}

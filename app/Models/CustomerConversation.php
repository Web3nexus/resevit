<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerConversation extends Model
{
    protected $connection = 'tenant';
    protected $connection = 'landlord';

    protected $fillable = ['customer_id', 'tenant_id', 'status'];

    public function messages(): HasMany
    {
        return $this->hasMany(CustomerMessage::class, 'customer_conversation_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

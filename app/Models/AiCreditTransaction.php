<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiCreditTransaction extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'tenant_id',
        'amount',
        'type',
        'description',
        'provider',
        'model',
        'tokens_input',
        'tokens_output',
        'actual_cost',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:6',
        'actual_cost' => 'decimal:8',
        'metadata' => 'json',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

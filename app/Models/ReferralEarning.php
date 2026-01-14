<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReferralEarning extends TenantModel
{
    protected $table = 'referral_earnings';

    protected $connection = 'landlord';

    protected $fillable = [
        'earner_id',
        'earner_type',
        'referral_id',
        'tenant_id',
        'amount',
        'currency',
        'status',
        'description',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function earner(): MorphTo
    {
        return $this->morphTo();
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Referral extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'referrer_id',
        'referrer_type',
        'tenant_id',
        'referral_code',
        'ip_address',
        'user_agent',
        'converted_at',
    ];

    protected $casts = [
        'converted_at' => 'datetime',
    ];

    public function referrer(): MorphTo
    {
        return $this->morphTo();
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(ReferralEarning::class, 'referral_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReferralLinkClick extends TenantModel
{
    protected $connection = 'landlord';

    protected $fillable = [
        'referrer_id',
        'referrer_type',
        'ip_address',
        'user_agent',
        'device_type',
    ];

    public function referrer(): MorphTo
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Influencer extends Model
{
    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'email',
        'referral_code',
        'status',
        'bio',
        'website',
        'social_links',
        'stripe_account_id',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(InfluencerEarning::class);
    }

    public function getPendingEarningsAttribute()
    {
        return $this->earnings()->where('status', 'pending')->sum('amount');
    }

    public function getPaidEarningsAttribute()
    {
        return $this->earnings()->where('status', 'paid')->sum('amount');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;

class Influencer extends Authenticatable
{
    use Notifiable;

    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code',
        'status',
        'niche',
        'audience_size',
        'bio',
        'website',
        'social_links',
        'stripe_account_id',
        'bank_name',
        'account_name',
        'account_number',
        'iban',
        'swift_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function referrals(): MorphMany
    {
        return $this->morphMany(Referral::class, 'referrer');
    }

    public function linkClicks()
    {
        return $this->morphMany(ReferralLinkClick::class, 'referrer');
    }

    public function referralEarnings(): MorphMany
    {
        return $this->morphMany(ReferralEarning::class, 'earner');
    }

    public function withdrawalRequests(): MorphMany
    {
        return $this->morphMany(WithdrawalRequest::class, 'requester');
    }

    public function getPendingEarningsAttribute()
    {
        return $this->referralEarnings()->where('status', 'pending')->sum('amount');
    }

    public function getPaidEarningsAttribute()
    {
        return $this->referralEarnings()->where('status', 'paid')->sum('amount');
    }
}

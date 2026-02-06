<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Sanctum\HasApiTokens;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasApiTokens, HasFactory, HasRoles, LogsActivity, Notifiable;

    protected $connection = 'landlord';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Relation: a user may own many tenants (business owners).
     */
    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'owner_user_id');
    }

    /**
     * Helper for mobile app to get the primary tenant.
     */
    public function currentTenant()
    {
        return $this->hasOne(Tenant::class, 'owner_user_id')->latest();
    }

    public function getCurrentTenantAttribute()
    {
        return $this->tenants->first();
    }

    /**
     * Filament (and other packages) sometimes call getTenants() on the user.
     * Provide a compatibility method that returns the tenant collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTenants()
    {
        return $this->tenants()->get();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'terms_accepted',
        'newsletter_subscribed',
        'avatar_url',
        'mobile',
        'country',
        'currency',
        'timezone',
        'locale',
        'wallet_balance',
        'referral_code',
        'bank_name',
        'account_name',
        'account_number',
        'iban',
        'swift_code',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'terms_accepted' => 'boolean',
            'newsletter_subscribed' => 'boolean',
            'wallet_balance' => 'decimal:2',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Hash password automatically when setting the attribute.
     */
    public function setPasswordAttribute(?string $value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['password'] = $value;
            return;
        }

        // Avoid double-hashing if a hashed value is provided.
        if (preg_match('/^\$2y\$|^\$argon2i\$|^\$argon2id\$/', $value)) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = \Illuminate\Support\Facades\Hash::make($value);
    }

    use \App\Traits\HasTwoFactorAuthentication;

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'dashboard') {
            return true;
        }

        return false;
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->referral_code) {
                $user->referral_code = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8));
            }
        });
    }

    public function referrals()
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

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\Auth\VerifyEmail);
    }

    /**
     * Get the onboarding status from the first tenant.
     */
    public function getOnboardingStatusAttribute()
    {
        if ($this->hasRole('Business Owner')) {
            $tenant = $this->tenants()->first();
            return $tenant?->onboarding_status ?? 'active';
        }
        return 'active';
    }

    /**
     * Append onboarding_status to JSON.
     */
    protected $appends = ['onboarding_status'];
}

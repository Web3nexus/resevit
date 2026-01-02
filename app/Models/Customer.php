<?php

namespace App\Models;

use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $password
 * @property string|null $avatar
 * @property string|null $address
 */
class Customer extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $connection = 'landlord';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'address',
        'referral_code',
        'bank_name',
        'account_name',
        'account_number',
        'iban',
        'swift_code',
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
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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

        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Filament access control: only allow users with the 'customer' role.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('customer');
    }

    protected static function booted()
    {
        static::creating(function ($customer) {
            if (!$customer->referral_code) {
                $customer->referral_code = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8));
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
}

<?php

namespace App\Models;

use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Investor
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $phone
 * @property float $wallet_balance
 */
class Investor extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\InvestorFactory> */
    use HasFactory, Notifiable, HasRoles, \App\Traits\HasTwoFactorAuthentication;

    protected $connection = 'landlord';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'investors';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'wallet_balance',
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
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'wallet_balance' => 'decimal:2',
        'password' => 'hashed',
        'two_factor_confirmed_at' => 'datetime',
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

        // Always hash plaintext password values. If you supply an already hashed value,
        // it's the caller's responsibility — but we defensively avoid double-hashing by
        // checking common hashed prefixes.
        if (preg_match('/^\$2y\$|^\$argon2i\$|^\$argon2id\$/', $value)) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Filament access control: only allow users with the 'investor' role.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('investor');
    }

    /**
     * Get the investments for the investor.
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    /**
     * Get the payouts for the investor.
     */
    public function payouts()
    {
        return $this->hasMany(InvestorPayout::class);
    }
}

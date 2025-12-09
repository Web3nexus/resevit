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
 * @property string $name
 * @property string $email
 * @property string $password
 */
class Admin extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Automatically hash the password when set.
     */
    public function setPasswordAttribute(?string $value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['password'] = $value;
            return;
        }

        // If it looks like a hashed value (bcrypt/argon), keep as-is.
        if (preg_match('/^\$2y\$|^\$argon2i\$|^\$argon2id\$/', $value)) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Determine if the user can access Filament.
     * Only allow users with specific securegate roles.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole([
            'securegate_admin',
            'securegate_support',
            'securegate_marketing',
        ]);
    }
}

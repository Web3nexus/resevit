<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation: a user may own many tenants (business owners).
     */
    public function ownedTenants()
    {
        return $this->hasMany(Tenant::class, 'owner_user_id');
    }

    /**
     * Relation: a user may be a member of many tenants.
     */
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class);
    }

    /**
     * Get the tenants that the user has access to.
     */
    public function getTenants(Panel $panel): Collection
    {
        return $this->tenants;
    }

    /**
     * Check if the user can access the given tenant.
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenants->contains($tenant);
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'securegate' => $this->hasRole('super-admin'),
            'dashboard' => $this->hasRole('business-owner'),
            'invest' => $this->hasRole('investor'),
            'customer' => $this->hasRole('customer'),
            default => false,
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Cashier;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasFactory, HasDatabase, HasDomains, Billable;

    protected $connection = 'landlord';


    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot the model and assign a UUID when creating if none provided.
     */
    protected static function booted()
    {
        static::creating(function (Tenant $tenant) {
            if (empty($tenant->{$tenant->getKeyName()})) {
                $tenant->{$tenant->getKeyName()} = (string) Str::uuid();
            }
        });
    }
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'database_name',
        'owner_user_id',
        'status',
        'mobile',
        'country',
        'staff_count',
        'timezone',
        'currency',
        'trial_ends_at',
        'plan_id',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'staff_count' => 'integer',
        'data' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function plan()
    {
        return $this->belongsTo(PricingPlan::class, 'plan_id');
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'domain',
            'database_name',
            'owner_user_id',
            'status',
            'mobile',
            'country',
            'staff_count',
            'timezone',
            'currency',
            'trial_ends_at',
            'plan_id',
        ];
    }
}

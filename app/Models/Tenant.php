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
        'is_public',
        'is_sponsored',
        'sponsored_ranking',
        'business_category_id',
        'description',
        'cover_image',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'staff_count' => 'integer',
        'data' => 'array',
        'is_public' => 'boolean',
        'is_sponsored' => 'boolean',
        'sponsored_ranking' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function plan()
    {
        return $this->belongsTo(PricingPlan::class, 'plan_id');
    }

    public function businessCategory()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'tenant_id');
    }

    public function averageRating(): float
    {
        return (float) $this->reviews()->where('is_published', true)->avg('rating') ?: 0;
    }

    public function publishedReviews()
    {
        return $this->reviews()->where('is_published', true)->orderByDesc('created_at');
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
            'stripe_id',
            'pm_type',
            'pm_last_four',
            'is_public',
            'is_sponsored',
            'sponsored_ranking',
            'business_category_id',
            'description',
            'cover_image',
            'seo_title',
            'seo_description',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use Billable, HasDatabase, HasDomains, HasFactory;

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

            if (empty($tenant->domain) && !empty($tenant->slug)) {
                $previewDomain = config('tenancy.preview_domain');
                $tenant->domain = $tenant->slug . '.' . $previewDomain;
            }

            if (empty($tenant->timezone)) {
                $tenant->timezone = 'UTC';
            }

            if (empty($tenant->currency)) {
                $tenant->currency = 'USD';
            }
        });

        static::saved(function (Tenant $tenant) {
            $centralDomains = config('tenancy.central_domains', []);
            $previewDomain = config('tenancy.preview_domain');
            $dashboardBase = parse_url(config('app.url'), PHP_URL_HOST);

            $systemDomains = [
                $tenant->slug . '.' . $previewDomain,
                $tenant->slug . '.' . $dashboardBase,
            ];

            $newCustomDomains = array_filter([
                $tenant->website_custom_domain,
                $tenant->dashboard_custom_domain,
            ]);

            $allTenantDomains = array_merge($systemDomains, $newCustomDomains);

            // 1. Create/Update the new domains
            foreach ($allTenantDomains as $domainName) {
                $tenant->domains()->updateOrCreate(['domain' => $domainName]);
            }

            // 2. Cleanup old custom domains that are no longer selected
            $tenant->domains()->get()->each(function ($d) use ($allTenantDomains) {
                if (!in_array($d->domain, $allTenantDomains)) {
                    $d->delete();
                }
            });
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
        'influencer_id',
        'subscription_interval',
        'is_public',
        'is_sponsored',
        'sponsored_ranking',
        'business_category_id',
        'description',
        'cover_image',
        'seo_title',
        'seo_description',
        'whitelabel_logo',
        'whitelabel_active',
        'website_custom_domain',
        'dashboard_custom_domain',
        'staff_range',
        'onboarding_status',
        'onboarding_completed',
        'ai_credits',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'staff_count' => 'integer',
        'data' => 'array',
        'is_public' => 'boolean',
        'is_sponsored' => 'boolean',
        'sponsored_ranking' => 'integer',
        'whitelabel_active' => 'boolean',
        'ai_credits' => 'decimal:6',
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

    public function branches()
    {
        return $this->hasMany(Branch::class, 'tenant_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'tenant_id');
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
            'whitelabel_logo',
            'whitelabel_active',
            'website_custom_domain',
            'dashboard_custom_domain',
            'staff_range',
            'onboarding_status',
            'onboarding_completed',
        ];
    }
}

<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class TenantCreatorService
{
    /**
     * Create a new tenant, including its database, domain, and initial data.
     *
     * @param User $user
     * @param string $tenantName
     * @return Tenant
     */
    public function createTenant(User $user, string $tenantName): Tenant
    {
        $slug = Str::slug($tenantName);

        // Create the tenant record
        $tenant = Tenant::create([
            'name' => $tenantName,
            'slug' => $slug,
            'owner_user_id' => $user->id,
        ]);

        // Create the domain for the tenant
        $tenant->domains()->create([
            'domain' => $slug . '.' . config('tenancy.central_domains')[0],
        ]);

        // Associate the owner with the tenant
        $tenant->users()->attach($user);

        // Create and migrate the tenant's database
        Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
            '--force' => true, // Use --force for production environments
        ]);

        // Seed the tenant's database
        Artisan::call('tenants:seed', [
            '--tenants' => [$tenant->id],
            '--force' => true,
        ]);

        return $tenant;
    }
}

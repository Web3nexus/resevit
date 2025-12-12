<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;

class TenantCreatorService
{
    /**
     * Create a new tenant, domain, and assign owner.
     *
     * @param User $user
     * @param string $restaurantName
     * @param string $subdomain
     * @return Tenant
     */
    public function createTenant(User $user, string $restaurantName, string $subdomain): Tenant
    {
        $tenant = DB::transaction(function () use ($user, $restaurantName, $subdomain) {
            // 1. Create Tenant
            /** @var Tenant $tenant */
            $tenant = Tenant::create([
                'name' => $restaurantName,
                'slug' => $subdomain,
                'domain' => $subdomain . '.' . parse_url(config('app.url'), PHP_URL_HOST), // e.g. pizza.resevit.test
                'owner_user_id' => $user->id,
                'status' => 'active',
            ]);

            // 2. Create Domain (Stancl Tenancy)
            $tenant->domains()->create([
                'domain' => $subdomain . '.' . parse_url(config('app.url'), PHP_URL_HOST),
            ]);
            
            return $tenant;
        });

        // 3. Initialize Tenant Context to Create User in Tenant DB
        $tenant->run(function () use ($user) {
            // Seed tenant roles first
            \Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\TenantRolesSeeder',
                '--force' => true,
            ]);
            
            // Create user in tenant DB
            $tenantUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password, // Password is already hashed
            ]);
            
            // Assign business_owner role
            $tenantUser->assignRole('business_owner');
        });

        return $tenant;
    }
}

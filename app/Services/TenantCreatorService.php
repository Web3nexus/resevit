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
     *
     * @param User $user
     * @param string $restaurantName
     * @param string $subdomain
     * @param array $extraData
     * @return Tenant
     */
    public function createTenant(User $user, string $restaurantName, string $subdomain, array $extraData = []): Tenant
    {
        // 1. Create Tenant
        /** @var Tenant $tenant */
        $tenant = Tenant::create([
            'name' => $restaurantName,
            'slug' => $subdomain,
            // 'database_name' => ... let package generate it (tenant_UUID)
            'domain' => $subdomain . '.' . parse_url(config('app.url'), PHP_URL_HOST), // e.g. pizza.resevit.test
            'owner_user_id' => $user->id,
            'status' => 'active',
            'mobile' => $extraData['mobile'] ?? null,
            'country' => $extraData['country'] ?? null,
            'staff_count' => $extraData['staff_count'] ?? null,
        ]);

        // Get the actual DB name determined by the package (likely tenant_UUID)
        $targetDbName = $tenant->database()->getName();

        // Save it for reference
        if (empty($tenant->database_name)) {
            $tenant->database_name = $targetDbName;
            $tenant->save();
        }

        // 2. Add Domain
        $tenant->domains()->create([
            'domain' => $subdomain . '.' . parse_url(config('app.url'), PHP_URL_HOST), // e.g. pizza.resevit.test
        ]);

        try {
            // Ensure DB exists before initializing
            // This fixes the "Unknown database" error if the event listener fails
            $databaseName = $targetDbName; // Use local variable
            $connection = config('tenancy.database.central_connection');

            // Use specific statement for MySQL to check/create if needed
            // NOTE: Stancl/Tenancy usually handles this, but we are forcing it here for robustness
            if ($databaseName) {
                // Use the tenant's specific database manager (MySQL/Postgres/etc)
                try {
                    $manager = $tenant->database()->manager();
                    if (! $manager->databaseExists($databaseName)) {
                        $manager->createDatabase($tenant);
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Manual DB creation check failed: ' . $e->getMessage());
                }
            }

            tenancy()->initialize($tenant);

            \Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id],
                '--force' => true,
            ]);


            // Paranoia: Manually set the DB config to ensure it works
            // We do this AFTER initialize because initialize might be setting it to null/empty if failing
            if (!empty($tenant->database_name)) {
                config(['database.connections.tenant.database' => $tenant->database_name]);
                \Illuminate\Support\Facades\DB::purge('tenant');
                \Illuminate\Support\Facades\DB::reconnect('tenant');
            }

            \Illuminate\Support\Facades\DB::setDefaultConnection('tenant');

            \Log::info("Tenancy Initialized.");
            \Log::info("Tenant DB Name from Model: " . $tenant->database_name);
            \Log::info("Tenant Connection DB from Driver: " . \DB::connection('tenant')->getDatabaseName());

            // Explicitly run migrations to ensure tables exist before seeding
            \Artisan::call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            \Artisan::call('tenants:seed', [
                '--tenants' => [$tenant->id],
                '--class' => 'Database\\Seeders\\TenantRolesSeeder',
                '--force' => true,
            ]);


            // Create user in tenant DB using TenantUser (explicit connection)
            // Ensure we are using the tenant connection
            $tenantUser = \App\Models\TenantUser::on('tenant')->create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password, // Password is already hashed
            ]);

            // Assign business_owner role
            $role = \Spatie\Permission\Models\Role::on('tenant')->where('name', 'business_owner')->first();
            if ($role) {
                $tenantUser->assignRole('business_owner');
            }
        } catch (\Exception $e) {
            \Log::error("Tenant Creation Failed: " . $e->getMessage());
            throw $e;
        } finally {
            tenancy()->end();
        }

        return $tenant;
    }
}

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
     * @param string|int|null $planId
     * @param string|null $paymentMethodId
     * @return Tenant
     */
    public function createTenant(User $user, string $restaurantName, string $subdomain, array $extraData = [], $planId = null, $paymentMethodId = null): Tenant
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
            'plan_id' => $planId ?: \App\Models\PricingPlan::where('slug', 'starter')->first()?->id,
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
                    if (!$manager->databaseExists($databaseName)) {
                        $manager->createDatabase($tenant);
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Manual DB creation check failed: ' . $e->getMessage());
                }
            }

            // 2. Now initialize tenancy
            // This will swap both 'mysql' and 'tenant' connections
            tenancy()->initialize($tenant);

            // 3. Run migrations Specifically for this tenant
            \Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            \Log::info("Tenant Migrations Done.");

            // 4. Run seeds
            \Artisan::call('db:seed', [
                '--database' => 'tenant',
                '--class' => 'Database\\Seeders\\TenantRolesSeeder',
                '--force' => true,
            ]);

            \Log::info("Tenancy Initialized and Setup Complete.");
            \Log::info("Tenant DB Name: " . $tenant->database_name);

            // Create user in tenant DB using TenantUser (tenant connection)
            $tenantUser = \App\Models\TenantUser::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password, // Password is already hashed
            ]);

            // Assign business_owner role
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            $role = \Spatie\Permission\Models\Role::on('tenant')->where('name', 'business_owner')->first();
            if ($role) {
                // Ensure the user is also recognized on the tenant connection for the role assignment
                $tenantUser->assignRole($role);
                \Log::info("Assigned business_owner role to user: " . $tenantUser->email);
            } else {
                \Log::error("Could not find business_owner role for user: " . $tenantUser->email);
            }
            // 5. Handle Stripe Subscription if plan and payment method provided
            if ($paymentMethodId && $planId) {
                $plan = \App\Models\PricingPlan::find($planId);
                if ($plan) {
                    $billingCycle = $extraData['billing_cycle'] ?? 'monthly';
                    $stripeId = $billingCycle === 'yearly' ? $plan->stripe_yearly_id : $plan->stripe_id;

                    if ($stripeId) {
                        \Log::info("Initiating Stripe Subscription for Tenant: " . $tenant->id . " Plan: " . $plan->slug . " Cycle: " . $billingCycle);

                        $subscription = $tenant->newSubscription('default', $stripeId);

                        if ($plan->trial_days > 0) {
                            $subscription->trialDays($plan->trial_days);
                        }

                        $subscription->create($paymentMethodId);

                        \Log::info("Stripe Subscription Created.");
                    } else {
                        \Log::error("Stripe Price ID missing for plan " . $plan->name . " and cycle " . $billingCycle);
                    }
                }
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

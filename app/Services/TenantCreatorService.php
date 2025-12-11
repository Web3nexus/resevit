<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class TenantCreatorService
{
    /**
     * Create a new tenant with owner, database, migrations, and roles.
     *
     * @param array $ownerData ['name', 'email', 'password', 'business_name', 'business_slug', 'phone']
     * @return array ['user' => User, 'tenant' => Tenant]
     * @throws \Exception
     */
    public function createTenant(array $ownerData): array
    {
        try {
            // 1. Create User (Business Owner)
            $user = User::create([
                'name' => $ownerData['name'],
                'email' => $ownerData['email'],
                'password' => $ownerData['password'],
            ]);

            // Assign business_owner role
            $user->assignRole('business_owner');

            // 2. Prepare tenant data
            $slug = $this->validateAndGenerateSlug($ownerData['business_slug']);
            $databaseName = $this->generateDatabaseName($slug);

            // 3. Create tenant in landlord DB
            $tenant = Tenant::create([
                'name' => $ownerData['business_name'],
                'slug' => $slug,
                'database_name' => $databaseName,
                'owner_user_id' => $user->id,
                'status' => 'active',
                'domain' => $this->generateDomain($slug),
            ]);

            // 4. Create tenant database
            $this->createTenantDatabase($databaseName);

            // 5. Run tenant migrations
            $this->runTenantMigrations($tenant);

            // 6. Create and assign default tenant roles
            $this->assignDefaultTenantRoles($tenant);

            return [
                'user' => $user,
                'tenant' => $tenant,
            ];
        } catch (\Exception $e) {
            // Cleanup on failure
            // TODO: implement proper rollback logic
            throw $e;
        }
    }

    /**
     * Validate and generate a unique slug for the tenant.
     */
    private function validateAndGenerateSlug(string $slug): string
    {
        $slug = Str::slug($slug);

        // Ensure uniqueness
        $original = $slug;
        $counter = 1;

        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Generate a database name from the tenant slug.
     */
    public function generateDatabaseName(string $slug): string
    {
        // Format: resevit_<slug>
        $prefix = env('DB_TENANT_PREFIX', 'resevit_');
        return $prefix . str_replace('-', '_', $slug);
    }

    /**
     * Generate domain for the tenant.
     */
    private function generateDomain(string $slug): string
    {
        $appDomain = env('APP_DOMAIN', 'localhost');
        return "{$slug}.{$appDomain}";
    }

    /**
     * Create the tenant database.
     */
    private function createTenantDatabase(string $databaseName): void
    {
        $connection = 'landlord';
        $host = config("database.connections.{$connection}.host");
        $user = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");
        $port = config("database.connections.{$connection}.port") ?? 3306;

        // Use raw PDO to create database
        $dsn = "mysql:host={$host};port={$port}";
        $pdo = new \PDO($dsn, $user, $password);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Run tenant migrations on the created tenant.
     */
    public function runTenantMigrations(Tenant $tenant): void
    {
        // Switch to tenant database context
        $tenantConnection = config('tenancy.database.connection_name', 'tenant');
        config(['database.connections.' . $tenantConnection . '.database' => $tenant->database_name]);

        // Run migrations for this tenant
        Artisan::call('migrate', [
            '--database' => $tenantConnection,
        ]);
    }

    /**
     * Create and assign default tenant roles.
     */
    public function assignDefaultTenantRoles(Tenant $tenant): void
    {
        $roles = ['manager', 'accountant', 'staff', 'waiter', 'cashier'];

        // Switch to tenant database to create roles
        $tenantConnection = config('tenancy.database.connection_name', 'tenant');
        config(['database.connections.' . $tenantConnection . '.database' => $tenant->database_name]);

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );
        }
    }
}

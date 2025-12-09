<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TenantRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createTenantRoles([
            'business_owner',
            'manager',
            'accountant',
            'staff',
            'waiter',
            'cashier',
        ]);
    }

    /**
     * Create a single tenant role.
     */
    private function createTenantRole(string $name): void
    {
        Role::firstOrCreate(
            ['name' => $name, 'guard_name' => 'web']
        );

        $this->command->info("Tenant role '{$name}' created or already exists.");
    }

    /**
     * Create multiple tenant roles at once.
     *
     * @param array<string> $roles
     */
    private function createTenantRoles(array $roles): void
    {
        foreach ($roles as $role) {
            $this->createTenantRole($role);
        }
    }
}

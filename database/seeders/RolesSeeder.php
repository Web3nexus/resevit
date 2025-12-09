<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Global roles for Super Admin Panel (securegate)
        $this->createGlobalRoles([
            'securegate_admin',
            'securegate_support',
            'securegate_marketing',
        ]);

        // Investor role (global)
        $this->createGlobalRole('investor');

        // Customer role (global)
        $this->createGlobalRole('customer');

        // Tenant roles (created in each tenant's database)
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
     * Create a single global role.
     */
    private function createGlobalRole(string $name): void
    {
        Role::firstOrCreate(
            ['name' => $name, 'guard_name' => 'web']
        );

        $this->command->info("Global role '{$name}' created or already exists.");
    }

    /**
     * Create multiple global roles at once.
     *
     * @param array<string> $roles
     */
    private function createGlobalRoles(array $roles): void
    {
        foreach ($roles as $role) {
            $this->createGlobalRole($role);
        }
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

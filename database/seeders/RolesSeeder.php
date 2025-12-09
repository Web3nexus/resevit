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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Global roles for Super Admin Panel (securegate)
        $this->createGlobalRoles([
            'super-admin',
            'securegate_admin',
            'securegate_support',
            'securegate_marketing',
        ]);

        // Investor role (global)
        $this->createGlobalRole('investor');

        // Customer role (global)
        $this->createGlobalRole('customer');
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
}

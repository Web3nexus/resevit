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
        ], 'securegate');

        // Investor role (global)
        Role::firstOrCreate(['name' => 'investor', 'guard_name' => 'investor']);

        // Customer role (global)
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'customer']);

        $this->command->info('Global roles created successfully!');
    }

    /**
     * Create multiple global roles at once.
     *
     * @param array<string> $roles
     */
    private function createGlobalRoles(array $roles, string $guard = 'web'): void
    {
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => $guard]);
            $this->command->info("Role '{$role}' created or already exists.");
        }
    }
}

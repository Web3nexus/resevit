<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Landlord Roles (Guard: web)
        $landlordRoles = [
            'super_admin',
            'owner',
            'manager',
            'staff',
            'accountant',
        ];

        foreach ($landlordRoles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Tenant Roles (Guards: securegate, investor, customer)
        $tenantRoles = [
            ['name' => 'securegate_admin', 'guard_name' => 'securegate'],
            ['name' => 'securegate_support', 'guard_name' => 'securegate'],
            ['name' => 'securegate_marketing', 'guard_name' => 'securegate'],
            ['name' => 'investor', 'guard_name' => 'investor'],
            ['name' => 'customer', 'guard_name' => 'customer'],
        ];

        foreach ($tenantRoles as $role) {
            Role::firstOrCreate([
                'name' => $role['name'],
                'guard_name' => $role['guard_name'],
            ]);
        }

        // Add dummy permissions for now (to be updated based on backup discovery)
        $permissions = [
            'view dashboard',
            'manage restaurants',
            'view analytics',
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign all permissions to super_admin
        $superAdmin = Role::where('name', 'super_admin')->where('guard_name', 'web')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::where('guard_name', 'web')->get());
        }
    }
}

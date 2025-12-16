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
        // Tenant roles
        Role::firstOrCreate(['name' => 'business_owner', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'waiter', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
    }
}

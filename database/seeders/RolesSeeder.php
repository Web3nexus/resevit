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
        // Landlord roles
        Role::create(['name' => 'securegate_admin', 'guard_name' => 'web']);
        Role::create(['name' => 'securegate_support', 'guard_name' => 'web']);
        Role::create(['name' => 'securegate_marketing', 'guard_name' => 'web']);
    }
}

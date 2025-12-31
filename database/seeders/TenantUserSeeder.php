<?php

namespace Database\Seeders;

use App\Models\TenantUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure team ID is set for Spatie if teams are enabled
        if (config('permission.teams')) {
            $branch = \App\Models\Branch::first();
            if ($branch) {
                setPermissionsTeamId($branch->id);
            }
        }

        // Create Owner
        $owner = TenantUser::updateOrCreate(
            ['email' => 'owner@local.test'],
            [
                'name' => 'Business Owner',
                'password' => Hash::make('password'),
            ]
        );
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $owner->assignRole($ownerRole);

        // Create Manager
        $manager = TenantUser::updateOrCreate(
            ['email' => 'manager@local.test'],
            [
                'name' => 'Manager',
                'password' => Hash::make('password'),
            ]
        );
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->assignRole($managerRole);

        // Create Staff
        $staff = TenantUser::updateOrCreate(
            ['email' => 'staff@local.test'],
            [
                'name' => 'Staff',
                'password' => Hash::make('password'),
            ]
        );
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->assignRole($staffRole);

        // Create Accountant
        $accountant = TenantUser::updateOrCreate(
            ['email' => 'accountant@local.test'],
            [
                'name' => 'Accountant',
                'password' => Hash::make('password'),
            ]
        );
        $accountantRole = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $accountant->assignRole($accountantRole);
    }
}

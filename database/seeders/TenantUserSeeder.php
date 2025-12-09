<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Create Owner
        $owner = User::create([
            'name' => 'Business Owner',
            'email' => 'owner@local.test',
            'password' => Hash::make('password'),
        ]);
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $owner->assignRole($ownerRole);

        // Create Manager
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@local.test',
            'password' => Hash::make('password'),
        ]);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->assignRole($managerRole);

        // Create Staff
        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@local.test',
            'password' => Hash::make('password'),
        ]);
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->assignRole($staffRole);

        // Create Accountant
        $accountant = User::create([
            'name' => 'Accountant',
            'email' => 'accountant@local.test',
            'password' => Hash::make('password'),
        ]);
        $accountantRole = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $accountant->assignRole($accountantRole);
    }
}

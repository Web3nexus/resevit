<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Contracts\Tenant;

class TenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createBusinessOwner(tenant());
    }

    /**
     * Create the business owner user for the tenant.
     */
    private function createBusinessOwner(Tenant $tenant): void
    {
        $user = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Business Owner',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('business_owner');

        // Attach the user to the current tenant
        $tenant->users()->attach($user);

        $this->command->info('Business Owner user created and attached to the tenant.');
    }
}

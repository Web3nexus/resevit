<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createSuperAdmin();
        $this->createInvestor();
        $this->createCustomer();
    }

    /**
     * Create the super-admin user.
     */
    private function createSuperAdmin(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('super-admin');

        $this->command->info('Super Admin user created.');
    }

    /**
     * Create an investor user.
     */
    private function createInvestor(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'investor@example.com'],
            [
                'name' => 'Investor User',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('investor');

        $this->command->info('Investor user created.');
    }

    /**
     * Create a customer user.
     */
    private function createCustomer(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('customer');

        $this->command->info('Customer user created.');
    }
}

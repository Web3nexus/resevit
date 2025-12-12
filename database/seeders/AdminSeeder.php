<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default super admin
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@resevit.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Assign securegate_admin role
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('securegate_admin');
        }

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: admin@resevit.com');
        $this->command->info('Password: password');
        $this->command->warn('IMPORTANT: Change this password in production!');
    }
}

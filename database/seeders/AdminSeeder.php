<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or Update default super admin (Forces password reset)
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@resevit.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Assign securegate_admin role safely
        $role = Role::where('name', 'securegate_admin')->where('guard_name', 'securegate')->first();
        if ($role) {
            $admin->roles()->syncWithPivotValues([$role->id], ['branch_id' => 0, 'model_type' => get_class($admin)], false);
        }

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: admin@resevit.com');
        $this->command->info('Password: password');
        $this->command->warn('IMPORTANT: Change this password in the Super Admin panel!');
    }
}

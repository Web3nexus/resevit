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
        // Create or Update default super admin (Forces password reset)
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@resevit.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Assign securegate_admin role safely
        $role = \Spatie\Permission\Models\Role::where('name', 'securegate_admin')->where('guard_name', 'securegate')->first();
        if ($role) {
            // Check if role is already assigned to avoid duplicate entry error
            $exists = \Illuminate\Support\Facades\DB::table('model_has_roles')
                ->where('model_id', $admin->id)
                ->where('model_type', get_class($admin))
                ->where('role_id', $role->id)
                ->where('branch_id', 0)
                ->exists();

            if (! $exists) {
                $admin->roles()->attach($role->id, ['branch_id' => 0, 'model_type' => get_class($admin)]);
            }
        }

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: admin@resevit.com');
        $this->command->info('Password: password');
        $this->command->warn('IMPORTANT: Change this password in production!');
    }
}

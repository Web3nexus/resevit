<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class SystemsSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->ensureSuperAdminExists();
        $this->ensurePlatformSettingsExist();
    }

    /**
     * Ensure exactly one Super Admin exists with the correct role.
     */
    private function ensureSuperAdminExists(): void
    {
        $email = 'admin@resevit.com';

        // Find or create the admin
        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            $admin = Admin::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make('password'), // User MUST change this in production
            ]);
            $this->command->info("Super Admin created: {$email}");
        } else {
            $this->command->info("Super Admin already exists: {$email}");
        }

        // Ensure the role exists and is assigned
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $roleName = 'securegate_admin';

            // Check if role exists in landlord connection or default
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                $role = Role::create(['name' => $roleName, 'guard_name' => 'admin']);
                $this->command->info("Role created: {$roleName}");
            }

            if (!$admin->hasRole($roleName)) {
                $admin->assignRole($roleName);
                $this->command->info("Role {$roleName} assigned to Super Admin");
            }
        }
    }

    /**
     * Ensure baseline platform settings exist without overwriting production values.
     */
    private function ensurePlatformSettingsExist(): void
    {
        if (!Schema::hasTable('platform_settings')) {
            $this->command->warn('Table platform_settings does not exist. Skipping settings creation.');
            return;
        }

        $settings = PlatformSetting::first();

        if (!$settings) {
            PlatformSetting::create([
                'supported_languages' => ['en', 'es', 'fr', 'de', 'ar'],
                'footer_settings' => PlatformSetting::getDefaultFooterSettings(),
                'landing_settings' => [
                    'hero_badge' => 'THE FUTURE OF DINING',
                    'hero_title' => 'Maximize Your Restaurant’s <span class="text-brand-accent">Potential</span>',
                    'hero_subtitle' => "Streamline reservations, optimize staff schedules, and delight customers with the world's most advanced restaurant management platform.",
                    'hero_cta_text' => 'Get Started Free',
                    'hero_cta_url' => '/register',
                    'active_theme' => 'default',
                ],
                'promotion_settings' => [
                    'min_withdrawal_amount' => 50,
                    'affiliate_enabled' => true,
                ],
            ]);
            $this->command->info('Default platform settings created.');
        } else {
            $this->command->info('Platform settings already exist. Skipping.');
        }
    }
}

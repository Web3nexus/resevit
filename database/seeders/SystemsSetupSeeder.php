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
        $this->ensurePlatformSettingsExist();
        $this->ensureDefaultEmailSettingsExist();
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
                    'hero_subtitle' => "Streamline reservations, optimize staff schedules, and delight customers with Resevit's advanced restaurant management platform.",
                    'hero_cta_text' => 'Launch Your Business',
                    'hero_cta_url' => '/register',
                    'active_theme' => 'default',
                    'support_email' => 'support@resevit.com',
                    'contact_phone' => '+1 (555) 000-0000',
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

    /**
     * Ensure default email settings exist to "unpause" the email system.
     */
    private function ensureDefaultEmailSettingsExist(): void
    {
        if (!Schema::hasTable('default_email_settings')) {
            $this->command->warn('Table default_email_settings does not exist. Skipping.');
            return;
        }

        $exists = \App\Models\DefaultEmailSetting::where('is_active', true)->exists();

        if (!$exists) {
            \App\Models\DefaultEmailSetting::create([
                'provider' => 'smtp',
                'smtp_host' => 'smtp.hostinger.com',
                'smtp_port' => 465,
                'smtp_username' => 'noreply@cryptogateshub.com',
                'smtp_password' => '4oC>!]=7!;',
                'smtp_encryption' => 'ssl',
                'from_email' => 'hello@resevit.com',
                'from_name' => 'Resevit',
                'is_active' => true,
            ]);
            $this->command->info('Default email settings seeded (System Unpaused).');
            $this->command->warn('IMPORTANT: Update these credentials in the admin panel/database for production!');
        } else {
            $this->command->info('Active default email settings already exist.');
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'key' => 'welcome_registration',
                'name' => 'Registration Welcome Email',
                'subject' => 'Welcome to {{app_name}}!',
                'body_html' => '<h1>Hello {{user_name}}!</h1><p>Thank you for registering at {{app_name}}. Your business <strong>{{business_name}}</strong> is now being set up.</p><p>You can access your dashboard here: <a href="{{dashboard_url}}">{{dashboard_url}}</a></p>',
                'body_text' => "Hello {{user_name}}!\n\nThank you for registering at {{app_name}}. Your business {{business_name}} is now being set up.\n\nYou can access your dashboard here: {{dashboard_url}}",
                'variables' => ['user_name', 'business_name', 'dashboard_url', 'app_name'],
                'is_active' => true,
            ],
            [
                'key' => 'new_login_alert',
                'name' => 'New Login Security Alert',
                'subject' => 'New Login Alert for {{app_name}}',
                'body_html' => '<h3>Security Alert</h3><p>Hello {{user_name}},</p><p>Your account was just logged into from a new device.</p><ul><li><strong>Time:</strong> {{login_time}}</li><li><strong>IP Address:</strong> {{ip_address}}</li><li><strong>Device:</strong> {{device}}</li><li><strong>Location:</strong> {{location}}</li></ul><p>If this was not you, please reset your password immediately.</p>',
                'body_text' => "Security Alert\n\nHello {{user_name}},\n\nYour account was just logged into from a new device.\n\nTime: {{login_time}}\nIP Address: {{ip_address}}\nDevice: {{device}}\nLocation: {{location}}\n\nIf this was not you, please reset your password immediately.",
                'variables' => ['user_name', 'login_time', 'ip_address', 'device', 'location', 'app_name'],
                'is_active' => true,
            ],
            [
                'key' => '2fa_code',
                'name' => '2FA Verification Code',
                'subject' => '{{2fa_code}} is your verification code',
                'body_html' => '<h3>Two-Factor Authentication</h3><p>Hello {{user_name}},</p><p>Your verification code is:</p><h1 style="font-size: 32px; letter-spacing: 5px;">{{2fa_code}}</h1><p>This code will expire in 10 minutes.</p>',
                'body_text' => "Two-Factor Authentication\n\nHello {{user_name}},\n\nYour verification code is: {{2fa_code}}\n\nThis code will expire in 10 minutes.",
                'variables' => ['user_name', '2fa_code', 'app_name'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::on('landlord')->updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}

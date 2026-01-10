<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class LifecycleEmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Existing templates (updated with layout settings)
            [
                'key' => 'welcome_registration',
                'name' => 'Registration Welcome Email',
                'subject' => 'Welcome to {{app_name}}!',
                'email_title' => 'Welcome to {{app_name}}',
                'email_badge' => 'WELCOME',
                'body_html' => '<p>Hello <strong>{{user_name}}</strong>!</p><p>Thank you for registering at {{app_name}}. Your business <strong>{{business_name}}</strong> is now being set up.</p><p>You can access your dashboard here:</p><a href="{{dashboard_url}}" class="cta-button">Access Dashboard</a>',
                'body_text' => "Hello {{user_name}}!\n\nThank you for registering at {{app_name}}. Your business {{business_name}} is now being set up.\n\nYou can access your dashboard here: {{dashboard_url}}",
                'variables' => ['user_name', 'business_name', 'dashboard_url', 'app_name'],
                'use_layout' => true,
                'is_active' => true,
            ],
            [
                'key' => 'new_login_alert',
                'name' => 'New Login Security Alert',
                'subject' => 'New Login Alert for {{app_name}}',
                'email_title' => 'New Login Detected',
                'email_badge' => 'SECURITY ALERT',
                'body_html' => '<p>Hello <strong>{{user_name}}</strong>,</p><p>Your account was just logged into from a new device.</p><ul style="list-style: none; padding: 0;"><li style="padding: 8px 0;"><strong>Time:</strong> {{login_time}}</li><li style="padding: 8px 0;"><strong>IP Address:</strong> {{ip_address}}</li><li style="padding: 8px 0;"><strong>Device:</strong> {{device}}</li><li style="padding: 8px 0;"><strong>Location:</strong> {{location}}</li></ul><p style="margin-top: 20px; padding: 15px; background-color: #FEF3C7; border-left: 4px solid #F59E0B; border-radius: 4px;">If this was not you, please reset your password immediately.</p>',
                'body_text' => "Security Alert\n\nHello {{user_name}},\n\nYour account was just logged into from a new device.\n\nTime: {{login_time}}\nIP Address: {{ip_address}}\nDevice: {{device}}\nLocation: {{location}}\n\nIf this was not you, please reset your password immediately.",
                'variables' => ['user_name', 'login_time', 'ip_address', 'device', 'location', 'app_name'],
                'use_layout' => true,
                'is_active' => true,
            ],
            [
                'key' => '2fa_code',
                'name' => '2FA Verification Code',
                'subject' => '{{2fa_code}} is your verification code',
                'email_title' => 'Your Verification Code',
                'email_badge' => '2FA AUTHENTICATION',
                'body_html' => '<p>Hello <strong>{{user_name}}</strong>,</p><p>Your two-factor authentication code is:</p><div style="text-align: center; margin: 30px 0;"><h1 style="font-size: 48px; letter-spacing: 10px; color: #0B132B; font-weight: 800; margin: 0;">{{2fa_code}}</h1></div><p style="text-align: center; color: #6B7280;">This code will expire in 10 minutes.</p>',
                'body_text' => "Two-Factor Authentication\n\nHello {{user_name}},\n\nYour verification code is: {{2fa_code}}\n\nThis code will expire in 10 minutes.",
                'variables' => ['user_name', '2fa_code', 'app_name'],
                'use_layout' => true,
                'is_active' => true,
            ],

            // Staff Lifecycle Templates
            [
                'key' => 'staff_welcome',
                'name' => 'Staff Welcome Email',
                'subject' => 'Welcome to the {{business_name}} Team!',
                'email_title' => 'Welcome to the Team!',
                'email_badge' => 'NEW HIRE',
                'body_html' => '<p>Hello <strong>{{staff_name}}</strong>,</p><p>We are excited to welcome you to <strong>{{business_name}}</strong> as our new <strong>{{position}}</strong>!</p><p><strong>Your Start Date:</strong> {{hire_date}}</p><p><strong>Branch Location:</strong> {{branch_name}}</p><p>Your manager will reach out to you shortly with more details about your onboarding process.</p><p style="margin-top: 30px;">We look forward to working with you!</p>',
                'body_text' => "Hello {{staff_name}},\n\nWe are excited to welcome you to {{business_name}} as our new {{position}}!\n\nYour Start Date: {{hire_date}}\nBranch Location: {{branch_name}}\n\nYour manager will reach out to you shortly with more details about your onboarding process.\n\nWe look forward to working with you!",
                'variables' => ['staff_name', 'business_name', 'position', 'hire_date', 'branch_name'],
                'use_layout' => true,
                'is_active' => true,
            ],
            [
                'key' => 'staff_credentials',
                'name' => 'Staff Login Credentials',
                'subject' => 'Your {{business_name}} Account Details',
                'email_title' => 'Your Account is Ready',
                'email_badge' => 'ACCOUNT ACCESS',
                'body_html' => '<p>Hello <strong>{{staff_name}}</strong>,</p><p>Your account for <strong>{{business_name}}</strong> has been created. Below are your login credentials:</p><div style="background-color: #F3F4F6; padding: 20px; border-radius: 8px; margin: 20px 0;"><p style="margin: 0;"><strong>Email:</strong> {{staff_email}}</p><p style="margin: 10px 0 0 0;"><strong>Temporary Password:</strong> <code style="background-color: #E5E7EB; padding: 4px 8px; border-radius: 4px; font-family: monospace;">{{temp_password}}</code></p></div><p style="padding: 15px; background-color: #FEF3C7; border-left: 4px solid #F59E0B; border-radius: 4px;"><strong>Important:</strong> Please change your password immediately after your first login.</p><a href="{{login_url}}" class="cta-button">Login to Dashboard</a>',
                'body_text' => "Hello {{staff_name}},\n\nYour account for {{business_name}} has been created. Below are your login credentials:\n\nEmail: {{staff_email}}\nTemporary Password: {{temp_password}}\n\nImportant: Please change your password immediately after your first login.\n\nLogin URL: {{login_url}}",
                'variables' => ['staff_name', 'business_name', 'staff_email', 'temp_password', 'login_url'],
                'use_layout' => true,
                'is_active' => true,
            ],
            [
                'key' => 'staff_promotion',
                'name' => 'Staff Promotion Notification',
                'subject' => 'Congratulations on Your Promotion!',
                'email_title' => 'Congratulations!',
                'email_badge' => 'PROMOTION',
                'body_html' => '<p>Hello <strong>{{staff_name}}</strong>,</p><p>We are pleased to inform you that you have been promoted to <strong>{{new_position}}</strong> at {{business_name}}!</p><p><strong>Effective Date:</strong> {{effective_date}}</p><p><strong>New Hourly Rate:</strong> ${{new_rate}}/hour</p><p>This promotion is a recognition of your hard work, dedication, and the value you bring to our team.</p><p style="margin-top: 30px;">Congratulations and keep up the excellent work!</p>',
                'body_text' => "Hello {{staff_name}},\n\nWe are pleased to inform you that you have been promoted to {{new_position}} at {{business_name}}!\n\nEffective Date: {{effective_date}}\nNew Hourly Rate: \${{new_rate}}/hour\n\nThis promotion is a recognition of your hard work, dedication, and the value you bring to our team.\n\nCongratulations and keep up the excellent work!",
                'variables' => ['staff_name', 'new_position', 'business_name', 'effective_date', 'new_rate'],
                'use_layout' => true,
                'is_active' => true,
            ],
            [
                'key' => 'staff_termination',
                'name' => 'Staff Termination Notice',
                'subject' => 'Employment Status Update',
                'email_title' => 'Employment Status Update',
                'email_badge' => 'NOTICE',
                'body_html' => '<p>Hello <strong>{{staff_name}}</strong>,</p><p>We regret to inform you that your employment with <strong>{{business_name}}</strong> has been terminated, effective <strong>{{termination_date}}</strong>.</p><p><strong>Reason:</strong> {{termination_reason}}</p><p>Your final paycheck, including any outstanding payments, will be processed according to company policy.</p><p>If you have any questions or need to arrange the return of company property, please contact HR at {{hr_contact}}.</p><p style="margin-top: 30px;">We wish you the best in your future endeavors.</p>',
                'body_text' => "Hello {{staff_name}},\n\nWe regret to inform you that your employment with {{business_name}} has been terminated, effective {{termination_date}}.\n\nReason: {{termination_reason}}\n\nYour final paycheck, including any outstanding payments, will be processed according to company policy.\n\nIf you have any questions or need to arrange the return of company property, please contact HR at {{hr_contact}}.\n\nWe wish you the best in your future endeavors.",
                'variables' => ['staff_name', 'business_name', 'termination_date', 'termination_reason', 'hr_contact'],
                'use_layout' => true,
                'is_active' => true,
            ],

            // Business Operations Templates
            [
                'key' => 'super_admin_new_customer',
                'name' => 'New Customer Alert (Super Admin)',
                'subject' => 'New Business Registration: {{business_name}}',
                'email_title' => 'New Business Registered',
                'email_badge' => 'PLATFORM ALERT',
                'body_html' => '<p>A new business has registered on the platform:</p><div style="background-color: #F3F4F6; padding: 20px; border-radius: 8px; margin: 20px 0;"><p style="margin: 0;"><strong>Business Name:</strong> {{business_name}}</p><p style="margin: 10px 0 0 0;"><strong>Owner:</strong> {{owner_name}}</p><p style="margin: 10px 0 0 0;"><strong>Email:</strong> {{owner_email}}</p><p style="margin: 10px 0 0 0;"><strong>Registration Date:</strong> {{registration_date}}</p><p style="margin: 10px 0 0 0;"><strong>Plan:</strong> {{plan_name}}</p></div><a href="{{admin_url}}" class="cta-button">View in Admin Panel</a>',
                'body_text' => "A new business has registered on the platform:\n\nBusiness Name: {{business_name}}\nOwner: {{owner_name}}\nEmail: {{owner_email}}\nRegistration Date: {{registration_date}}\nPlan: {{plan_name}}\n\nView in Admin Panel: {{admin_url}}",
                'variables' => ['business_name', 'owner_name', 'owner_email', 'registration_date', 'plan_name', 'admin_url'],
                'use_layout' => true,
                'is_active' => true,
            ],
            [
                'key' => 'business_new_order',
                'name' => 'New Order Alert (Business Owner)',
                'subject' => 'New Order #{{order_number}} Received',
                'email_title' => 'New Order Received!',
                'email_badge' => 'NEW ORDER',
                'body_html' => '<p>You have received a new order:</p><div style="background-color: #F3F4F6; padding: 20px; border-radius: 8px; margin: 20px 0;"><p style="margin: 0;"><strong>Order Number:</strong> #{{order_number}}</p><p style="margin: 10px 0 0 0;"><strong>Customer:</strong> {{customer_name}}</p><p style="margin: 10px 0 0 0;"><strong>Total Amount:</strong> ${{order_total}}</p><p style="margin: 10px 0 0 0;"><strong>Order Type:</strong> {{order_type}}</p><p style="margin: 10px 0 0 0;"><strong>Date/Time:</strong> {{order_datetime}}</p></div><p><strong>Items:</strong></p><div style="background-color: #FFFFFF; border: 1px solid #E5E7EB; padding: 15px; border-radius: 8px;">{{order_items}}</div><a href="{{order_url}}" class="cta-button">View Order Details</a>',
                'body_text' => "You have received a new order:\n\nOrder Number: #{{order_number}}\nCustomer: {{customer_name}}\nTotal Amount: \${{order_total}}\nOrder Type: {{order_type}}\nDate/Time: {{order_datetime}}\n\nItems:\n{{order_items}}\n\nView Order Details: {{order_url}}",
                'variables' => ['order_number', 'customer_name', 'order_total', 'order_type', 'order_datetime', 'order_items', 'order_url'],
                'use_layout' => true,
                'is_active' => true,
            ],
            [
                'key' => 'business_new_reservation',
                'name' => 'New Reservation Alert (Business Owner)',
                'subject' => 'New Reservation for {{reservation_date}}',
                'email_title' => 'New Reservation Received!',
                'email_badge' => 'NEW BOOKING',
                'body_html' => '<p>You have received a new reservation:</p><div style="background-color: #F3F4F6; padding: 20px; border-radius: 8px; margin: 20px 0;"><p style="margin: 0;"><strong>Guest Name:</strong> {{guest_name}}</p><p style="margin: 10px 0 0 0;"><strong>Party Size:</strong> {{party_size}} guests</p><p style="margin: 10px 0 0 0;"><strong>Date:</strong> {{reservation_date}}</p><p style="margin: 10px 0 0 0;"><strong>Time:</strong> {{reservation_time}}</p><p style="margin: 10px 0 0 0;"><strong>Table:</strong> {{table_number}}</p><p style="margin: 10px 0 0 0;"><strong>Contact:</strong> {{guest_phone}}</p></div><p><strong>Special Requests:</strong></p><p style="background-color: #FFFFFF; border: 1px solid #E5E7EB; padding: 15px; border-radius: 8px;">{{special_requests}}</p><a href="{{reservation_url}}" class="cta-button">View Reservation</a>',
                'body_text' => "You have received a new reservation:\n\nGuest Name: {{guest_name}}\nParty Size: {{party_size}} guests\nDate: {{reservation_date}}\nTime: {{reservation_time}}\nTable: {{table_number}}\nContact: {{guest_phone}}\n\nSpecial Requests:\n{{special_requests}}\n\nView Reservation: {{reservation_url}}",
                'variables' => ['guest_name', 'party_size', 'reservation_date', 'reservation_time', 'table_number', 'guest_phone', 'special_requests', 'reservation_url'],
                'use_layout' => true,
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

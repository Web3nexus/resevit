<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'key' => 'new_staff',
                'name' => 'New Staff Welcome',
                'subject' => 'Welcome to {{restaurant_name}}!',
                'body_html' => '<h1>Welcome {{staff_name}}!</h1><p>We\'re excited to have you join our team at <strong>{{restaurant_name}}</strong>.</p><p>Your login credentials:<br>Email: {{email}}<br>Temporary Password: {{password}}</p><p>Please log in and change your password.</p>',
                'body_text' => 'Welcome {{staff_name}}! We\'re excited to have you join our team at {{restaurant_name}}. Your login credentials: Email: {{email}}, Temporary Password: {{password}}. Please log in and change your password.',
                'variables' => ['staff_name', 'restaurant_name', 'email', 'password'],
            ],
            [
                'key' => 'new_order',
                'name' => 'New Order Notification',
                'subject' => 'New Order #{{order_number}}',
                'body_html' => '<h2>New Order Received!</h2><p><strong>Order #{{order_number}}</strong></p><p>Customer: {{customer_name}}<br>Total: {{total}}<br>Type: {{order_type}}</p><p>Items:<br>{{items}}</p>',
                'body_text' => 'New Order Received! Order #{{order_number}}. Customer: {{customer_name}}, Total: {{total}}, Type: {{order_type}}. Items: {{items}}',
                'variables' => ['order_number', 'customer_name', 'total', 'order_type', 'items'],
            ],
            [
                'key' => 'reservation_confirmed',
                'name' => 'Reservation Confirmation',
                'subject' => 'Reservation Confirmed at {{restaurant_name}}',
                'body_html' => '<h1>Reservation Confirmed!</h1><p>Dear {{guest_name}},</p><p>Your reservation at <strong>{{restaurant_name}}</strong> is confirmed.</p><p><strong>Details:</strong><br>Date & Time: {{reservation_time}}<br>Party Size: {{party_size}}<br>Table: {{table_name}}</p><p>We look forward to serving you!</p>',
                'body_text' => 'Reservation Confirmed! Dear {{guest_name}}, Your reservation at {{restaurant_name}} is confirmed. Details: Date & Time: {{reservation_time}}, Party Size: {{party_size}}, Table: {{table_name}}. We look forward to serving you!',
                'variables' => ['guest_name', 'restaurant_name', 'reservation_time', 'party_size', 'table_name'],
            ],
            [
                'key' => 'reservation_reminder',
                'name' => 'Reservation Reminder',
                'subject' => 'Reminder: Reservation Tomorrow at {{restaurant_name}}',
                'body_html' => '<h2>Reservation Reminder</h2><p>Hi {{guest_name}},</p><p>This is a friendly reminder about your reservation at <strong>{{restaurant_name}}</strong> tomorrow.</p><p><strong>Details:</strong><br>Date & Time: {{reservation_time}}<br>Party Size: {{party_size}}</p><p>See you soon!</p>',
                'body_text' => 'Reservation Reminder. Hi {{guest_name}}, This is a friendly reminder about your reservation at {{restaurant_name}} tomorrow. Details: Date & Time: {{reservation_time}}, Party Size: {{party_size}}. See you soon!',
                'variables' => ['guest_name', 'restaurant_name', 'reservation_time', 'party_size'],
            ],
            [
                'key' => 'order_ready',
                'name' => 'Order Ready for Pickup',
                'subject' => 'Your Order #{{order_number}} is Ready!',
                'body_html' => '<h2>Order Ready!</h2><p>Hi {{customer_name}},</p><p>Your order <strong>#{{order_number}}</strong> is ready for pickup!</p><p>Please come to {{restaurant_name}} at your earliest convenience.</p>',
                'body_text' => 'Order Ready! Hi {{customer_name}}, Your order #{{order_number}} is ready for pickup! Please come to {{restaurant_name}} at your earliest convenience.',
                'variables' => ['customer_name', 'order_number', 'restaurant_name'],
            ],
            [
                'key' => 'customer_support_reply',
                'name' => 'Customer Support Auto-Reply',
                'subject' => 'We received your message',
                'body_html' => '<h2>Thank You for Contacting Us</h2><p>Hi {{customer_name}},</p><p>We\'ve received your message and will get back to you within 24 hours.</p><p><strong>Your Message:</strong><br>{{message}}</p><p>Best regards,<br>{{restaurant_name}} Team</p>',
                'body_text' => 'Thank You for Contacting Us. Hi {{customer_name}}, We\'ve received your message and will get back to you within 24 hours. Your Message: {{message}}. Best regards, {{restaurant_name}} Team',
                'variables' => ['customer_name', 'message', 'restaurant_name'],
            ],
            [
                'key' => 'marketing_campaign',
                'name' => 'Marketing Campaign',
                'subject' => '{{campaign_subject}}',
                'body_html' => '{{campaign_content}}',
                'body_text' => '{{campaign_content}}',
                'variables' => ['campaign_subject', 'campaign_content'],
            ],
            [
                'key' => 'order_receipt',
                'name' => 'Order Receipt',
                'subject' => 'Receipt for Order #{{order_number}}',
                'body_html' => '<h1>Receipt</h1><p><strong>{{restaurant_name}}</strong></p><p>Order #{{order_number}}<br>Date: {{order_date}}</p><hr><p>{{items}}</p><hr><p><strong>Subtotal:</strong> {{subtotal}}<br><strong>Tax:</strong> {{tax}}<br><strong>Total:</strong> {{total}}</p><p>Payment Method: {{payment_method}}</p><p>Thank you for your business!</p>',
                'body_text' => 'Receipt. {{restaurant_name}}. Order #{{order_number}}, Date: {{order_date}}. {{items}}. Subtotal: {{subtotal}}, Tax: {{tax}}, Total: {{total}}. Payment Method: {{payment_method}}. Thank you for your business!',
                'variables' => ['restaurant_name', 'order_number', 'order_date', 'items', 'subtotal', 'tax', 'total', 'payment_method'],
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}

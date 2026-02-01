<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // order_confirmation, order_ready, etc.
            $table->string('name');
            $table->text('content');
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default templates
        DB::table('sms_templates')->insert([
            [
                'key' => 'order_confirmation',
                'name' => 'Order Confirmation',
                'content' => 'Thanks {customer_name}! Your order #{order_number} has been received. Total: {order_total}. View status: {order_url}',
                'variables' => json_encode(['customer_name', 'order_number', 'order_total', 'order_url']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'order_status_update',
                'name' => 'Order Status Update',
                'content' => 'Update on order #{order_number}: Your order is now {status}.',
                'variables' => json_encode(['order_number', 'status']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed default email templates if they don't exist
        if (Schema::hasTable('email_templates')) {
            if (DB::table('email_templates')->where('key', 'order_confirmation')->doesntExist()) {
                DB::table('email_templates')->insert([
                    [
                        'key' => 'order_confirmation',
                        'name' => 'Order Confirmation',
                        'subject' => 'Order Confirmation #{order_number}',
                        'body_html' => '<h1>Thanks for your order!</h1><p>Hi {customer_name},</p><p>We received your order #{order_number} for {order_total}.</p><p><a href="{order_url}">Track Order</a></p>',
                        'body_text' => 'Thanks for your order #{order_number} for {order_total}. Track here: {order_url}',
                        'variables' => json_encode(['customer_name', 'order_number', 'order_total', 'order_url']),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_templates');
    }
};

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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('use_default')->default(true);
            $table->string('provider')->nullable(); // smtp, sendgrid, mailgun, ses
            
            // SMTP Configuration
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable(); // Encrypted
            $table->string('smtp_encryption')->nullable(); // tls, ssl
            
            // API-based providers
            $table->text('api_key')->nullable(); // Encrypted
            $table->string('api_region')->nullable(); // For AWS SES
            
            // From details
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('use_default')->default(true);
            $table->string('provider')->nullable(); // twilio, vonage, aws_sns
            
            $table->text('api_key')->nullable(); // Encrypted
            $table->text('api_secret')->nullable(); // Encrypted
            $table->string('from_number')->nullable();
            $table->string('api_region')->nullable(); // For AWS SNS
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // new_staff, new_order, etc.
            $table->string('name'); // Human-readable name
            $table->string('subject');
            $table->longText('body_html');
            $table->longText('body_text')->nullable();
            $table->json('variables')->nullable(); // Available variables for template
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('sms_settings');
        Schema::dropIfExists('email_settings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('smtp_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Transactional", "Marketing"
            $table->string('provider')->default('smtp'); // smtp, sendgrid, etc.

            // SMTP Details
            $table->string('host')->nullable();
            $table->string('port')->nullable();
            $table->string('username')->nullable(); // encrypted?
            $table->string('password')->nullable(); // encrypted?
            $table->string('encryption')->nullable(); // tls, ssl

            // API Details (if needed later)
            $table->string('api_key')->nullable();
            $table->string('api_region')->nullable();

            // Sender Details
            $table->string('from_email');
            $table->string('from_name');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp_configurations');
    }
};

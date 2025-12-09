<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            
            // Tenant business information
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->string('domain')->unique();
            $table->string('database_name')->unique()->nullable();
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->enum('status', ['active', 'suspended', 'deleted'])->default('active');

            $table->timestamps();
            $table->json('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}

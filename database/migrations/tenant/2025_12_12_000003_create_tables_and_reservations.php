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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('capacity');
            $table->string('status')->default('available'); // available, occupied, maintenance
            $table->string('location')->nullable(); // indoor, outdoor, terrace
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->nullable()->constrained()->nullOnDelete();
            // customer_id can be added later or linked to global db if needed, using unsignedBigInteger for now logic dependent on architecture
            // prompt says "Customer -> App\Models\Customer (Customer Portal)" which is global.
            // linking to global customer from tenant DB usually implies just storing the ID or using a specific package reference. 
            // flexible for now:
            $table->unsignedBigInteger('customer_id')->nullable()->index(); 
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->integer('party_size');
            $table->dateTime('reservation_time');
            $table->string('status')->default('pending'); // pending, confirmed, seated, cancelled, completed
            $table->text('special_requests')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('tables');
    }
};

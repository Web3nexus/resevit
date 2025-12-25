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
        Schema::create('uptime_pulses', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('up');
            $table->float('cpu_usage')->nullable();
            $table->float('memory_usage')->nullable();
            $table->float('disk_usage')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uptime_pulses');
    }
};

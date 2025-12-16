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
        Schema::table('tables', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            $table->string('shape')->default('rect'); // rect, circle
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            $table->integer('width')->default(100);
            $table->integer('height')->default(100);
            $table->integer('rotation')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn(['room_id', 'shape', 'x', 'y', 'width', 'height', 'rotation']);
        });
    }
};

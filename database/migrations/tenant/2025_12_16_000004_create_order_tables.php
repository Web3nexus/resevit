<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Nullable customer_id for guest checkout or walk-ins if we sync everything here
            // Assuming customers are in the global DB, but we might store a reference or copy minimal data.
            // Requirement says Feature 11 Customer Panel uses Global DB.
            // We'll store a customer_id (global ID) or just customer details for guest.
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending'); // pending, preparing, ready, completed, cancelled
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('type')->default('dine-in'); // dine-in, pickup, delivery
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->restrictOnDelete(); // Don't delete history if item deleted? or use soft deletes? Restrict is safer.
            $table->foreignId('variant_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Price at time of order
            $table->decimal('subtotal', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_item_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained()->nullOnDelete(); // If addon deleted, keep record? or store name.
            $table->decimal('price', 10, 2); // Price at time of order
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_addons');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add e-commerce fields if they don't exist
            if (! Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('customer_id')->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }

            if (! Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }

            if (! Schema::hasColumn('orders', 'order_type')) {
                $table->string('order_type')->default('dine-in')->after('customer_phone'); // dine-in, takeout, delivery
            }

            if (! Schema::hasColumn('orders', 'items')) {
                $table->json('items')->nullable()->after('order_type'); // For online orders, store cart items as JSON
            }

            if (! Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('items');
            }

            if (! Schema::hasColumn('orders', 'tax')) {
                $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            }

            if (! Schema::hasColumn('orders', 'service_fee')) {
                $table->decimal('service_fee', 10, 2)->default(0)->after('tax');
            }

            if (! Schema::hasColumn('orders', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->default(0)->after('service_fee');
            }

            if (! Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('delivery_fee');
            }

            if (! Schema::hasColumn('orders', 'payment_intent_id')) {
                $table->string('payment_intent_id')->nullable()->after('payment_status');
            }

            if (! Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_intent_id');
            }

            if (! Schema::hasColumn('orders', 'delivery_address')) {
                $table->text('delivery_address')->nullable()->after('paid_at');
            }

            if (! Schema::hasColumn('orders', 'order_source')) {
                $table->string('order_source')->default('pos')->after('delivery_address'); // pos, online, phone
            }

            if (! Schema::hasColumn('orders', 'staff_id')) {
                $table->foreignId('staff_id')->nullable()->after('order_source')->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('orders', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('staff_id')->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',
                'customer_name',
                'customer_email',
                'customer_phone',
                'order_type',
                'items',
                'subtotal',
                'tax',
                'service_fee',
                'delivery_fee',
                'total',
                'payment_intent_id',
                'paid_at',
                'delivery_address',
                'order_source',
            ]);
        });
    }
};

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
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('shipping_status', ['pending', 'processing', 'shipped', 'delivered'])->default('pending');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('cod_fee', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->foreignId('shipping_address_id')->nullable()->constrained('shipping_addresses')->nullOnDelete();
            $table->foreignId('shipping_company_id')->nullable()->constrained('shipping_companies')->nullOnDelete();
            $table->string('tracking_number')->nullable();
            $table->string('shipping_method')->default('standard');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('order_number');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('total', 12, 2);
            $table->json('options')->nullable();
            $table->text('custom_text')->nullable();
            $table->string('custom_file')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->enum('method', ['cod', 'bank_transfer'])->default('cod');
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->decimal('amount', 12, 2);
            $table->string('confirmation_code')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

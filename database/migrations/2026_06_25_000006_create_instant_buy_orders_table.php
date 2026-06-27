<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instant_buy_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->unsignedBigInteger('user_id')->nullable();

            // Customer info
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 20);
            $table->string('email', 255)->nullable();

            // Address (codes, not FK — countries/states are config-based)
            $table->string('country_code', 2)->index();
            $table->string('state_code', 20)->nullable();
            $table->string('city', 100);
            $table->text('address');
            $table->text('notes')->nullable();

            // Product info
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->json('options')->nullable();
            $table->string('custom_text', 500)->nullable();
            $table->string('custom_file', 255)->nullable();

            // Pricing
            $table->decimal('product_price', 10, 2);
            $table->decimal('options_price', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('grand_total', 10, 2);

            // Shipping
            $table->string('shipping_method_type', 50)->nullable();
            $table->string('shipping_method_name', 100)->nullable();
            $table->string('delivery_type', 20)->default('home');
            $table->unsignedBigInteger('shipping_company_id')->nullable();

            // Status
            $table->string('status', 30)->default('new')->index();
            $table->string('payment_status', 20)->default('pending');
            $table->string('payment_method', 50)->default('cod');

            // Security
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Notifications
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('shipping_company_id')->references('id')->on('shipping_companies')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instant_buy_orders');
    }
};

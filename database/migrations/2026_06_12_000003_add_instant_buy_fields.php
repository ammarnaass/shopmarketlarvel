<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Split name into first_name + last_name for shipping addresses
        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('email')->nullable()->after('phone');
        });

        // Add is_instant (mark orders created via instant-buy form)
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_instant_buy')->default(false)->after('user_id');
            $table->string('guest_email')->nullable()->after('user_id');
            $table->string('guest_phone')->nullable()->after('guest_email');
            $table->index('is_instant_buy');
        });

        // Order items: store which option/variant metadata was selected for instant buy
        Schema::table('order_items', function (Blueprint $table) {
            $table->json('options_summary')->nullable()->after('options');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('options_summary');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['is_instant_buy']);
            $table->dropColumn(['is_instant_buy', 'guest_email', 'guest_phone']);
        });

        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'email']);
        });
    }
};

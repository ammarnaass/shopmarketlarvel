<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->decimal('cost_per_kg', 10, 2)->nullable()->after('flat_rate_amount');
            $table->decimal('free_threshold', 10, 2)->nullable()->after('cost_per_kg');
            $table->decimal('min_order_value', 10, 2)->nullable()->after('free_threshold');
            $table->decimal('max_order_value', 10, 2)->nullable()->after('min_order_value');
            $table->json('covered_cities')->nullable()->after('product_ids');
            $table->json('excluded_cities')->nullable()->after('covered_cities');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn([
                'cost_per_kg', 'free_threshold',
                'min_order_value', 'max_order_value',
                'covered_cities', 'excluded_cities',
            ]);
        });
    }
};

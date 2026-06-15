<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight', 8, 3)->default(0.5)->after('stock')->comment('kg');
            $table->decimal('length', 8, 2)->nullable()->after('weight')->comment('cm');
            $table->decimal('width', 8, 2)->nullable()->after('length');
            $table->decimal('height', 8, 2)->nullable()->after('width');
            $table->integer('low_stock_threshold')->default(5)->after('height');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('weight', 8, 3)->default(0)->after('shipping_cost');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight', 'length', 'width', 'height', 'low_stock_threshold']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
};

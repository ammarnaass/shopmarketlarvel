<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_zones', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained('shipping_companies')->nullOnDelete();
            }
            if (!Schema::hasColumn('shipping_zones', 'delivery_type')) {
                $table->enum('delivery_type', ['home', 'office', 'both'])->default('both')->after('cities');
            }
            if (!Schema::hasColumn('shipping_zones', 'home_cost')) {
                $table->decimal('home_cost', 10, 2)->nullable()->after('express_cost');
            }
            if (!Schema::hasColumn('shipping_zones', 'home_express_cost')) {
                $table->decimal('home_express_cost', 10, 2)->nullable()->after('home_cost');
            }
            if (!Schema::hasColumn('shipping_zones', 'office_cost')) {
                $table->decimal('office_cost', 10, 2)->nullable()->after('home_express_cost');
            }
            if (!Schema::hasColumn('shipping_zones', 'office_express_cost')) {
                $table->decimal('office_express_cost', 10, 2)->nullable()->after('office_cost');
            }
            if (!Schema::hasColumn('shipping_zones', 'cost_per_kg')) {
                $table->decimal('cost_per_kg', 10, 2)->nullable()->after('office_express_cost');
            }
            if (!Schema::hasColumn('shipping_zones', 'estimated_days_standard')) {
                $table->string('estimated_days_standard', 30)->nullable()->after('cost_per_kg');
            }
            if (!Schema::hasColumn('shipping_zones', 'estimated_days_express')) {
                $table->string('estimated_days_express', 30)->nullable()->after('estimated_days_standard');
            }
            if (!Schema::hasColumn('shipping_zones', 'description')) {
                $table->text('description')->nullable()->after('estimated_days_express');
            }
            if (!Schema::hasColumn('shipping_zones', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('description');
            }
        });

        // Index already exists from previous migration attempt

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_type')) {
                $table->enum('delivery_type', ['home', 'office'])->default('home')->after('shipping_company_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_type']);
        });

        Schema::table('shipping_zones', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropIndex(['company_id', 'status', 'delivery_type']);
            $table->dropColumn([
                'company_id', 'delivery_type', 'home_cost', 'home_express_cost',
                'office_cost', 'office_express_cost', 'cost_per_kg',
                'estimated_days_standard', 'estimated_days_express',
                'description', 'sort_order',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enhance shipping_zones table
        Schema::table('shipping_zones', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_zones', 'states')) {
                $table->json('states')->nullable()->after('countries');
            }
            if (!Schema::hasColumn('shipping_zones', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('cities');
            }
            if (!Schema::hasColumn('shipping_zones', 'priority')) {
                $table->integer('priority')->default(0)->after('is_default');
            }
        });

        // 2. Enhance shipping_companies table
        Schema::table('shipping_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_companies', 'website')) {
                $table->string('website')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('shipping_companies', 'api_key')) {
                $table->string('api_key')->nullable()->after('api_endpoint');
            }
            if (!Schema::hasColumn('shipping_companies', 'api_secret')) {
                $table->string('api_secret')->nullable()->after('api_key');
            }
            if (Schema::hasColumn('shipping_companies', 'api_enabled') && !Schema::hasColumn('shipping_companies', 'is_active')) {
                $table->renameColumn('api_enabled', 'is_active');
            }
        });

        // 3. Create shipping_methods table
        if (!Schema::hasTable('shipping_methods')) {
            Schema::create('shipping_methods', function (Blueprint $table) {
                $table->id();
                $table->foreignId('zone_id')->constrained('shipping_zones')->cascadeOnDelete();
                $table->string('name');
                $table->enum('type', ['flat_rate', 'free_shipping', 'weight_based', 'zone_based', 'product_based', 'courier_api'])->default('flat_rate');
                $table->foreignId('carrier_id')->nullable()->constrained('shipping_companies')->nullOnDelete();
                $table->decimal('flat_rate_amount', 10, 2)->nullable();
                $table->decimal('free_shipping_min', 10, 2)->nullable();
                $table->enum('free_shipping_requires', ['min_amount', 'coupon', 'both'])->default('min_amount');
                $table->json('weight_ranges')->nullable();
                $table->json('product_ids')->nullable();
                $table->json('api_settings')->nullable();
                $table->json('zone_rates')->nullable();
                $table->string('estimated_days', 50)->nullable();
                $table->enum('tax_status', ['taxable', 'none'])->default('taxable');
                $table->boolean('status')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 4. Create shipping_labels table
        if (!Schema::hasTable('shipping_labels')) {
            Schema::create('shipping_labels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('carrier_id')->constrained('shipping_companies');
                $table->string('tracking_number');
                $table->string('label_pdf')->nullable();
                $table->decimal('weight', 8, 2)->nullable();
                $table->decimal('cost', 10, 2)->default(0);
                $table->enum('status', ['pending', 'printed', 'shipped', 'delivered', 'returned'])->default('pending');
                $table->timestamp('shipped_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamps();
            });
        }

        // 5. Create shipping_tracking table
        if (!Schema::hasTable('shipping_tracking')) {
            Schema::create('shipping_tracking', function (Blueprint $table) {
                $table->id();
                $table->foreignId('label_id')->constrained('shipping_labels')->cascadeOnDelete();
                $table->string('status');
                $table->string('location')->nullable();
                $table->text('description')->nullable();
                $table->timestamp('tracked_at')->useCurrent();
            });
        }

        // 6. Add shipping_method_id to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->foreignId('shipping_method_id')->nullable()->after('shipping_company_id')->constrained('shipping_methods')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('shipping_method_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_method_id']);
            $table->dropColumn(['shipping_method_id', 'tracking_number']);
        });

        Schema::dropIfExists('shipping_tracking');
        Schema::dropIfExists('shipping_labels');
        Schema::dropIfExists('shipping_methods');

        Schema::table('shipping_companies', function (Blueprint $table) {
            $table->dropColumn(['website', 'api_key', 'api_secret']);
            $table->renameColumn('is_active', 'api_enabled');
        });

        Schema::table('shipping_zones', function (Blueprint $table) {
            $table->dropColumn(['states', 'is_default', 'priority']);
        });
    }
};

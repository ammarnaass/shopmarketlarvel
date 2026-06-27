<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_shipping_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->json('allowed_methods')->nullable()->comment('[method_id]');
            $table->json('excluded_methods')->nullable()->comment('[method_id]');
            $table->decimal('max_weight', 8, 2)->nullable();
            $table->json('max_dimensions')->nullable();
            $table->json('allowed_zones')->nullable()->comment('[zone_id]');
            $table->json('excluded_zones')->nullable()->comment('[zone_id]');
            $table->boolean('requires_signature')->default(false);
            $table->boolean('fragile')->default(false);
            $table->boolean('hazardous')->default(false);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_shipping_rules');
    }
};

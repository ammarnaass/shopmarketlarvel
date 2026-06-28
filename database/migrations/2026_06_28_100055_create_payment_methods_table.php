<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('icon')->default('payments');
            $table->string('color', 30)->default('blue');
            $table->text('description')->nullable();
            $table->enum('type', ['manual', 'gateway', 'wallet'])->default('manual');
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('fees_type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('fees_value', 10, 2)->default(0);
            $table->decimal('min_order', 10, 2)->nullable();
            $table->decimal('max_order', 10, 2)->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

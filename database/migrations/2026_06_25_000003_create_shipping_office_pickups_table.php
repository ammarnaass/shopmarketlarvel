<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_office_pickups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrier_id')->constrained('shipping_companies')->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('address');
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('country_code', 2);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('working_hours')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['country_code', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_office_pickups');
    }
};

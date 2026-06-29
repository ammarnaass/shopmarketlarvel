<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instant_buy_settings', function (Blueprint $table) {
            $table->string('field_country_placeholder', 100)->nullable()->change();
            $table->string('field_state_placeholder', 100)->nullable()->change();
            $table->string('field_city_placeholder', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('instant_buy_settings', function (Blueprint $table) {
            $table->string('field_country_placeholder', 100)->nullable(false)->default('اختر الدولة')->change();
            $table->string('field_state_placeholder', 100)->nullable(false)->default('اختر الولاية')->change();
            $table->string('field_city_placeholder', 100)->nullable(false)->default('أدخل المدينة')->change();
        });
    }
};

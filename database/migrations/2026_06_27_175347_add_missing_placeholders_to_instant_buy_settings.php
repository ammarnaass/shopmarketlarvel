<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instant_buy_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('instant_buy_settings', 'field_country_placeholder')) {
                $table->string('field_country_placeholder', 100)->default('اختر الدولة');
            }
            if (!Schema::hasColumn('instant_buy_settings', 'field_state_placeholder')) {
                $table->string('field_state_placeholder', 100)->default('اختر الولاية');
            }
            if (!Schema::hasColumn('instant_buy_settings', 'field_city_placeholder')) {
                $table->string('field_city_placeholder', 100)->default('أدخل المدينة');
            }
        });
    }

    public function down(): void
    {
        Schema::table('instant_buy_settings', function (Blueprint $table) {
            $table->dropColumn(['field_country_placeholder', 'field_state_placeholder', 'field_city_placeholder']);
        });
    }
};

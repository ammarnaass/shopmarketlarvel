<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instant_buy_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('instant_buy_settings', 'field_coupon_required')) {
                $table->boolean('field_coupon_required')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('instant_buy_settings', function (Blueprint $table) {
            $table->dropColumn('field_coupon_required');
        });
    }
};

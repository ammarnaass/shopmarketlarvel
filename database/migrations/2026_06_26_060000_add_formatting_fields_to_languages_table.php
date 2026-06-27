<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->string('flag', 10)->nullable()->after('native_name');
            $table->string('locale', 10)->nullable()->after('flag');
            $table->string('date_format', 20)->default('Y-m-d')->after('sort_order');
            $table->string('time_format', 20)->default('H:i')->after('date_format');
            $table->string('decimal_separator', 5)->default('.')->after('time_format');
            $table->string('thousands_separator', 5)->default(',')->after('decimal_separator');
            $table->string('currency_position', 10)->default('after')->after('thousands_separator');
        });
    }

    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn(['flag', 'locale', 'date_format', 'time_format', 'decimal_separator', 'thousands_separator', 'currency_position']);
        });
    }
};

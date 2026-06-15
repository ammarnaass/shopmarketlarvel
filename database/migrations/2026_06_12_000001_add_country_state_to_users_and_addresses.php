<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('country_code', 2)->default('SD')->after('phone');
            $table->string('state_code', 5)->nullable()->after('country_code');
        });

        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->string('country_code', 2)->default('SD')->after('phone');
            $table->string('state_code', 5)->nullable()->after('country_code');
            $table->index(['country_code', 'state_code']);
        });
    }

    public function down(): void
    {
        Schema::table('shipping_addresses', function (Blueprint $table) {
            $table->dropIndex(['country_code', 'state_code']);
            $table->dropColumn(['country_code', 'state_code']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'state_code']);
        });
    }
};

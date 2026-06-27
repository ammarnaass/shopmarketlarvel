<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instant_buy_settings', function (Blueprint $table) {
            // Email field
            $table->boolean('field_email_enabled')->default(true);
            $table->boolean('field_email_required')->default(false);
            $table->string('field_email_label', 50)->default('البريد الإلكتروني');
            $table->string('field_email_placeholder', 100)->default('example@mail.com');

            // District / neighborhood field
            $table->boolean('field_district_enabled')->default(true);
            $table->boolean('field_district_required')->default(false);
            $table->string('field_district_label', 50)->default('الحي / المنطقة');
            $table->string('field_district_placeholder', 100)->default('الرياض، العمارات');

            // ZIP / postal code field
            $table->boolean('field_zip_enabled')->default(true);
            $table->boolean('field_zip_required')->default(false);
            $table->string('field_zip_label', 50)->default('الرمز البريدي');
            $table->string('field_zip_placeholder', 100)->default('11111');

            // Payment settings
            $table->boolean('show_bank_transfer')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('instant_buy_settings', function (Blueprint $table) {
            $table->dropColumn([
                'field_email_enabled',
                'field_email_required',
                'field_email_label',
                'field_email_placeholder',
                'field_district_enabled',
                'field_district_required',
                'field_district_label',
                'field_district_placeholder',
                'field_zip_enabled',
                'field_zip_required',
                'field_zip_label',
                'field_zip_placeholder',
                'show_bank_transfer',
            ]);
        });
    }
};

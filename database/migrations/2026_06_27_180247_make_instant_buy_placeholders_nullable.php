<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `instant_buy_settings` MODIFY `field_country_placeholder` VARCHAR(100) NULL');
        DB::statement('ALTER TABLE `instant_buy_settings` MODIFY `field_state_placeholder` VARCHAR(100) NULL');
        DB::statement('ALTER TABLE `instant_buy_settings` MODIFY `field_city_placeholder` VARCHAR(100) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `instant_buy_settings` MODIFY `field_country_placeholder` VARCHAR(100) NOT NULL DEFAULT \'اختر الدولة\'');
        DB::statement('ALTER TABLE `instant_buy_settings` MODIFY `field_state_placeholder` VARCHAR(100) NOT NULL DEFAULT \'اختر الولاية\'');
        DB::statement('ALTER TABLE `instant_buy_settings` MODIFY `field_city_placeholder` VARCHAR(100) NOT NULL DEFAULT \'أدخل المدينة\'');
    }
};

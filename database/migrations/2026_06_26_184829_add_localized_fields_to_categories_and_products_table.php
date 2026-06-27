<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_fr')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_fr')->nullable()->after('description_en');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('name_fr')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_fr')->nullable()->after('description_en');
            $table->text('short_description_en')->nullable()->after('short_description');
            $table->text('short_description_fr')->nullable()->after('short_description_en');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_fr', 'description_en', 'description_fr']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_fr', 'description_en', 'description_fr', 'short_description_en', 'short_description_fr']);
        });
    }
};

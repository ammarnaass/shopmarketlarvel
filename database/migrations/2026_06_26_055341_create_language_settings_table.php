<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->string('setting_key');
            $table->text('setting_value')->nullable();
            $table->unique(['language_id', 'setting_key']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_settings');
    }
};

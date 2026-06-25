<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: rebuild the table with the new schema (SQLite can't ALTER COLUMN)
            // Step 1: Create new table
            Schema::create('shipping_addresses_new', function ($table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->string('country_code', 2)->default('SD');
                $table->string('state_code', 5)->nullable();
                $table->string('city');
                $table->string('district')->nullable();
                $table->text('address');
                $table->string('zip', 20)->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });

            // Step 2: Copy data
            $existing = DB::table('shipping_addresses')->get();
            foreach ($existing as $row) {
                DB::table('shipping_addresses_new')->insert((array) $row);
            }

            // Step 3: Swap tables
            Schema::drop('shipping_addresses');
            Schema::rename('shipping_addresses_new', 'shipping_addresses');
        } else {
            // MySQL/MariaDB/PostgreSQL: use raw SQL
            DB::statement('ALTER TABLE shipping_addresses MODIFY COLUMN user_id BIGINT UNSIGNED NULL');
            // Re-add the foreign key (drop and recreate to allow NULL)
            DB::statement('ALTER TABLE shipping_addresses DROP FOREIGN KEY shipping_addresses_user_id_foreign');
            DB::statement('ALTER TABLE shipping_addresses ADD CONSTRAINT shipping_addresses_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        }
    }

    public function down(): void
    {
        // Reverting nullable → not null would lose guest data; we keep it nullable.
    }
};

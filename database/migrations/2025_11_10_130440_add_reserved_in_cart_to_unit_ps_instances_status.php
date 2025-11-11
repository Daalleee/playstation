<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include 'reserved_in_cart'
        DB::statement("ALTER TABLE unit_ps_instances MODIFY COLUMN status ENUM('available', 'rented', 'maintenance', 'unavailable', 'reserved_in_cart')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'reserved_in_cart' from the enum
        DB::statement("ALTER TABLE unit_ps_instances MODIFY COLUMN status ENUM('available', 'rented', 'maintenance', 'unavailable')");
    }
};

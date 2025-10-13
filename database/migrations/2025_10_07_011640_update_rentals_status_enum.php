<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update enum values untuk status di tabel rentals
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('draft', 'pending', 'paid', 'ongoing', 'active', 'returned', 'overdue', 'cancelled') DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Revert ke enum values sebelumnya
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('draft', 'ongoing', 'returned', 'overdue', 'cancelled') DEFAULT 'draft'");
    }
};
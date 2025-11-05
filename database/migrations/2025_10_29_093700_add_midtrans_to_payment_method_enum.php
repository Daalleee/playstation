<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL, kita perlu alter enum dengan cara khusus
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'transfer', 'ewallet', 'midtrans') DEFAULT 'cash'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'transfer', 'ewallet') DEFAULT 'cash'");
    }
};

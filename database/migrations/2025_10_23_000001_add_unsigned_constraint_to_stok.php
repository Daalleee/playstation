<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tidak ada stok negatif sebelum apply constraint
        DB::statement('UPDATE unit_ps SET stok = 0 WHERE stok < 0');
        DB::statement('UPDATE games SET stok = 0 WHERE stok < 0');
        DB::statement('UPDATE accessories SET stok = 0 WHERE stok < 0');
        
        // Ubah kolom stok menjadi unsigned (tidak bisa negatif)
        DB::statement('ALTER TABLE unit_ps MODIFY COLUMN stok INT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE games MODIFY COLUMN stok INT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE accessories MODIFY COLUMN stok INT UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        // Revert ke signed integer
        DB::statement('ALTER TABLE unit_ps MODIFY COLUMN stok INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE games MODIFY COLUMN stok INT NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE accessories MODIFY COLUMN stok INT NOT NULL DEFAULT 0');
    }
};

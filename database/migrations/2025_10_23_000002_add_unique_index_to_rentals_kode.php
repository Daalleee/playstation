<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah unique index sudah ada
        $indexExists = DB::select("SHOW INDEX FROM rentals WHERE Key_name = 'rentals_kode_unique'");
        
        if (empty($indexExists)) {
            Schema::table('rentals', function (Blueprint $table) {
                // Tambah unique constraint untuk kode transaksi
                $table->unique('kode');
            });
        }
    }

    public function down(): void
    {
        // Cek apakah unique index ada sebelum drop
        $indexExists = DB::select("SHOW INDEX FROM rentals WHERE Key_name = 'rentals_kode_unique'");
        
        if (!empty($indexExists)) {
            Schema::table('rentals', function (Blueprint $table) {
                $table->dropUnique(['kode']);
            });
        }
    }
};

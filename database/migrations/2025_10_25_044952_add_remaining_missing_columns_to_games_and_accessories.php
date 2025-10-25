<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan kembali kolom-kolom yang dihapus oleh migrasi cleanup yang salah
        if (!Schema::hasColumn('games', 'genre')) {
            Schema::table('games', function (Blueprint $table) {
                $table->string('genre')->nullable()->after('platform');
            });
        }
        
        if (!Schema::hasColumn('games', 'gambar')) {
            Schema::table('games', function (Blueprint $table) {
                $table->string('gambar')->nullable()->after('harga_per_hari');
            });
        }
        
        if (!Schema::hasColumn('accessories', 'gambar')) {
            Schema::table('accessories', function (Blueprint $table) {
                $table->string('gambar')->nullable()->after('harga_per_hari');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['genre', 'gambar']);
        });
        
        Schema::table('accessories', function (Blueprint $table) {
            $table->dropColumn(['gambar']);
        });
    }
};
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
        // Kolom-kolom ini seharusnya tetap ada berdasarkan struktur aplikasi
        if (!Schema::hasColumn('games', 'platform')) {
            Schema::table('games', function (Blueprint $table) {
                $table->string('platform')->nullable()->after('judul');
            });
        }
        
        if (!Schema::hasColumn('accessories', 'jenis')) {
            Schema::table('accessories', function (Blueprint $table) {
                $table->string('jenis')->nullable()->after('nama');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['platform']);
        });
        
        Schema::table('accessories', function (Blueprint $table) {
            $table->dropColumn(['jenis']);
        });
    }
};
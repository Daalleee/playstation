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
        Schema::table('games', function (Blueprint $table) {
            // Drop duplicate columns that are not being used
            $table->dropColumn(['title', 'platform', 'genre', 'stock', 'price_per_day', 'gambar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Recreate the dropped columns
            $table->string('title')->nullable();
            $table->string('platform')->nullable();
            $table->string('genre')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('price_per_day', 10, 2)->default(0);
            $table->string('gambar')->nullable();
        });
    }
};

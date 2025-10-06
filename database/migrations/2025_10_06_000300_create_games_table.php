<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('platform'); // PS3, PS4, PS5
            $table->string('genre')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('price_per_day', 10, 2)->default(0); // untuk sewa harian disc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};



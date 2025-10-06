<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_ps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->default('Sony');
            $table->string('model'); // e.g., PS3, PS4, PS5
            $table->string('serial_number')->unique();
            $table->decimal('price_per_hour', 10, 2); // tarif sewa per jam
            $table->integer('stock')->default(1);
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_ps');
    }
};



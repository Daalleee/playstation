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
        Schema::create('unit_ps_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_ps_id')->constrained('unit_ps')->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->enum('status', ['available', 'rented', 'maintenance', 'unavailable'])->default('available');
            $table->string('condition')->nullable(); // kondisi unit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_ps_instances');
    }
};

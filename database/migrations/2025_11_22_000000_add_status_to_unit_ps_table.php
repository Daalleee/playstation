<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_ps', function (Blueprint $table) {
            $table->string('status')->default('Tersedia')->after('kondisi');
        });
    }

    public function down(): void
    {
        Schema::table('unit_ps', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

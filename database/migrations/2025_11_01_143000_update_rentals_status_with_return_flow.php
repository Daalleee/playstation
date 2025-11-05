<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Temporarily change column to VARCHAR to allow any value
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        
        // Step 2: Update existing data to match new enum values
        // Map old statuses to new statuses
        DB::table('rentals')->where('status', 'draft')->update(['status' => 'pending']);
        DB::table('rentals')->where('status', 'paid')->update(['status' => 'sedang_disewa']);
        DB::table('rentals')->where('status', 'ongoing')->update(['status' => 'sedang_disewa']);
        DB::table('rentals')->where('status', 'active')->update(['status' => 'sedang_disewa']);
        DB::table('rentals')->where('status', 'returned')->update(['status' => 'selesai']);
        DB::table('rentals')->where('status', 'overdue')->update(['status' => 'sedang_disewa']);
        
        // Step 3: Modify column to new enum values
        // pending: menunggu pembayaran
        // sedang_disewa: pembayaran sukses, barang sedang disewa
        // menunggu_konfirmasi: user sudah mengembalikan, menunggu konfirmasi kasir
        // selesai: kasir sudah konfirmasi pengembalian
        // cancelled: dibatalkan
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'sedang_disewa', 'menunggu_konfirmasi', 'selesai', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Step 1: Revert data to old status values
        DB::table('rentals')->where('status', 'sedang_disewa')->update(['status' => 'ongoing']);
        DB::table('rentals')->where('status', 'menunggu_konfirmasi')->update(['status' => 'returned']);
        DB::table('rentals')->where('status', 'selesai')->update(['status' => 'returned']);
        
        // Step 2: Revert to old enum values
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('draft', 'pending', 'paid', 'ongoing', 'active', 'returned', 'overdue', 'cancelled') DEFAULT 'draft'");
    }
};

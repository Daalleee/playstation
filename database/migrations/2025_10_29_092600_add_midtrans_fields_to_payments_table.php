<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Midtrans transaction details
            $table->string('order_id')->nullable()->after('reference');
            $table->string('transaction_id')->nullable()->after('order_id');
            $table->string('transaction_status')->nullable()->after('transaction_id');
            $table->string('payment_type')->nullable()->after('transaction_status');
            $table->decimal('gross_amount', 12, 2)->nullable()->after('payment_type');
            $table->timestamp('transaction_time')->nullable()->after('gross_amount');
            $table->string('fraud_status')->nullable()->after('transaction_time');
            $table->text('raw_response')->nullable()->after('fraud_status');
            
            // Index for faster lookup
            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('transaction_status');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['transaction_id']);
            $table->dropIndex(['transaction_status']);
            
            $table->dropColumn([
                'order_id',
                'transaction_id',
                'transaction_status',
                'payment_type',
                'gross_amount',
                'transaction_time',
                'fraud_status',
                'raw_response',
            ]);
        });
    }
};

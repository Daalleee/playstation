<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'method',
        'amount',
        'reference',
        'paid_at',
        'order_id',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'gross_amount',
        'transaction_time',
        'fraud_status',
        'raw_response',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'transaction_time' => 'datetime',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    /**
     * Update payment from Midtrans notification
     */
    public function updateFromMidtrans(array $notification): void
    {
        $this->update([
            'transaction_id' => $notification['transaction_id'] ?? null,
            'transaction_status' => $notification['transaction_status'] ?? null,
            'payment_type' => $notification['payment_type'] ?? null,
            'gross_amount' => $notification['gross_amount'] ?? null,
            'transaction_time' => $notification['transaction_time'] ?? null,
            'fraud_status' => $notification['fraud_status'] ?? null,
            'raw_response' => json_encode($notification),
        ]);

        // Update paid_at if payment is successful
        if (in_array($notification['transaction_status'] ?? '', ['capture', 'settlement'])) {
            $this->update(['paid_at' => now()]);
        }
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return in_array($this->transaction_status, ['capture', 'settlement']);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->transaction_status === 'pending';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire']);
    }

    /**
     * Scope for successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->whereIn('transaction_status', ['capture', 'settlement']);
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('transaction_status', ['deny', 'cancel', 'expire']);
    }
}



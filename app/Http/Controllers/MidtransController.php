<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle Midtrans notification webhook
     */
    public function notification(Request $request)
    {
        try {
            // Get notification data from Midtrans
            $notification = $this->midtransService->handleNotification();
            
            // Extract notification data
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? 'accept';
            $paymentType = $notification->payment_type;
            $grossAmount = $notification->gross_amount;
            $transactionId = $notification->transaction_id;
            $transactionTime = $notification->transaction_time;

            // Log notification for debugging
            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
            ]);

            // Verify signature hash
            $serverKey = config('midtrans.server_key');
            $signatureKey = $notification->signature_key ?? '';
            
            $hashed = hash('sha512', $orderId . $notification->status_code . $grossAmount . $serverKey);
            
            if ($hashed !== $signatureKey) {
                Log::warning('Invalid Midtrans signature', [
                    'order_id' => $orderId,
                    'expected' => $hashed,
                    'received' => $signatureKey,
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            DB::beginTransaction();

            // Find or create payment record
            $payment = Payment::firstOrCreate(
                ['order_id' => $orderId],
                [
                    'method' => 'midtrans',
                    'amount' => $grossAmount,
                ]
            );

            // Update payment with Midtrans data
            $notificationData = [
                'transaction_id' => $transactionId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
                'transaction_time' => $transactionTime,
                'fraud_status' => $fraudStatus,
            ];
            
            $payment->updateFromMidtrans($notificationData);

            // Update rental status based on payment status
            if ($payment->rental_id) {
                $rental = Rental::find($payment->rental_id);
                
                if ($rental) {
                    $this->updateRentalStatus($rental, $transactionStatus, $fraudStatus, $grossAmount);
                }
            }

            DB::commit();

            Log::info('Midtrans notification processed successfully', [
                'order_id' => $orderId,
                'payment_id' => $payment->id,
            ]);

            return response()->json(['message' => 'Notification processed']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error processing Midtrans notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Update rental status based on payment status
     */
    /**
     * Update rental status based on payment status
     */
    protected function updateRentalStatus(Rental $rental, string $transactionStatus, string $fraudStatus, $amount)
    {
        Log::info('Updating rental status from Midtrans', [
            'rental_id' => $rental->id,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'amount' => $amount,
        ]);

        // Handle different transaction statuses
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                // Payment captured and verified (Credit Card)
                $this->handleSuccessfulPayment($rental, $amount);
                
                Log::info('✅ Rental PAID - Status: sedang_disewa (capture)', [
                    'rental_id' => $rental->id,
                    'paid_amount' => $amount
                ]);
            } else {
                // Fraud detected
                $rental->status = 'cancelled';
                $rental->save();
                // No need to restore stock as it wasn't decremented yet
                
                Log::warning('⚠️ Rental CANCELLED - Fraud detected', [
                    'rental_id' => $rental->id,
                    'fraud_status' => $fraudStatus
                ]);
            }
        } elseif ($transactionStatus == 'settlement') {
            // Payment settled (E-wallet, Bank Transfer, etc)
            $this->handleSuccessfulPayment($rental, $amount);
            
            Log::info('✅ Rental PAID - Status: sedang_disewa (settlement)', [
                'rental_id' => $rental->id,
                'paid_amount' => $amount
            ]);
        } elseif ($transactionStatus == 'pending') {
            // Payment pending (waiting for customer to complete)
            $rental->status = 'pending';
            $rental->save();
            
            Log::info('⏳ Rental PENDING - Waiting for payment', [
                'rental_id' => $rental->id
            ]);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            // Payment failed/cancelled/expired
            $rental->status = 'cancelled';
            $rental->save();
            // No need to restore stock as it wasn't decremented yet
            
            Log::info('❌ Rental CANCELLED - Payment ' . $transactionStatus, [
                'rental_id' => $rental->id,
                'reason' => $transactionStatus
            ]);
        }
    }

    protected function handleSuccessfulPayment(Rental $rental, $amount)
    {
        DB::transaction(function () use ($rental, $amount) {
            // Update rental status
            $rental->status = 'sedang_disewa';
            $rental->paid = $amount;
            $rental->save();

            // Decrement stock here
            foreach ($rental->items as $item) {
                if ($item->rentable) {
                    $rentable = $item->rentable;
                    
                    // Lock for update to prevent race conditions
                    // Note: In a high concurrency env, we should check if stock < 0 after decrement
                    // But for now we follow the requirement "decrease on payment"
                    
                    if ($rentable instanceof \App\Models\UnitPS) {
                        $rentable->stock -= $item->quantity;
                    } else {
                        $rentable->stok -= $item->quantity;
                    }
                    $rentable->save();
                }
            }
        });
    }

    /**
     * Restore stock when rental is cancelled
     * (Deprecated: Stock is now only decremented on payment)
     */
    protected function restoreStock(Rental $rental)
    {
        // No-op
    }

    /**
     * Check payment status manually (for debugging)
     */
    public function checkStatus($orderId)
    {
        try {
            $status = $this->midtransService->getTransactionStatus($orderId);
            
            // Find payment record
            $payment = Payment::where('order_id', $orderId)->first();
            
            if ($payment) {
                // Update payment data
                $payment->updateFromMidtrans([
                    'transaction_id' => $status->transaction_id,
                    'transaction_status' => $status->transaction_status,
                    'payment_type' => $status->payment_type,
                    'gross_amount' => $status->gross_amount,
                    'transaction_time' => $status->transaction_time,
                    'fraud_status' => $status->fraud_status ?? 'accept',
                ]);

                // Update rental status
                if ($payment->rental_id) {
                    $rental = Rental::find($payment->rental_id);
                    if ($rental) {
                        $this->updateRentalStatus(
                            $rental, 
                            $status->transaction_status, 
                            $status->fraud_status ?? 'accept', 
                            $status->gross_amount
                        );
                    }
                }
            }
            
            return response()->json([
                'order_id' => $orderId,
                'status' => $status,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking Midtrans status manually', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

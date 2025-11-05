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
                $rental->status = 'sedang_disewa';
                $rental->paid = $amount;
                $rental->save();
                
                Log::info('✅ Rental PAID - Status: sedang_disewa (capture)', [
                    'rental_id' => $rental->id,
                    'paid_amount' => $amount
                ]);
            } else {
                // Fraud detected
                $rental->status = 'cancelled';
                $rental->save();
                $this->restoreStock($rental);
                
                Log::warning('⚠️ Rental CANCELLED - Fraud detected', [
                    'rental_id' => $rental->id,
                    'fraud_status' => $fraudStatus
                ]);
            }
        } elseif ($transactionStatus == 'settlement') {
            // Payment settled (E-wallet, Bank Transfer, etc)
            $rental->status = 'sedang_disewa';
            $rental->paid = $amount;
            $rental->save();
            
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
            $this->restoreStock($rental);
            
            Log::info('❌ Rental CANCELLED - Payment ' . $transactionStatus, [
                'rental_id' => $rental->id,
                'reason' => $transactionStatus
            ]);
        }
    }

    /**
     * Restore stock when rental is cancelled
     */
    protected function restoreStock(Rental $rental)
    {
        foreach ($rental->items as $item) {
            if ($item->rentable) {
                // Check if it's UnitPS (uses 'stock') or other models (use 'stok')
                $isUnitPS = $item->rentable instanceof \App\Models\UnitPS;
                
                if ($isUnitPS) {
                    $item->rentable->stock += $item->quantity;
                    $newStock = $item->rentable->stock;
                } else {
                    $item->rentable->stok += $item->quantity;
                    $newStock = $item->rentable->stok;
                }
                
                $item->rentable->save();
                
                Log::info('Stock restored', [
                    'item_type' => get_class($item->rentable),
                    'item_id' => $item->rentable->id,
                    'quantity_restored' => $item->quantity,
                    'new_stock' => $newStock
                ]);
            }
        }
    }

    /**
     * Check payment status manually (for debugging)
     */
    public function checkStatus($orderId)
    {
        try {
            $status = $this->midtransService->getTransactionStatus($orderId);
            
            return response()->json([
                'order_id' => $orderId,
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

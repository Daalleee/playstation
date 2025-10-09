<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    public function createSnapToken(array $params): string
    {
        return Snap::getSnapToken($params);
    }

    public function getTransactionStatus(string $orderId)
    {
        return Transaction::status($orderId);
    }

    public function handleNotification(): Notification
    {
        return new Notification();
    }
}

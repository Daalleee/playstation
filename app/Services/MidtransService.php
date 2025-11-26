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

        // Initialize curlOptions with empty array if not set
        if (!is_array(Config::$curlOptions)) {
            Config::$curlOptions = [];
        }
        
        // Ensure CURLOPT_HTTPHEADER is initialized as an array
        if (!isset(Config::$curlOptions[CURLOPT_HTTPHEADER])) {
            Config::$curlOptions[CURLOPT_HTTPHEADER] = [];
        }

        // In local/dev environments (e.g., Windows), cURL may fail due to missing CA bundle.
        // Relax SSL verification only for local to prevent "SSL certificate problem: unable to get local issuer certificate".
        if (app()->environment('local', 'development')) {
            Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
            Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
            // Fix "Could not resolve host" by forcing IPv4
            Config::$curlOptions[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
        }
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

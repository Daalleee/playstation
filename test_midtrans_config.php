<?php

/**
 * Script untuk test konfigurasi Midtrans
 * Jalankan dengan: php test_midtrans_config.php
 */

echo "=== MIDTRANS CONFIGURATION TEST ===\n\n";

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "✓ Laravel loaded\n\n";

// Test 1: Check Config
echo "1. Checking configuration...\n";
$serverKey = config('midtrans.server_key');
$clientKey = config('midtrans.client_key');
$isProduction = config('midtrans.is_production');

if (empty($serverKey)) {
    echo "   ❌ MIDTRANS_SERVER_KEY tidak diset di .env\n";
    echo "   → Tambahkan: MIDTRANS_SERVER_KEY=your-server-key\n\n";
} else {
    echo "   ✓ Server Key: " . substr($serverKey, 0, 10) . "..." . substr($serverKey, -5) . "\n";
}

if (empty($clientKey)) {
    echo "   ❌ MIDTRANS_CLIENT_KEY tidak diset di .env\n";
    echo "   → Tambahkan: MIDTRANS_CLIENT_KEY=your-client-key\n\n";
} else {
    echo "   ✓ Client Key: " . substr($clientKey, 0, 10) . "..." . substr($clientKey, -5) . "\n";
}

echo "   ✓ Environment: " . ($isProduction ? "PRODUCTION" : "SANDBOX") . "\n";
echo "   ✓ Sanitized: " . (config('midtrans.is_sanitized') ? "Yes" : "No") . "\n";
echo "   ✓ 3DS: " . (config('midtrans.is_3ds') ? "Yes" : "No") . "\n\n";

// Test 2: Check Midtrans Package
echo "2. Checking Midtrans package...\n";
if (class_exists('Midtrans\Config')) {
    echo "   ✓ Midtrans package installed\n";
} else {
    echo "   ❌ Midtrans package not found\n";
    echo "   → Run: composer require midtrans/midtrans-php\n";
}

if (class_exists('Midtrans\Snap')) {
    echo "   ✓ Midtrans\Snap available\n";
} else {
    echo "   ❌ Midtrans\Snap not available\n";
}

if (class_exists('Midtrans\Notification')) {
    echo "   ✓ Midtrans\Notification available\n\n";
} else {
    echo "   ❌ Midtrans\Notification not available\n\n";
}

// Test 3: Check Service
echo "3. Checking MidtransService...\n";
try {
    $service = app(\App\Services\MidtransService::class);
    echo "   ✓ MidtransService can be instantiated\n\n";
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Check Routes
echo "4. Checking routes...\n";
$routes = [
    'midtrans.notification' => 'POST /midtrans/notification',
    'midtrans.status' => 'GET /midtrans/status/{orderId}',
    'pelanggan.rentals.store' => 'POST /pelanggan/rentals',
];

foreach ($routes as $name => $description) {
    if (app('router')->has($name)) {
        echo "   ✓ Route '$name' exists ($description)\n";
    } else {
        echo "   ❌ Route '$name' not found\n";
    }
}
echo "\n";

// Test 5: Check Database
echo "5. Checking database tables...\n";
try {
    if (Schema::hasTable('payments')) {
        echo "   ✓ Table 'payments' exists\n";
        
        $columns = ['order_id', 'transaction_id', 'transaction_status', 'payment_type'];
        foreach ($columns as $column) {
            if (Schema::hasColumn('payments', $column)) {
                echo "   ✓ Column 'payments.$column' exists\n";
            } else {
                echo "   ❌ Column 'payments.$column' missing\n";
            }
        }
    } else {
        echo "   ❌ Table 'payments' not found\n";
        echo "   → Run: php artisan migrate\n";
    }
    
    if (Schema::hasTable('rentals')) {
        echo "   ✓ Table 'rentals' exists\n";
    } else {
        echo "   ❌ Table 'rentals' not found\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test 6: Check View
echo "6. Checking payment view...\n";
$viewPath = resource_path('views/pelanggan/payment/midtrans.blade.php');
if (file_exists($viewPath)) {
    echo "   ✓ Payment view exists\n";
    
    $content = file_get_contents($viewPath);
    if (strpos($content, 'snap.pay') !== false) {
        echo "   ✓ Snap.js integration found\n";
    } else {
        echo "   ❌ Snap.js integration not found\n";
    }
    
    if (strpos($content, 'config(\'midtrans.client_key\')') !== false) {
        echo "   ✓ Client key configuration found\n";
    } else {
        echo "   ❌ Client key configuration not found\n";
    }
} else {
    echo "   ❌ Payment view not found\n";
}
echo "\n";

// Summary
echo "=== SUMMARY ===\n";
if (!empty($serverKey) && !empty($clientKey)) {
    echo "✓ Konfigurasi dasar sudah lengkap!\n\n";
    echo "LANGKAH SELANJUTNYA:\n";
    echo "1. Pastikan .env sudah diisi dengan Server Key dan Client Key dari Midtrans\n";
    echo "2. Jalankan aplikasi: php artisan serve\n";
    echo "3. Coba buat rental dan lakukan pembayaran\n";
    echo "4. Gunakan test card: 4811 1111 1111 1114 (CVV: 123, Exp: 01/25)\n\n";
    echo "UNTUK TESTING WEBHOOK:\n";
    echo "1. Install ngrok: https://ngrok.com/download\n";
    echo "2. Jalankan: ngrok http 8000\n";
    echo "3. Daftarkan URL ngrok di Midtrans Dashboard\n";
    echo "4. Format: https://your-ngrok-url.ngrok.io/midtrans/notification\n\n";
} else {
    echo "❌ Konfigurasi belum lengkap!\n";
    echo "Silakan lengkapi MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di file .env\n\n";
    echo "Cara mendapatkan API Keys:\n";
    echo "1. Daftar di https://dashboard.sandbox.midtrans.com/register\n";
    echo "2. Login dan buka Settings → Access Keys\n";
    echo "3. Copy Server Key dan Client Key\n";
    echo "4. Paste ke file .env\n\n";
}

echo "Dokumentasi lengkap: Baca file MIDTRANS_SETUP.md\n";

# Implementasi Webhook Midtrans - Status Pembayaran Otomatis

## 📋 Overview

Sistem webhook Midtrans telah diimplementasikan untuk **otomatis mengupdate status pembayaran** ketika customer melakukan pembayaran melalui Midtrans. Status "LUNAS" akan langsung muncul di interface user dan kasir tanpa perlu refresh manual.

---

## ✅ Fitur yang Sudah Diimplementasikan

### 1. **Webhook Handler** (`MidtransController.php`)
- ✅ Menerima notifikasi dari Midtrans via POST request
- ✅ Verifikasi signature key untuk keamanan (SHA512)
- ✅ Update status payment di database
- ✅ Update status rental otomatis:
  - `capture` + `fraud_status: accept` → Status: `sedang_disewa`, Paid: ✓
  - `settlement` → Status: `sedang_disewa`, Paid: ✓
  - `pending` → Status: `pending`
  - `deny/expire/cancel` → Status: `cancelled`, Stock dikembalikan
- ✅ Logging lengkap untuk debugging

### 2. **Model Payment** (`Payment.php`)
- ✅ Method `updateFromMidtrans()` untuk update data dari webhook
- ✅ Helper methods: `isSuccessful()`, `isPending()`, `isFailed()`
- ✅ Auto-update `paid_at` timestamp saat pembayaran sukses

### 3. **Interface User (Pelanggan)**

#### Halaman Detail Rental (`pelanggan/rentals/show.blade.php`)
- ✅ Badge status pembayaran:
  - **✓ LUNAS** (hijau) - Jika `paid >= total`
  - **⚠ KURANG BAYAR** (kuning) - Jika `paid > 0` tapi `< total`
  - **✗ BELUM LUNAS** (merah) - Jika `paid = 0`
- ✅ Menampilkan jumlah dibayar vs total
- ✅ Riwayat pembayaran dengan status

#### Halaman Riwayat (`pelanggan/rentals/index.blade.php`)
- ✅ Kolom "Pembayaran" dengan badge status
- ✅ Filter dan pencarian rental

### 4. **Interface Kasir**

#### Halaman Detail Rental (`kasir/rentals/show.blade.php`)
- ✅ Badge status pembayaran yang sama dengan pelanggan
- ✅ Menampilkan sisa pembayaran jika kurang bayar
- ✅ Info pelanggan lengkap

#### Halaman Daftar Rental (`kasir/rentals/index.blade.php`)
- ✅ Kolom "Pembayaran" dengan badge status
- ✅ Highlight rental yang menunggu konfirmasi

### 5. **Security & Configuration**
- ✅ CSRF exception untuk webhook endpoint (`bootstrap/app.php`)
- ✅ Signature verification menggunakan SHA512
- ✅ SSL verification disabled untuk local development
- ✅ Webhook URL: `POST /midtrans/notification`

---

## 🔧 Konfigurasi Midtrans Dashboard

### Langkah Setup Webhook di Midtrans:

1. **Login ke Midtrans Dashboard**
   - Sandbox: https://dashboard.sandbox.midtrans.com
   - Production: https://dashboard.midtrans.com

2. **Konfigurasi Payment Notification URL**
   - Go to: **SETTINGS** → **CONFIGURATION**
   - **Payment Notification URL**: `https://yourdomain.com/midtrans/notification`
   - **Finish Redirect URL**: `https://yourdomain.com/pelanggan/rentals/{order_id}` (opsional)
   - **Error Redirect URL**: `https://yourdomain.com/pelanggan/rentals` (opsional)
   - Click **Update**

3. **Untuk Local Development**
   - Gunakan **ngrok** atau **localtunnel** untuk expose localhost
   ```bash
   ngrok http 8000
   ```
   - Copy URL ngrok (contoh: `https://abc123.ngrok.io`)
   - Set di Midtrans: `https://abc123.ngrok.io/midtrans/notification`

---

## 🔄 Flow Pembayaran

```
1. Customer membuat rental → Status: pending, Paid: 0
                ↓
2. Customer klik bayar → Redirect ke Midtrans Snap
                ↓
3. Customer pilih metode & bayar di Midtrans
                ↓
4. Midtrans kirim webhook ke: /midtrans/notification
                ↓
5. System verifikasi signature key
                ↓
6. System update Payment & Rental:
   - Payment.transaction_status = 'settlement'
   - Payment.paid_at = now()
   - Rental.status = 'sedang_disewa'
   - Rental.paid = gross_amount
                ↓
7. Interface user & kasir otomatis tampil: ✓ LUNAS
```

---

## 📊 Status Mapping

| Midtrans Status | Rental Status | Paid Amount | Badge Display |
|----------------|---------------|-------------|---------------|
| `capture` (fraud: accept) | `sedang_disewa` | `gross_amount` | ✓ LUNAS |
| `settlement` | `sedang_disewa` | `gross_amount` | ✓ LUNAS |
| `pending` | `pending` | `0` | ✗ BELUM LUNAS |
| `deny` | `cancelled` | `0` | ✗ BELUM LUNAS |
| `expire` | `cancelled` | `0` | ✗ BELUM LUNAS |
| `cancel` | `cancelled` | `0` | ✗ BELUM LUNAS |

---

## 🧪 Testing Webhook

### 1. **Test Manual dengan CURL**
```bash
curl -X POST http://localhost:8000/midtrans/notification \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_time": "2024-01-09 18:27:19",
    "transaction_status": "settlement",
    "transaction_id": "test-123",
    "status_code": "200",
    "signature_key": "CALCULATED_HASH",
    "payment_type": "bank_transfer",
    "order_id": "RENTAL-123",
    "gross_amount": "100000.00",
    "fraud_status": "accept"
  }'
```

### 2. **Hitung Signature Key**
```php
$orderId = "RENTAL-123";
$statusCode = "200";
$grossAmount = "100000.00";
$serverKey = config('midtrans.server_key');

$signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
```

### 3. **Check Status API** (untuk debugging)
```
GET /midtrans/status/{orderId}
```

### 4. **Monitor Logs**
```bash
tail -f storage/logs/laravel.log | grep Midtrans
```

---

## 🔍 Troubleshooting

### Problem: Webhook tidak diterima
**Solution:**
1. Pastikan URL webhook accessible dari internet (gunakan ngrok untuk local)
2. Check firewall/security group
3. Pastikan endpoint return HTTP 200
4. Check logs: `storage/logs/laravel.log`

### Problem: Signature verification failed
**Solution:**
1. Pastikan `MIDTRANS_SERVER_KEY` di `.env` benar
2. Pastikan format signature: `SHA512(order_id + status_code + gross_amount + ServerKey)`
3. Check logs untuk melihat expected vs received signature

### Problem: Status tidak update
**Solution:**
1. Check apakah webhook diterima (cek logs)
2. Pastikan `rental_id` ada di tabel `payments`
3. Check database transaction tidak rollback
4. Pastikan status mapping benar

### Problem: Duplicate notifications
**Solution:**
- System sudah handle dengan `firstOrCreate()` berdasarkan `order_id`
- Idempotent: webhook yang sama tidak akan create duplicate payment

---

## 📝 Database Schema

### Table: `payments`
```sql
- id (bigint)
- rental_id (bigint) → foreign key ke rentals
- order_id (string) → unique, dari Midtrans
- transaction_id (string)
- transaction_status (string) → capture/settlement/pending/etc
- payment_type (string) → credit_card/bank_transfer/etc
- method (string) → midtrans/cash/transfer
- amount (decimal)
- gross_amount (decimal)
- fraud_status (string)
- paid_at (timestamp)
- transaction_time (timestamp)
- raw_response (json)
- created_at, updated_at
```

### Table: `rentals`
```sql
- id (bigint)
- status (string) → pending/sedang_disewa/selesai/cancelled
- paid (decimal) → jumlah yang sudah dibayar
- total (decimal) → total yang harus dibayar
- ... (fields lainnya)
```

---

## 🚀 Best Practices

1. **Always verify signature** - Jangan skip verification di production
2. **Use HTTPS** - Webhook URL harus HTTPS di production
3. **Idempotent handling** - Handle duplicate notifications gracefully
4. **Logging** - Log semua webhook untuk audit trail
5. **Error handling** - Return proper HTTP status codes
6. **Timeout** - Respond dalam 5 detik (max 15 detik)
7. **GET Status API** - Gunakan untuk verifikasi double-check

---

## 📞 Support

- **Midtrans Docs**: https://docs.midtrans.com/docs/https-notification-webhooks
- **Midtrans Support**: support@midtrans.com
- **Status Page**: https://status.midtrans.com

---

## ✨ Hasil Akhir

Setelah implementasi ini:
- ✅ Customer bayar via Midtrans → Status otomatis "LUNAS"
- ✅ Kasir bisa langsung lihat status pembayaran real-time
- ✅ Tidak perlu manual update status
- ✅ Audit trail lengkap di database
- ✅ Security terjamin dengan signature verification

**Status pembayaran akan otomatis muncul di interface user dan kasir segera setelah pembayaran berhasil!** 🎉

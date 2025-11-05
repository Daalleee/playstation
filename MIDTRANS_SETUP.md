# Setup Midtrans Payment Gateway

Panduan lengkap untuk mengintegrasikan Midtrans ke dalam aplikasi PlayStation Rental.

## 1. Mendapatkan API Keys

### Sandbox (Testing)
1. Daftar akun di [Midtrans Sandbox](https://dashboard.sandbox.midtrans.com/register)
2. Login ke dashboard
3. Buka **Settings** → **Access Keys**
4. Copy **Server Key** dan **Client Key**

### Production
1. Daftar akun di [Midtrans Production](https://dashboard.midtrans.com/register)
2. Lengkapi verifikasi bisnis
3. Login ke dashboard
4. Buka **Settings** → **Access Keys**
5. Copy **Server Key** dan **Client Key**

## 2. Konfigurasi Environment

Edit file `.env` dan tambahkan:

```env
# Midtrans Payment Gateway
MIDTRANS_SERVER_KEY=your-server-key-here
MIDTRANS_CLIENT_KEY=your-client-key-here
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Catatan:**
- Untuk testing, gunakan `MIDTRANS_IS_PRODUCTION=false`
- Untuk production, ubah menjadi `MIDTRANS_IS_PRODUCTION=true`

## 3. Jalankan Migration

```bash
php artisan migrate
```

Migration akan menambahkan kolom-kolom berikut ke tabel `payments`:
- `order_id` - ID order unik untuk tracking
- `transaction_id` - ID transaksi dari Midtrans
- `transaction_status` - Status pembayaran (pending, settlement, dll)
- `payment_type` - Metode pembayaran (credit_card, gopay, dll)
- `gross_amount` - Total pembayaran
- `transaction_time` - Waktu transaksi
- `fraud_status` - Status fraud detection
- `raw_response` - Response lengkap dari Midtrans (JSON)

## 4. Setup Webhook URL di Midtrans Dashboard

### URL Webhook
Daftarkan URL berikut di Midtrans Dashboard:

**Development/Local:**
```
https://your-ngrok-url.ngrok.io/midtrans/notification
```

**Production:**
```
https://yourdomain.com/midtrans/notification
```

### Cara Mendaftarkan Webhook:

1. Login ke [Midtrans Dashboard](https://dashboard.sandbox.midtrans.com)
2. Buka **Settings** → **Configuration**
3. Scroll ke bagian **Payment Notification URL**
4. Masukkan URL webhook Anda
5. Klik **Update**

### Testing Webhook di Local Development

Karena Midtrans perlu akses ke server Anda, gunakan **ngrok** untuk expose local server:

```bash
# Install ngrok (jika belum)
# Download dari https://ngrok.com/download

# Jalankan Laravel
php artisan serve

# Di terminal lain, jalankan ngrok
ngrok http 8000
```

Copy URL HTTPS dari ngrok (contoh: `https://abc123.ngrok.io`) dan daftarkan sebagai webhook URL.

## 5. Testing Payment Flow

### Test Cards (Sandbox)

Midtrans menyediakan test cards untuk testing:

**Sukses:**
- Card Number: `4811 1111 1111 1114`
- CVV: `123`
- Exp Date: `01/25`

**Gagal:**
- Card Number: `4911 1111 1111 1113`
- CVV: `123`
- Exp Date: `01/25`

**Pending:**
- Card Number: `4611 1111 1111 1112`
- CVV: `123`
- Exp Date: `01/25`

### Test E-Wallets

**GoPay:**
- Nomor HP: `081234567890`
- OTP: `123456`

**ShopeePay:**
- Akan redirect ke halaman simulasi

## 6. Monitoring Transaksi

### Cek Status Pembayaran Manual

Akses endpoint berikut untuk cek status:
```
GET /midtrans/status/{order_id}
```

### Log Files

Semua notifikasi dari Midtrans akan dicatat di:
```
storage/logs/laravel.log
```

Cari dengan keyword:
- `Midtrans Notification Received`
- `Midtrans notification processed`

## 7. Flow Pembayaran

1. **Customer membuat rental** → Status: `pending`
2. **Sistem generate Snap Token** → Redirect ke halaman pembayaran
3. **Customer bayar di Midtrans** → Midtrans kirim notification ke webhook
4. **Webhook update status** → Status berubah sesuai hasil pembayaran:
   - `settlement` → Rental status: `ongoing`, stock berkurang
   - `pending` → Rental status: `pending`
   - `cancel/expire/deny` → Rental status: `cancelled`, stock dikembalikan

## 8. Status Transaksi Midtrans

| Status | Deskripsi | Action |
|--------|-----------|--------|
| `pending` | Menunggu pembayaran | Rental tetap pending |
| `settlement` | Pembayaran berhasil | Rental jadi ongoing |
| `capture` | Kartu kredit berhasil | Rental jadi ongoing |
| `deny` | Pembayaran ditolak | Rental dibatalkan |
| `cancel` | Dibatalkan customer | Rental dibatalkan |
| `expire` | Pembayaran kadaluarsa | Rental dibatalkan |

## 9. Troubleshooting

### Webhook tidak dipanggil

1. Pastikan URL webhook sudah terdaftar di Midtrans Dashboard
2. Pastikan URL bisa diakses dari internet (gunakan ngrok untuk local)
3. Cek log di `storage/logs/laravel.log`

### Invalid Signature Error

1. Pastikan `MIDTRANS_SERVER_KEY` di `.env` sesuai dengan dashboard
2. Pastikan tidak ada spasi di awal/akhir server key

### Payment tidak terupdate

1. Cek apakah webhook dipanggil (lihat log)
2. Pastikan `order_id` di database sesuai dengan yang dikirim Midtrans
3. Cek response di kolom `raw_response` di tabel `payments`

### SSL Certificate Error (Local Development)

Sudah dihandle di `MidtransService.php` untuk environment `local` dan `development`.

## 10. Security Checklist

- ✅ Webhook route di-exclude dari CSRF protection
- ✅ Signature validation di webhook handler
- ✅ Server key tidak di-commit ke git (ada di `.gitignore`)
- ✅ Transaction logging untuk audit trail
- ⚠️ (Optional) IP Whitelist untuk webhook

## 11. Production Checklist

Sebelum go-live:

- [ ] Ganti `MIDTRANS_IS_PRODUCTION=true`
- [ ] Gunakan Production Server Key & Client Key
- [ ] Update webhook URL ke domain production
- [ ] Test semua payment methods
- [ ] Setup monitoring & alerting
- [ ] Backup database sebelum migration
- [ ] Test rollback scenario

## Support

- Dokumentasi: https://docs.midtrans.com
- Support: support@midtrans.com
- Status: https://status.midtrans.com

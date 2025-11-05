# 🚀 Setup Midtrans Payment Gateway - Panduan Lengkap

## 📋 Checklist Persiapan

- [ ] Akun Midtrans Sandbox
- [ ] Server Key & Client Key
- [ ] Ngrok (untuk testing lokal)
- [ ] Database sudah di-migrate

## 1️⃣ Daftar Akun Midtrans Sandbox

### Langkah 1: Registrasi
1. Buka https://dashboard.sandbox.midtrans.com/register
2. Isi form registrasi:
   - Email
   - Password
   - Nama Bisnis: **PlayStation Rental**
   - Kategori: **Rental/Leasing**
3. Verifikasi email
4. Login ke dashboard

### Langkah 2: Dapatkan API Keys
1. Login ke https://dashboard.sandbox.midtrans.com
2. Klik menu **Settings** → **Access Keys**
3. Copy kedua keys:
   - **Server Key**: `SB-Mid-server-xxxxxxxxxx`
   - **Client Key**: `SB-Mid-client-xxxxxxxxxx`

## 2️⃣ Konfigurasi Aplikasi

### Edit File `.env`

Buka file `.env` dan tambahkan/update:

```env
# Midtrans Payment Gateway
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**⚠️ PENTING:** Ganti `xxxxxxxxxx` dengan API keys Anda yang sebenarnya!

### Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 3️⃣ Setup Webhook dengan Ngrok

### Install Ngrok

1. Download dari https://ngrok.com/download
2. Extract dan jalankan

### Jalankan Aplikasi

```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Ngrok
ngrok http 8000
```

### Daftarkan Webhook URL

1. Copy URL HTTPS dari ngrok (contoh: `https://abc123.ngrok.io`)
2. Login ke Midtrans Dashboard
3. **Settings** → **Configuration**
4. **Payment Notification URL**: `https://abc123.ngrok.io/midtrans/notification`
5. **Finish Redirect URL**: `https://abc123.ngrok.io/pelanggan/rentals`
6. **Unfinish Redirect URL**: `https://abc123.ngrok.io/pelanggan/cart`
7. **Error Redirect URL**: `https://abc123.ngrok.io/pelanggan/cart`
8. Klik **Update**

## 4️⃣ Testing Payment Flow

### Test dengan Credit Card

**Card Number:** `4811 1111 1111 1114`  
**CVV:** `123`  
**Exp Date:** `01/25`  
**OTP/3DS:** `112233`

### Test dengan GoPay

**Phone:** `081234567890`  
**OTP:** `123456`

### Test dengan Bank Transfer

Pilih bank apapun, akan langsung muncul VA number untuk testing.

## 5️⃣ Flow Pembayaran Lengkap

### User Flow:

1. **Browse & Add to Cart**
   - User browse game/aksesoris
   - Klik "Tambah ke Keranjang"
   - Item masuk cart

2. **Checkout**
   - User klik "Buat Penyewaan"
   - Isi tanggal mulai & tanggal kembali
   - Klik "Buat Penyewaan"

3. **Payment Page**
   - Redirect ke halaman pembayaran
   - Popup Midtrans muncul otomatis (1 detik)
   - User pilih metode pembayaran
   - User selesaikan pembayaran

4. **Payment Success**
   - Midtrans kirim notification ke webhook
   - Status rental berubah: `pending` → `ongoing`
   - User redirect ke detail rental
   - Pembayaran tercatat di database

### System Flow:

```
User Submit Form
    ↓
Create Rental (status: pending)
    ↓
Create Payment Record (status: pending)
    ↓
Generate Snap Token
    ↓
Show Payment Page
    ↓
User Pay
    ↓
Midtrans Send Webhook
    ↓
Update Payment Status
    ↓
Update Rental Status (ongoing)
    ↓
Done ✅
```

## 6️⃣ Monitoring & Debugging

### Cek Transaction di Midtrans Dashboard

1. Login ke https://dashboard.sandbox.midtrans.com
2. Menu **Transactions**
3. Lihat semua transaksi yang masuk
4. Klik detail untuk melihat status

### Cek Log Laravel

```bash
# Windows PowerShell
Get-Content storage\logs\laravel.log -Tail 50

# Cari keyword:
# - "Rental created"
# - "Midtrans Notification"
# - "Rental PAID"
# - "Rental PENDING"
```

### Cek Database

```sql
-- Cek rental
SELECT id, kode, status, total, paid, created_at 
FROM rentals 
ORDER BY created_at DESC 
LIMIT 10;

-- Cek payment
SELECT id, rental_id, order_id, transaction_status, amount, created_at 
FROM payments 
ORDER BY created_at DESC 
LIMIT 10;
```

## 7️⃣ Status Mapping

| Midtrans Status | Rental Status | Deskripsi |
|-----------------|---------------|-----------|
| `pending` | `pending` | Menunggu pembayaran |
| `settlement` | `ongoing` | Pembayaran berhasil (e-wallet, bank) |
| `capture` | `ongoing` | Pembayaran berhasil (credit card) |
| `deny` | `cancelled` | Pembayaran ditolak |
| `cancel` | `cancelled` | Dibatalkan user |
| `expire` | `expired` | Pembayaran kadaluarsa |

## 8️⃣ Troubleshooting

### ❌ Error: "Sistem pembayaran belum dikonfigurasi"

**Penyebab:** API keys belum diisi di `.env`

**Solusi:**
1. Pastikan `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` sudah terisi
2. Jalankan `php artisan config:clear`
3. Restart server

### ❌ Webhook tidak dipanggil

**Penyebab:** URL webhook salah atau ngrok mati

**Solusi:**
1. Pastikan ngrok masih running
2. Cek URL webhook di Midtrans Dashboard
3. Test webhook manual: `POST https://your-ngrok-url.ngrok.io/midtrans/notification`

### ❌ Payment popup tidak muncul

**Penyebab:** Client key salah atau snap.js tidak load

**Solusi:**
1. Cek browser console (F12)
2. Pastikan `MIDTRANS_CLIENT_KEY` benar
3. Cek koneksi internet

### ❌ Status tidak update setelah bayar

**Penyebab:** Webhook tidak terkirim atau error

**Solusi:**
1. Cek log Laravel: `storage/logs/laravel.log`
2. Cek Midtrans Dashboard → Transactions → Notification History
3. Test manual webhook

## 9️⃣ Production Checklist

Sebelum deploy ke production:

- [ ] Ganti ke Production API Keys
- [ ] Set `MIDTRANS_IS_PRODUCTION=true`
- [ ] Update webhook URL ke domain production
- [ ] Test semua payment methods
- [ ] Setup monitoring & alerting
- [ ] Backup database
- [ ] Test rollback scenario
- [ ] Enable HTTPS
- [ ] Setup proper logging

## 🎯 Quick Test Checklist

- [ ] Login sebagai pelanggan
- [ ] Tambah game ke cart
- [ ] Klik "Buat Penyewaan"
- [ ] Isi form dan submit
- [ ] Halaman payment muncul
- [ ] Popup Midtrans muncul
- [ ] Bayar dengan test card
- [ ] Redirect ke detail rental
- [ ] Status rental = "ongoing"
- [ ] Payment tercatat di database
- [ ] Transaction muncul di Midtrans Dashboard

## 📞 Support

- **Midtrans Docs:** https://docs.midtrans.com
- **Midtrans Support:** support@midtrans.com
- **Status Page:** https://status.midtrans.com

---

**Last Updated:** 29 Oktober 2025  
**Version:** 1.0  
**Status:** ✅ Ready for Testing

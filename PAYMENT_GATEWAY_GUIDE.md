# Payment Gateway - Panduan Lengkap

## Fitur Halaman Pembayaran

### 1. **Desain Konsisten**
- Menggunakan dark theme yang sama dengan halaman lainnya
- Sidebar navigasi tetap tersedia
- Responsive design untuk mobile dan desktop

### 2. **Informasi Lengkap**
Halaman pembayaran menampilkan:
- **Order ID**: ID transaksi Midtrans
- **Kode Rental**: Kode unik penyewaan
- **Status**: Status pembayaran saat ini
- **Detail Penyewaan**: Tanggal mulai, tanggal kembali, dan durasi
- **Ringkasan Pesanan**: Daftar item yang disewa dengan harga
- **Total Pembayaran**: Total yang harus dibayar (highlight warna hijau)

### 3. **Instruksi Pembayaran**
Panduan step-by-step untuk user:
1. Klik tombol "Lanjutkan Pembayaran"
2. Pilih metode pembayaran (Transfer Bank, E-Wallet, Kartu Kredit, dll)
3. Ikuti instruksi pembayaran
4. Selesaikan pembayaran sebelum batas waktu
5. Status otomatis diperbarui setelah pembayaran berhasil

### 4. **Integrasi Midtrans**
- Auto-trigger popup pembayaran setelah 1.5 detik
- Support multiple payment methods
- Secure payment dengan enkripsi
- Real-time payment status update

### 5. **User Experience**
- **Tombol Pembayaran**: Hijau dengan icon kartu kredit
- **Loading State**: Menampilkan "Memproses Pembayaran..." saat loading
- **Success Notification**: Popup hijau dengan icon check
- **Pending Notification**: Popup orange dengan icon clock
- **Error Notification**: Popup merah dengan icon X
- **Cancel Option**: Tombol kembali ke daftar penyewaan

## Flow Pembayaran

```
User submit form penyewaan
    ↓
RentalController@store
    ↓
Create rental & rental items
    ↓
Reduce stock (in transaction)
    ↓
Create Midtrans snap token
    ↓
Commit transaction
    ↓
Clear cart
    ↓
Redirect to payment page ← YOU ARE HERE
    ↓
Auto-trigger Midtrans popup (1.5s)
    ↓
User choose payment method
    ↓
User complete payment
    ↓
Midtrans callback
    ↓
Update payment status
    ↓
Redirect to rental detail page
```

## Metode Pembayaran yang Tersedia

### 1. **Transfer Bank**
- BCA Virtual Account
- Mandiri Virtual Account
- BNI Virtual Account
- BRI Virtual Account
- Permata Virtual Account
- CIMB Niaga Virtual Account

### 2. **E-Wallet**
- GoPay
- ShopeePay
- QRIS (All E-Wallets)

### 3. **Kartu Kredit/Debit**
- Visa
- Mastercard
- JCB
- Amex

### 4. **Convenience Store**
- Indomaret
- Alfamart

### 5. **Cicilan**
- Kredivo
- Akulaku

## Status Pembayaran

### 1. **Pending** (Menunggu Pembayaran)
- Status awal setelah rental dibuat
- User belum menyelesaikan pembayaran
- Warna: Orange (#f39c12)

### 2. **Success** (Berhasil)
- Pembayaran berhasil diverifikasi
- Status rental diupdate menjadi "active"
- Warna: Hijau (#2ecc71)

### 3. **Failed** (Gagal)
- Pembayaran gagal atau ditolak
- User bisa retry pembayaran
- Warna: Merah (#e74c3c)

### 4. **Expired** (Kadaluarsa)
- Batas waktu pembayaran habis
- Rental dibatalkan otomatis
- Stok dikembalikan
- Warna: Abu-abu (#6c757d)

## Keamanan

### 1. **Enkripsi**
- Semua data pembayaran dienkripsi dengan SSL/TLS
- Midtrans PCI-DSS Level 1 certified

### 2. **Validasi**
- Server-side validation untuk semua input
- CSRF protection
- XSS prevention

### 3. **Privacy**
- Data kartu kredit tidak disimpan di server
- Hanya Midtrans yang memproses data sensitif

## Testing

### Sandbox Mode (Development)
Gunakan test credentials dari Midtrans:

**Test Credit Card:**
- Card Number: `4811 1111 1111 1114`
- CVV: `123`
- Exp Date: `01/25`

**Test E-Wallet:**
- GoPay: Akan muncul simulator
- ShopeePay: Akan muncul simulator

**Test Virtual Account:**
- Semua bank akan generate VA number
- Gunakan simulator untuk approve payment

### Production Mode
- Gunakan real payment methods
- Real money transaction
- Set `MIDTRANS_IS_PRODUCTION=true` di `.env`

## Troubleshooting

### 1. Popup Tidak Muncul
**Penyebab:**
- Snap.js tidak loaded
- Client key salah
- Browser block popup

**Solusi:**
- Check console untuk error
- Verify client key di `.env`
- Allow popup di browser settings

### 2. Payment Failed
**Penyebab:**
- Insufficient balance
- Card declined
- Network error

**Solusi:**
- Check payment method balance
- Try different payment method
- Check internet connection

### 3. Status Tidak Update
**Penyebab:**
- Webhook tidak configured
- Callback URL salah
- Server error

**Solusi:**
- Check webhook settings di Midtrans dashboard
- Verify callback URL
- Check server logs

## Kustomisasi

### Mengubah Warna Tema
Edit di `resources/views/pelanggan/payment/midtrans.blade.php`:

```css
.btn-pay{ background:#2ecc71; } /* Hijau */
.order-row.total{ color:#2ecc71; } /* Total hijau */
```

### Mengubah Auto-trigger Delay
Edit di bagian JavaScript:

```javascript
// Auto-trigger payment popup after 1.5 seconds
setTimeout(function() {
  payButton.click();
}, 1500); // Ubah nilai ini (dalam milliseconds)
```

### Menambah Informasi
Edit section `rental-details` atau `order-summary` untuk menambah informasi tambahan.

## Best Practices

1. **Selalu gunakan HTTPS** di production
2. **Set timeout** untuk pending payments (24 jam)
3. **Send email notification** setelah payment success
4. **Log semua transactions** untuk audit trail
5. **Handle webhook properly** untuk auto-update status
6. **Test thoroughly** di sandbox sebelum production
7. **Monitor payment success rate** dan optimize flow

## Support

Jika ada masalah dengan payment gateway:
1. Check Midtrans dashboard untuk transaction logs
2. Check Laravel logs di `storage/logs/laravel.log`
3. Contact Midtrans support: support@midtrans.com
4. Dokumentasi: https://docs.midtrans.com

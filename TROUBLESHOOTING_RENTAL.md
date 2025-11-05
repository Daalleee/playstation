# Troubleshooting: Masalah "Keranjang Kosong" Setelah Checkout

## 🔍 Masalah

Setelah klik "Buat Penyewaan", user melihat halaman "Keranjang kosong" dengan pesan sukses di atas, padahal seharusnya redirect ke halaman detail rental.

## 🎯 Kemungkinan Penyebab

### 1. **Browser Cache**
Browser masih menyimpan halaman cart lama.

**Solusi:**
- Tekan `Ctrl + Shift + R` untuk hard refresh
- Atau buka di Incognito/Private window
- Clear browser cache

### 2. **Session/Cookie Issue**
Session flash message tidak terbawa ke halaman berikutnya.

**Solusi:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. **JavaScript Redirect**
Ada JavaScript yang mengoverride redirect dari server.

**Cek:**
- Buka Developer Tools (F12)
- Tab Console - lihat ada error JavaScript?
- Tab Network - lihat response dari POST `/pelanggan/rentals`

## ✅ Langkah Testing yang Benar

1. **Clear semua cache:**
```bash
php artisan optimize:clear
```

2. **Restart server:**
```bash
# Stop server (Ctrl+C)
php artisan serve
```

3. **Buka browser baru (Incognito):**
- Chrome: `Ctrl + Shift + N`
- Firefox: `Ctrl + Shift + P`

4. **Login dan test:**
- Login sebagai pelanggan@gmail.com / password
- Tambah item ke keranjang
- Klik "Buat Penyewaan"
- Isi form
- Submit

5. **Yang seharusnya terjadi:**
- ✅ Redirect ke `/pelanggan/rentals/{id}`
- ✅ Muncul pesan sukses
- ✅ Menampilkan detail rental
- ✅ Keranjang kosong (ini normal karena sudah checkout)

## 🐛 Debug Steps

### 1. Cek Response di Network Tab

Buka Developer Tools → Network → Submit form → Lihat response:

**Jika response 302 (Redirect):**
- ✅ Server sudah benar redirect
- ❌ Browser tidak mengikuti redirect
- **Solusi:** Clear cache browser

**Jika response 200 (OK):**
- ❌ Server tidak redirect
- **Cek:** Ada error di controller?
- **Lihat:** Tab Console untuk error JavaScript

**Jika response 500 (Error):**
- ❌ Ada error di server
- **Cek:** `storage/logs/laravel.log`

### 2. Cek Log Laravel

```bash
# Windows PowerShell
Get-Content storage\logs\laravel.log -Tail 50

# Atau buka file langsung
storage/logs/laravel.log
```

Cari error dengan keyword:
- "Error creating rental"
- "SQLSTATE"
- "Exception"

### 3. Test Manual Redirect

Setelah buat rental, coba akses manual:
```
http://127.0.0.1:8000/pelanggan/rentals
```

Jika rental muncul di list → Berarti rental berhasil dibuat, masalahnya di redirect.

## 🔧 Solusi Alternatif

Jika masalah persist, tambahkan JavaScript redirect manual di view:

```javascript
// Di resources/views/pelanggan/rentals/create.blade.php
document.querySelector('form').addEventListener('submit', function(e) {
    // Tampilkan loading
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Memproses...';
});
```

## 📞 Jika Masih Error

Kirim screenshot dari:
1. Developer Tools → Console tab
2. Developer Tools → Network tab (saat submit form)
3. Error message yang muncul

---

**Last Updated:** 29 Oktober 2025

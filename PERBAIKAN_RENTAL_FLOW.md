# Perbaikan Rental Flow

## Masalah yang Diperbaiki

### 1. Error "Undefined array key 10023"
**Penyebab:**
- Error terjadi di Midtrans SDK (`ApiRequestor.php:117`)
- `Config::$curlOptions[CURLOPT_HTTPHEADER]` tidak diinisialisasi dengan benar

**Solusi:**
- Memperbaiki `MidtransService.php` untuk menginisialisasi `curlOptions` dan `CURLOPT_HTTPHEADER` sebagai array kosong sebelum digunakan

### 2. Data Hilang Setelah Klik "Sewa"
**Penyebab:**
- Form submit tidak mengirim `type` dan `id` item yang akan disewa
- Controller `store()` hanya membaca dari query parameters, bukan request body
- Cart kosong karena direct rental tidak menyimpan ke database

**Solusi:**
- Menambahkan hidden inputs di form untuk `type`, `id`, dan `quantity`
- Mengubah `store()` method untuk membaca dari `request->input()` (prioritas) dengan fallback ke `request->query()`

### 3. Semua Data Hilang Setelah 1 Transaksi
**Penyebab:**
- Cart dibersihkan SEBELUM Midtrans token berhasil dibuat
- Jika terjadi error di Midtrans, cart sudah terhapus tapi transaksi gagal
- User tidak bisa retry karena data sudah hilang

**Solusi:**
- Memindahkan pembersihan cart ke SETELAH:
  - Rental berhasil dibuat
  - Midtrans token berhasil dibuat
  - Database transaction berhasil di-commit
- Jika terjadi error, cart tetap ada dan stok otomatis di-rollback

## Flow Penyewaan yang Benar

### Direct Rental (dari tombol "Sewa Unit/Game/Accessory")

1. **User klik tombol "Sewa"**
   - URL: `/pelanggan/rentals/create?type=game&id=123`
   - Controller: `RentalController@create`

2. **Halaman Create Rental**
   - Controller membuat temporary array (TIDAK disimpan ke DB)
   - View menampilkan item yang akan disewa
   - Form memiliki hidden inputs:
     ```html
     <input type="hidden" name="type" value="game">
     <input type="hidden" name="id" value="123">
     <input type="hidden" name="quantity" value="1">
     ```

3. **User submit form**
   - POST ke `/pelanggan/rentals/store`
   - Hidden inputs dikirim dalam request body

4. **Controller Store**
   - Validasi input
   - Baca `type` dan `id` dari `request->input()` (hidden inputs)
   - Cek cart di database (kosong untuk direct rental)
   - Buat temporary cart item di database
   - Mulai DB transaction
   - Buat rental record
   - Buat rental items
   - Kurangi stok (dalam transaction)
   - Update rental total
   - Buat Midtrans params
   - **Panggil Midtrans API untuk create snap token**
   - Jika berhasil:
     - Commit transaction
     - **Bersihkan cart** (setelah sukses)
     - Redirect ke halaman payment
   - Jika gagal:
     - Rollback transaction (stok dikembalikan otomatis)
     - Cart tetap ada
     - User bisa retry

### Cart Rental (dari keranjang)

1. **User tambah item ke cart**
   - Item disimpan di database `carts` table

2. **User klik "Buat Penyewaan" dari cart**
   - URL: `/pelanggan/rentals/create`
   - Cart sudah ada di database

3. **Flow sama seperti direct rental**
   - Tapi skip pembuatan temporary cart karena sudah ada

## Testing

### Test Direct Rental
1. Akses dashboard pelanggan
2. Klik tombol "Sewa Game" pada salah satu game
3. Isi tanggal mulai dan tanggal kembali
4. Klik "Buat Penyewaan"
5. Seharusnya redirect ke halaman Midtrans payment

### Test Error Handling
1. Matikan koneksi internet atau gunakan invalid Midtrans credentials
2. Coba buat penyewaan
3. Seharusnya muncul error tapi cart tidak hilang
4. User bisa retry dengan data yang sama

### Test Cart Rental
1. Tambah beberapa item ke cart
2. Klik "Buat Penyewaan" dari halaman cart
3. Isi form dan submit
4. Seharusnya berhasil dan cart dibersihkan

## File yang Diubah

1. **app/Services/MidtransService.php**
   - Inisialisasi `Config::$curlOptions` dan `CURLOPT_HTTPHEADER`

2. **app/Http/Controllers/Pelanggan/RentalController.php**
   - Method `create()`: Tambah key `item_id` dan `stok` pada array
   - Method `store()`: Baca dari `request->input()` dengan fallback ke `request->query()`
   - Method `store()`: Pindahkan pembersihan cart ke setelah Midtrans sukses

3. **resources/views/pelanggan/rentals/create.blade.php**
   - Tambah hidden inputs untuk `type`, `id`, dan `quantity`
   - Hapus fallback ke `$_GET['id']` yang tidak aman

## Catatan Penting

- **JANGAN** hapus cart sebelum transaksi selesai
- **SELALU** gunakan DB transaction untuk operasi yang melibatkan multiple tables
- **PASTIKAN** stok dikurangi dalam transaction agar bisa di-rollback jika error
- **GUNAKAN** hidden inputs untuk preserve data antar request, bukan query parameters
- **LOG** error dengan detail untuk debugging, tapi jangan log data sensitif

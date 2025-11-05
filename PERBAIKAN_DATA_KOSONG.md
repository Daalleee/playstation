# Perbaikan: Data Kosong di Halaman Unit PS

## Masalah
Halaman "Sewa Unit PlayStation" menampilkan pesan "Tidak ada unit PlayStation tersedia saat ini" padahal data sudah ada di database.

## Root Cause
**Nama kolom tidak konsisten** antara controller dan database:

### Controller (SALAH):
```php
$query = UnitPS::where('stok', '>', 0);  // ❌ Kolom 'stok' tidak ada
$query->where('merek', $request->brand); // ❌ Kolom 'merek' tidak ada
$q->where('nama', 'like', '%' . $request->q . '%'); // ❌ Kolom 'nama' tidak ada
```

### Database (BENAR):
```php
// Tabel: unit_ps
- stock (bukan stok)
- brand (bukan merek)
- name (bukan nama)
```

## Solusi
Memperbaiki `UnitPSController.php`:

```php
// ✅ BENAR
$query = UnitPS::where('stock', '>', 0);
$query->where('brand', $request->brand);
$q->where('name', 'like', '%' . $request->q . '%');
```

## Perbedaan Nama Kolom

### Tabel `unit_ps`:
- `name` (nama unit)
- `brand` (merek)
- `stock` (stok)
- `price_per_hour` (harga per jam)

### Tabel `games`:
- `judul` (nama game)
- `platform` (platform)
- `stok` (stok)
- `harga_per_hari` (harga per hari)

### Tabel `accessories`:
- `nama` (nama aksesoris)
- `jenis` (jenis)
- `stok` (stok)
- `harga_per_hari` (harga per hari)

## Catatan Penting
⚠️ **Konsistensi Penamaan Kolom**

Sistem ini menggunakan 2 konvensi berbeda:
1. **Unit PS**: Bahasa Inggris (`name`, `brand`, `stock`)
2. **Games & Accessories**: Bahasa Indonesia (`judul`/`nama`, `stok`)

Pastikan selalu menggunakan nama kolom yang sesuai dengan tabel saat query database.

## Testing
Setelah perbaikan:
1. Refresh halaman `/pelanggan/unitps`
2. Data Unit PS seharusnya muncul
3. Filter dan search berfungsi dengan benar

## File yang Diperbaiki
- `app/Http/Controllers/Pelanggan/UnitPSController.php`

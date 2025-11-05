# Perbaikan: Dashboard Pelanggan Menampilkan Unit PS

## Masalah
Dashboard pelanggan menampilkan "Tidak ada unit PlayStation tersedia saat ini" padahal data ada di database.

## Root Cause
**Nama kolom tidak konsisten** antara controller/view dan database:

### Controller & View (SALAH):
```php
// Controller
$unitps = UnitPS::where('stok', '>', 0)  // ❌ Kolom 'stok' tidak ada

// View
{{ $unit->nama }}           // ❌ Kolom 'nama' tidak ada
{{ $unit->harga_per_jam }}  // ❌ Kolom 'harga_per_jam' tidak ada
{{ $unit->stok }}           // ❌ Kolom 'stok' tidak ada
```

### Database (BENAR):
```php
// Tabel: unit_ps
- name (bukan nama)
- brand (bukan merek)
- model
- stock (bukan stok)
- price_per_hour (bukan harga_per_jam)
```

## Solusi

### 1. DashboardController.php
Memperbaiki query untuk menggunakan kolom yang benar:

```php
// ✅ BENAR
$unitps = UnitPS::where('stock', '>', 0)
    ->orderByDesc('id')
    ->limit(8)
    ->get();
```

### 2. dashboards/pelanggan.blade.php
Memperbaiki field yang digunakan di view:

```php
// ✅ BENAR
<h5>{{ $unit->name }}</h5>
<div>{{ $unit->model }} - {{ $unit->brand }}</div>
<div>Rp {{ number_format($unit->price_per_hour, 0, ',', '.') }}/jam</div>
<div>{{ $unit->stock }} Unit</div>
```

### 3. Menambahkan Badge Stok
Menampilkan badge berwarna berdasarkan jumlah stok:
- **Hijau**: Stok > 5 (Banyak)
- **Orange**: Stok 1-5 (Terbatas)
- **Merah**: Stok 0 (Habis)

```php
@php 
  $stok = $unit->stock ?? 0;
  $badgeClass = $stok > 5 ? 'badge-success' : ($stok > 0 ? 'badge-warning' : 'badge-danger');
@endphp
<span class="{{ $badgeClass }} px-3 py-1 rounded-pill">{{ $stok }} Unit</span>
```

## Fitur Dashboard Pelanggan

### 1. **Hero Section**
- Judul: "Selamat Datang di Rental PS"
- Subtitle: Deskripsi singkat tentang layanan

### 2. **Unit PlayStation Section**
- Menampilkan 8 unit PS terbaru dengan stok > 0
- Card dengan:
  - Gambar placeholder (PS3/PS4/PS5)
  - Nama unit (PS4 Slim, PS5 Standard, dll)
  - Model & Brand (PS4 - Sony)
  - Harga per jam
  - Badge stok berwarna
  - Tombol "Sewa Unit"
- Link "Lihat Semua" ke halaman daftar lengkap

### 3. **Games Section**
- Menampilkan 8 game terbaru dengan stok > 0
- Card dengan informasi game
- Tombol "Sewa Game"

### 4. **Accessories Section**
- Menampilkan 8 aksesoris terbaru dengan stok > 0
- Card dengan informasi aksesoris
- Tombol "Sewa Aksesoris"

## Layout

### Desktop (> 992px)
```
┌─────────────────────────────────────────┐
│  Sidebar  │  Main Content               │
│           │  ┌─────────────────────┐    │
│  - Beranda│  │ Hero Section        │    │
│  - Profil │  └─────────────────────┘    │
│  - Sewa PS│  ┌─────────────────────┐    │
│  - Games  │  │ Unit PS (4 cols)    │    │
│  - Aksesor│  │ [Card] [Card] ...   │    │
│  - Keranja│  └─────────────────────┘    │
│  - Riwayat│  ┌─────────────────────┐    │
│  - Logout │  │ Games (4 cols)      │    │
│           │  └─────────────────────┘    │
└─────────────────────────────────────────┘
```

### Mobile (< 992px)
```
┌─────────────────┐
│ Sidebar (Stack) │
├─────────────────┤
│ Hero Section    │
├─────────────────┤
│ Unit PS         │
│ [Card]          │
│ [Card]          │
├─────────────────┤
│ Games           │
│ [Card]          │
└─────────────────┘
```

## Warna & Styling

### Dark Theme
- Background: `#2b3156` (Dark Purple)
- Sidebar: `#3a2a70` (Purple)
- Card: `#49497A` (Light Purple)
- Text: `#e7e9ff` (Light)
- Price: `#7bed9f` (Light Green)

### Badges
- Success: `#2ecc71` (Green) - Stok > 5
- Warning: `#f39c12` (Orange) - Stok 1-5
- Danger: `#e74c3c` (Red) - Stok 0

### Buttons
- Primary (CTA): `#4750c9` (Blue)
- Hover: `#5a63e0` (Light Blue)
- Active: `#3a43a8` (Dark Blue)

## Testing

### 1. Verifikasi Data
```bash
php artisan tinker
>>> \App\Models\UnitPS::where('stock', '>', 0)->count()
# Should return: 5 (atau jumlah unit yang ada)
```

### 2. Test Dashboard
1. Login sebagai pelanggan
2. Akses `/dashboard/pelanggan` atau klik "Beranda"
3. Seharusnya muncul:
   - 8 Unit PS (atau semua yang tersedia jika < 8)
   - 8 Games
   - 8 Accessories
4. Klik "Sewa Unit" → redirect ke halaman create rental

### 3. Test Responsive
- Desktop: 4 cards per row
- Tablet: 2 cards per row
- Mobile: 1 card per row

## File yang Diperbaiki

1. **app/Http/Controllers/DashboardController.php**
   - Method `pelanggan()`: Query UnitPS menggunakan `stock`
   - Method `unitpsLanding()`: Query UnitPS menggunakan `stock`

2. **resources/views/dashboards/pelanggan.blade.php**
   - Unit PS section: Menggunakan `name`, `model`, `brand`, `price_per_hour`, `stock`
   - Menambahkan badge stok berwarna
   - Menambahkan info model & brand

## Routes

```php
// Dashboard pelanggan
Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])
    ->name('dashboard.pelanggan');

// Unit PS landing
Route::get('/pelanggan/unitps', [DashboardController::class, 'unitpsLanding'])
    ->name('pelanggan.unitps.landing');

// Unit PS list (dengan filter)
Route::get('/pelanggan/unitps/list', [UnitPSController::class, 'index'])
    ->name('pelanggan.unitps.index');
```

## Catatan Penting

⚠️ **Konsistensi Penamaan**
Sistem menggunakan 2 konvensi berbeda:
- **Unit PS**: English (`name`, `brand`, `stock`, `price_per_hour`)
- **Games & Accessories**: Indonesian (`judul`, `nama`, `stok`, `harga_per_hari`)

Pastikan selalu gunakan nama kolom yang sesuai dengan tabel!

⚠️ **Placeholder Images**
Saat ini menggunakan placehold.co untuk gambar. Untuk production:
1. Upload gambar unit PS ke storage
2. Update seeder untuk include path gambar
3. Update view untuk gunakan gambar asli

## Next Steps

1. ✅ Upload gambar unit PS yang sebenarnya
2. ✅ Implementasi fitur filter di halaman list
3. ✅ Implementasi fitur search
4. ✅ Implementasi pagination
5. ✅ Add to cart functionality
6. ✅ Rental creation flow

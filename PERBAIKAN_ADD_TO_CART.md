# Perbaikan: Error "Tambah ke Keranjang"

## Masalah
Ketika user klik tombol "Tambah ke Keranjang" di halaman Unit PS, muncul error "Terjadi kesalahan saat menambahkan ke keranjang".

## Root Cause

### 1. **Content-Type Tidak Sesuai**
JavaScript mengirim request dengan `Content-Type: application/json`:
```javascript
headers: {
  'Content-Type': 'application/json',
  ...
},
body: JSON.stringify({...})
```

Tapi Laravel controller expect `application/x-www-form-urlencoded` atau `multipart/form-data` untuk request validation.

### 2. **CSRF Token Handling**
CSRF token diambil dengan fallback yang kompleks dan bisa gagal:
```javascript
document.querySelector('meta[name="csrf-token"]') ? 
  document.querySelector('meta[name="csrf-token"]').getAttribute('content') : 
  document.querySelector('input[name="_token"]').value
```

### 3. **Error Handling Kurang Detail**
Catch block hanya menampilkan pesan generic tanpa detail error dari server.

## Solusi

### 1. **Gunakan FormData**
Mengubah dari JSON ke FormData:
```javascript
// ✅ BENAR - Gunakan FormData
const formData = new FormData();
formData.append('type', type);
formData.append('id', id);
formData.append('quantity', quantity);
formData.append('price_type', price_type);

fetch('/pelanggan/cart/add', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': csrfToken,
    'Accept': 'application/json',
  },
  body: formData  // FormData, bukan JSON
})
```

### 2. **Simplified CSRF Token**
```javascript
// ✅ BENAR - Simple dan aman
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (!csrfToken) {
  showFlashMessage('CSRF token tidak ditemukan. Silakan refresh halaman.', 'danger');
  return;
}
```

### 3. **Better Error Handling**
```javascript
.then(response => {
  if (!response.ok) {
    return response.json().then(err => Promise.reject(err));
  }
  return response.json();
})
.catch(error => {
  console.error('Error:', error);
  const errorMessage = error.message || error.error || 'Terjadi kesalahan saat menambahkan ke keranjang';
  showFlashMessage(errorMessage, 'danger');
})
```

## Validasi di Controller

Controller sudah memiliki validasi yang baik:

```php
$request->validate([
    'type' => 'required|in:unitps,game,accessory',
    'id' => 'required|integer',
    'quantity' => 'required|integer|min:1',
    'price_type' => 'required|in:per_jam,per_hari'
]);
```

Dan mengembalikan JSON response:
```php
if($request->wantsJson()) {
    return response()->json([
        'success' => true,
        'message' => 'Item berhasil ditambahkan ke keranjang!'
    ]);
}
```

## Flow Lengkap

```
User klik "Tambah ke Keranjang"
    ↓
JavaScript validate quantity (client-side)
    ↓
Get CSRF token dari meta tag
    ↓
Create FormData dengan: type, id, quantity, price_type
    ↓
POST ke /pelanggan/cart/add
    ↓
Laravel validate request (server-side)
    ↓
Check item exists & stock available
    ↓
Add to cart atau update quantity
    ↓
Return JSON response
    ↓
JavaScript show success/error message
    ↓
Reset quantity input to 1
```

## Testing

### Manual Testing
1. Login sebagai pelanggan
2. Akses halaman `/pelanggan/unitps`
3. Pilih quantity (1-5)
4. Klik "Tambah ke Keranjang"
5. Seharusnya muncul notifikasi sukses hijau
6. Quantity input reset ke 1

### Browser Console Testing
```javascript
// Test CSRF token
console.log(document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
// Should output: token string

// Test fetch
const formData = new FormData();
formData.append('type', 'unitps');
formData.append('id', '1');
formData.append('quantity', '1');
formData.append('price_type', 'per_jam');

fetch('/pelanggan/cart/add', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'Accept': 'application/json',
  },
  body: formData
})
.then(r => r.json())
.then(d => console.log(d));
```

## Error Messages

### Client-Side Validation
- ❌ "Jumlah tidak valid!" - Quantity < 1 atau > stok
- ❌ "CSRF token tidak ditemukan. Silakan refresh halaman." - Token missing

### Server-Side Validation
- ❌ "Tipe item tidak valid!" - Type bukan unitps/game/accessory
- ❌ "Item tidak ditemukan!" - ID tidak ada di database
- ❌ "Stok tidak mencukupi!" - Quantity > available stock
- ❌ "Jumlah melebihi stok yang tersedia!" - Update cart melebihi stok

### Success
- ✅ "Item berhasil ditambahkan ke keranjang!"

## File yang Diperbaiki

1. **resources/views/pelanggan/unitps/index.blade.php**
   - Mengubah dari JSON ke FormData
   - Simplified CSRF token handling
   - Better error handling

## Catatan Penting

⚠️ **CSRF Token**
- Token ada di `<meta name="csrf-token">` di `layouts/app.blade.php`
- Harus dikirim di header `X-CSRF-TOKEN`
- Jika token tidak valid, Laravel return 419 error

⚠️ **Content-Type**
- Jangan set `Content-Type` manual saat gunakan FormData
- Browser akan auto-set ke `multipart/form-data` dengan boundary

⚠️ **Accept Header**
- Set `Accept: application/json` agar Laravel return JSON response
- Tanpa ini, Laravel bisa return HTML redirect

## Best Practices

1. ✅ Gunakan FormData untuk POST request
2. ✅ Validate di client-side DAN server-side
3. ✅ Show loading state (disable button + change text)
4. ✅ Handle error dengan detail message
5. ✅ Reset form setelah success
6. ✅ Log error ke console untuk debugging

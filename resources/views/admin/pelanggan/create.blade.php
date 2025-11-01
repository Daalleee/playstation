@extends('admin.layout')
@section('title','Tambah Pelanggan - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Tambah Pelanggan</h1>
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="card p-3">
        <form method="POST" action="{{ route('admin.pelanggan.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-control">
                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" required class="form-control">
                @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">No HP</label>
                <input type="text" id="admin_phone_create" name="phone" value="{{ old('phone', '+62') }}" class="form-control" placeholder="Contoh: +6281234567890 (8-20 digit setelah +62)" inputmode="tel" pattern="^\+62[0-9]{8,20}$" title="Masukkan nomor dengan format: +62 diikuti 8-20 digit angka">
                @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-danger"><i class="bi bi-x me-1"></i> Batal</a>
            </div>
        </form>
    </div>
    <script>
      (function(){
        const input = document.getElementById('admin_phone_create');
        if (input) {
          const prefix = '+62';
          function ensurePrefix(){
            if (!input.value || !input.value.startsWith(prefix)){
              const digits = input.value.replace(/[^0-9]/g,'');
              input.value = prefix + digits;
            }
          }
          input.addEventListener('focus', ensurePrefix);
          input.addEventListener('input', ensurePrefix);
          ensurePrefix();
        }
      })();
    </script>
@endsection

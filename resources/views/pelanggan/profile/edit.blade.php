@extends('layouts.app')
@section('content')
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); position:sticky; top:1rem; min-height:calc(100dvh - 2rem); }
  .dash-logo{ width:100%; display:grid; place-items:center; margin-bottom:1rem; }
  .dash-logo .circle{ width:96px; height:96px; border-radius:50%; background:#0b3d91; display:grid; place-items:center; box-shadow:inset 0 -8px 14px rgba(0,0,0,.25); }
  .dash-menu{ list-style:none; padding:0; margin:1rem 0 0; display:grid; gap:.4rem; }
  .dash-menu a{ display:flex; align-items:center; gap:.6rem; padding:.6rem .8rem; border-radius:.6rem; color:#e9e9ff; text-decoration:none; font-weight:700; }
  .dash-menu a:hover{ background:rgba(255,255,255,.06); }
  .dash-main{ flex:1; }
  .prof-hero{ padding:1.25rem 1rem; }
  .prof-hero h2{ font-weight:800; margin:0; }
  .prof-hero p{ color:#aeb5e6; margin:0; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1.25rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  .input-dark, .textarea-dark, .select-dark, .file-dark{ width:100%; background:#23284a; color:#eef1ff; border:1px solid #2f3561; border-radius:.6rem; padding:.6rem .8rem; }
  .label{ display:block; color:#cdd3ff; font-weight:700; margin-bottom:6px; }
  .btn-cta{ background:#44c77b; color:#0e1a2f; font-weight:800; border:none; border-radius:.6rem; padding:.55rem 1rem; }
  .btn-grey{ background:#6c757d; color:#fff; border:none; border-radius:.6rem; padding:.55rem 1rem; }
  .alert-dark{ background:#2a334f; color:#f3f6ff; border:1px solid #3a4371; border-radius:.6rem; padding:.75rem 1rem; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ position:static; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="prof-hero">
        <h2>Edit Profil</h2>
        <p>Perbarui informasi akunmu</p>
      </div>

      @if($errors->any())
        <div class="alert-dark mb-3">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="card-dark">
        <form method="POST" action="{{ route('pelanggan.profile.update') }}">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-md-6">
              <label for="name" class="label">Nama Lengkap</label>
              <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="input-dark">
            </div>
            <div class="col-md-6">
              <label for="email" class="label">Email</label>
              <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="input-dark">
            </div>
            <div class="col-md-6">
              <label for="phone" class="label">Telepon</label>
              <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="input-dark">
            </div>
            <div class="col-md-6">
              <label for="address" class="label">Alamat</label>
              <textarea id="address" name="address" rows="1" class="textarea-dark">{{ old('address', $user->address) }}</textarea>
            </div>
          </div>

          <hr class="my-4" style="border-color:#2f3561;">
          <h6 class="mb-3" style="color:#cdd3ff; font-weight:800;">Ubah Password (Opsional)</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="current_password" class="label">Password Saat Ini</label>
              <input type="password" id="current_password" name="current_password" class="input-dark">
            </div>
            <div class="col-md-4">
              <label for="password" class="label">Password Baru</label>
              <input type="password" id="password" name="password" class="input-dark">
            </div>
            <div class="col-md-4">
              <label for="password_confirmation" class="label">Konfirmasi Password Baru</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="input-dark">
            </div>
          </div>

          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-cta">Simpan Perubahan</button>
            <a href="{{ route('pelanggan.profile.show') }}" class="btn btn-grey">Batal</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>
@endsection

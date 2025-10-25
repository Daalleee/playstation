@extends('layouts.app')
@section('content')
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; height: 100vh; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); height: 100vh; overflow-y: auto; position: sticky; top: 0; }
  .dash-logo{ width:100%; display:grid; place-items:center; margin-bottom:1rem; }
  .dash-logo .circle{ width:96px; height:96px; border-radius:50%; background:#0b3d91; display:grid; place-items:center; box-shadow:inset 0 -8px 14px rgba(0,0,0,.25); }
  .dash-menu{ list-style:none; padding:0; margin:1rem 0 0; display:grid; gap:.4rem; }
  .dash-menu a{ display:flex; align-items:center; gap:.6rem; padding:.6rem .8rem; border-radius:.6rem; color:#e9e9ff; text-decoration:none; font-weight:700; }
  .dash-menu a:hover{ background:rgba(255,255,255,.06); }
  .dash-main{ flex:1; overflow-y: auto; padding: 1rem; }
  .prof-hero{ padding:1.25rem 1rem; }
  .prof-hero h2{ font-weight:800; margin:0; }
  .prof-hero p{ color:#aeb5e6; margin:0; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1.25rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  .avatar{ width:120px; height:120px; border-radius:50%; background:#2b3156; display:grid; place-items:center; margin:0 auto 1rem; box-shadow:inset 0 -10px 16px rgba(0,0,0,.25); }
  .badge-soft{ background:#23284a; color:#cfd3ff; border-radius:.5rem; padding:.35rem .6rem; display:inline-block; font-weight:700; }
  .field{ margin-bottom:12px; }
  .field label{ display:block; color:#cdd3ff; font-weight:700; margin-bottom:6px; }
  .field .value{ background:#23284a; border:1px solid #2f3561; border-radius:.6rem; padding:.6rem .8rem; color:#eef1ff; }
  .btn-cta{ background:#44c77b; color:#0e1a2f; font-weight:800; border:none; border-radius:.6rem; padding:.55rem 1rem; }
  .btn-grey{ background:#6c757d; color:#fff; border:none; border-radius:.6rem; padding:.55rem 1rem; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ flex:0 0 auto; position:static; height: auto; } .dash-main{ height: auto; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="prof-hero">
        <h2>Profil Pelanggan</h2>
        <p>Kelola informasi akunmu</p>
      </div>

      @if(session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
      @endif

      <div class="row g-3">
        <div class="col-lg-4">
          <div class="card-dark text-center h-100">
            <div class="avatar">
              <svg width="56" height="56" viewBox="0 0 24 24" fill="#cfd3ff"><path d="M12 2a5 5 0 100 10 5 5 0 000-10zm0 12c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/></svg>
            </div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <div class="text-muted mb-3">{{ $user->email }}</div>
            <div class="d-grid gap-2 text-start">
              <div class="badge-soft">Status: Aktif</div>
              <div class="badge-soft">Telepon: {{ $user->phone ?? '-' }}</div>
              <div class="badge-soft">Terdaftar: {{ $user->created_at->format('d M Y') }}</div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="card-dark h-100">
            <div class="field">
              <label>Nama Lengkap</label>
              <div class="value">{{ $user->name }}</div>
            </div>
            <div class="field">
              <label>Email</label>
              <div class="value">{{ $user->email }}</div>
            </div>
            <div class="field">
              <label>Telepon</label>
              <div class="value">{{ $user->phone ?? '-' }}</div>
            </div>
            <div class="field">
              <label>Alamat</label>
              <div class="value">{{ $user->address ?? '-' }}</div>
            </div>
            <div class="mt-3 d-flex gap-2">
              <a href="{{ route('pelanggan.profile.edit') }}" class="btn btn-cta">Edit Profil</a>
              <a href="{{ route('dashboard.pelanggan') }}" class="btn btn-grey">Kembali</a>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
@endsection
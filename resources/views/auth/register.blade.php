@extends('layouts.app')
@section('content')
<style>
  body { background: radial-gradient(1200px 600px at 80% 20%, #2b2f59 0%, #1f2444 40%, #171b34 100%); }
  .auth-split { display: flex; min-height: 100vh; flex-direction: column; }
  .auth-hero { display: block; flex: 0 0 auto; width: auto; height: 32vh; position: relative; margin: 12px; border-radius: 16px; background: linear-gradient(0deg, rgba(17,17,34,.45), rgba(17,17,34,.45)), url('https://www.genmuda.com/wp-content/uploads/2016/11/SlimandPro.jpg'); background-size: cover; background-position: center; box-shadow: inset 0 0 0 6px rgba(150,160,255,.14), 0 10px 30px rgba(0,0,0,.35); }
  .auth-panel { flex: 1; display: grid; place-items: center; padding: 24px; }
  .panel-inner { width: 100%; max-width: 420px; margin: 0 8%; }
  .login-wrap { min-height: auto; display: grid; place-items: center; }
  .login-card { background: rgba(96,102,178,.92); border: 1px solid rgba(150,160,255,.18); border-radius: 28px; max-width: 380px; width: 100%; padding: 32px 24px; box-shadow: 0 20px 60px rgba(0,0,0,.35); backdrop-filter: blur(6px); }
  .login-card, .login-card p, .login-card label, .login-card small { color: #ffffff; }
  .login-card .brand { width: 84px; height: 84px; border-radius: 50%; background: #0b3d91; display: grid; place-items: center; margin: 4px auto 12px; }
  .login-title { text-align: center; font-weight: 800; color: #ffffff; margin-bottom: 14px; letter-spacing: .2px; }
  .form-underline { position: relative; margin-bottom: 14px; }
  .form-underline input { width: 100%; border: none; outline: none; background: transparent; padding: 12px 12px 12px 36px; border-bottom: 2px solid rgba(255,255,255,.9); color: #ffffff; font-size: 15px; border-radius: 0; }
  .form-underline input { font-size: 16px; }
  .form-underline input::placeholder { color: rgba(255,255,255,.82); }
  .form-underline input:focus { border-bottom-color: #ffffff; box-shadow: none; }
  .form-underline .icon { position: absolute; left: 8px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.92); opacity: 1; }
  .toggle-pass { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: transparent; border: 0; padding: 4px 6px; color: #ffffff; }
  .btn-primary-login { display: block; width: 100%; background: linear-gradient(90deg,#5a35ff,#6d56ff); color: #fff; font-weight: 700; border: none; padding: 10px 16px; border-radius: 999px; }
  .auth-alt a { color: #f6f7ff; text-decoration: none; font-size: 14px; }
  @media (max-width: 575.98px) {
    .auth-hero { height: 28vh; margin: 10px; border-radius: 14px; }
    .auth-panel { padding: 16px; }
    .panel-inner { margin: 0 10px; }
    .login-card { max-width: 100%; padding: 24px 18px; border-radius: 22px; }
    .login-title { font-size: 1.35rem; }
    .btn-primary-login { padding: 12px 14px; }
  }
  @media (min-width: 520px) { .login-card { border-radius: 32px; padding: 38px 28px; } }
  @media (min-width: 992px) {
    .auth-split { flex-direction: row; }
    .auth-hero { display: block; flex: 0 0 46%; height: auto; margin: 16px; border-radius: 18px; }
  }
  .form-underline.error input{ border-bottom-color:#ff5a5a !important; }
  .error-text{ color:#ffb4b4; font-size:12px; margin:4px 0 0 36px; display:block; }
</style>

<div class="auth-split">
  <div class="auth-hero" role="img" aria-label="Promo image"></div>
  <div class="auth-panel">
    <div class="panel-inner">
      <div class="login-card">
    <div class="brand">
      <img width="42" height="42" alt="PlayStation" src="https://cdn.simpleicons.org/playstation/ffffff" />
    </div>
    <h1 class="login-title h3">Registrasi</h1>

    @if ($errors->any())
      <div class="alert alert-danger py-2">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
      @csrf

      <div class="form-underline @error('name') error @enderror">
        <span class="icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 12c2.761 0 5-2.239 5-5S14.761 2 12 2 7 4.239 7 7s2.239 5 5 5zm0 2c-3.314 0-6 2.686-6 6h12c0-3.314-2.686-6-6-6z"/></svg>
        </span>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap" autocomplete="name" />
        @error('name')<small class="error-text">{{ $message }}</small>@enderror
      </div>

      <div class="form-underline @error('address') error @enderror">
        <span class="icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2L2 9l10 7 10-7-10-7zm0 18l-7-4.9V20h14v-4.9L12 20z"/></svg>
        </span>
        <input type="text" name="address" value="{{ old('address') }}" required placeholder="Alamat" autocomplete="street-address" />
        @error('address')<small class="error-text">{{ $message }}</small>@enderror
      </div>

      <div class="form-underline @error('phone') error @enderror">
        <span class="icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.1.37 2.29.57 3.58.6a1 1 0 011 1V20a1 1 0 01-1 1C10.85 21 3 13.15 3 3a1 1 0 011-1h2.46a1 1 0 011 1.01c.03 1.29.23 2.48.6 3.58a1 1 0 01-.24 1.01l-2.2 2.2z"/></svg>
        </span>
        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Nomor Telepon (11-12 digit)" inputmode="numeric" autocomplete="tel" minlength="11" maxlength="12" pattern="[0-9]{11,12}" title="Masukkan 11-12 digit angka" />
        @error('phone')<small class="error-text">{{ $message }}</small>@enderror
      </div>

      <div class="form-underline @error('email') error @enderror">
        <span class="icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        </span>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email" autocomplete="email" />
        @error('email')<small class="error-text">{{ $message }}</small>@enderror
      </div>

      <div class="form-underline @error('password') error @enderror" style="padding-right:36px;">
        <span class="icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1a5 5 0 00-5 5v3H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2v-8a2 2 0 00-2-2h-2V6a5 5 0 00-5-5zm-3 8V6a3 3 0 116 0v3H9z"/></svg>
        </span>
        <input type="password" name="password" id="password" required placeholder="Password" autocomplete="new-password" />
        <button type="button" class="toggle-pass" id="togglePassword" aria-label="Tampilkan password">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 110-10 5 5 0 010 10z"/></svg>
        </button>
        @error('password')<small class="error-text">{{ $message }}</small>@enderror
      </div>

      <div class="form-underline" style="padding-right:36px;">
        <span class="icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1a5 5 0 00-5 5v3H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2v-8a2 2 0 00-2-2h-2V6a5 5 0 00-5-5zm-3 8V6a3 3 0 116 0v3H9z"/></svg>
        </span>
        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Konfirmasi Password" autocomplete="new-password" />
        <button type="button" class="toggle-pass" id="togglePassword2" aria-label="Tampilkan password">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 110-10 5 5 0 010 10z"/></svg>
        </button>
      </div>

      <button type="submit" class="btn-primary-login">Register</button>

      <div class="auth-alt text-center mt-2">
        <small>Sudah punya akun? <a href="{{ route('login.show') }}">Masuk</a></small>
      </div>
    </form>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    function setupToggle(btnId, inputId){
      const btn = document.getElementById(btnId);
      const input = document.getElementById(inputId);
      if(btn && input){
        btn.addEventListener('click', function(){
          const t = input.getAttribute('type') === 'password' ? 'text' : 'password';
          input.setAttribute('type', t);
          this.setAttribute('aria-label', t === 'password' ? 'Tampilkan password' : 'Sembunyikan password');
        });
      }
    }
    setupToggle('togglePassword','password');
    setupToggle('togglePassword2','password_confirmation');
  })();
</script>
@endsection

@extends('layouts.app')
@section('content')
<style>
  body { background: radial-gradient(1200px 600px at 80% 20%, #2b2f59 0%, #1f2444 40%, #171b34 100%); }
  html, body { margin: 0; padding: 0; overflow-x: hidden; }
  .auth-split { display: flex; min-height: 100dvh; flex-direction: column; }
  .auth-hero { display: block; flex: 0 0 auto; width: calc(100% - 24px); height: 30vh; position: relative; margin: 12px; border-radius: 16px; background: linear-gradient(0deg, rgba(17,17,34,.45), rgba(17,17,34,.45)), url('https://www.genmuda.com/wp-content/uploads/2016/11/SlimandPro.jpg'); background-color: #111224; background-size: cover; background-position: center; box-shadow: inset 0 0 0 6px rgba(150,160,255,.14), 0 10px 30px rgba(0,0,0,.35); }
  .auth-panel { flex: 1; display: grid; place-items: center; padding: 24px; }
  .panel-inner { width: 100%; max-width: 420px; margin: 0 12px; }
  .login-wrap { min-height: auto; display: grid; place-items: center; width: 100%; }
  .login-card { background: rgba(96,102,178,.92); border: 1px solid rgba(150,160,255,.18); border-radius: 28px; max-width: 380px; width: 100%; padding: 28px 22px; box-shadow: 0 20px 60px rgba(0,0,0,.35); backdrop-filter: blur(6px); }
  .login-card, .login-card p, .login-card label, .login-card small { color: #ffffff; }
  .login-card .text-muted { color: #ffffff !important; opacity: 1 !important; }
  .login-card a { color: #ffffff; }
  .login-card a:hover { opacity: .9; }
  .login-card .brand { width: 84px; height: 84px; border-radius: 50%; background: #0b3d91; display: grid; place-items: center; margin: 4px auto 10px; box-shadow: inset 0 -6px 12px rgba(0,0,0,.18); }
  .login-title { text-align: center; font-weight: 800; color: #ffffff; margin-bottom: 14px; letter-spacing: .2px; }
  .form-underline { position: relative; margin-bottom: 16px; }
  .form-underline input { width: 100%; border: none; outline: none; background: transparent; padding: 12px 12px 12px 36px; border-bottom: 2px solid rgba(255,255,255,.9); color: #ffffff; font-size: 15px; border-radius: 0; }
  .form-underline input::placeholder { color: rgba(255,255,255,.82); }
  .form-underline input:focus { border-bottom-color: #ffffff; box-shadow: none; }
  .form-underline .icon { position: absolute; left: 8px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.92); opacity: 1; }
  .form-meta { display: flex; justify-content: flex-end; margin-top: -6px; margin-bottom: 14px; }
  .form-meta a { color: #ffffff; font-size: 13px; text-decoration: none; }
  .toggle-pass { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: transparent; border: 0; padding: 4px 6px; color: #ffffff; }
  .btn-primary-login { display: block; width: 100%; background: linear-gradient(90deg,#5a35ff,#6d56ff); color: #fff; font-weight: 700; border: none; padding: 10px 16px; border-radius: 999px; }
  .btn-primary-login:active { transform: translateY(1px); }
  .btn-google { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: rgba(255,255,255,.06); color: #ffffff; border: 1px solid rgba(150,160,255,.25); padding: 10px 14px; border-radius: 999px; font-weight: 600; }
  .btn-google img { width: 18px; height: 18px; }
  .auth-alt { text-align: center; margin-top: 10px; }
  .auth-alt a { color: #f6f7ff; text-decoration: none; font-size: 14px; }
  @media (min-width: 576px) { .panel-inner { margin: 0 5%; } }
  @media (min-width: 480px) { .login-card { padding: 32px 26px; border-radius: 30px; } }
  @media (min-width: 992px) {
    .auth-split { flex-direction: row; }
    .auth-hero { display: block; flex: 0 0 46%; width: auto; height: auto; margin: 16px; border-radius: 18px; }
    .panel-inner { margin: 0 8%; }
  }
  @media (hover: hover) { .btn-google:hover { filter: brightness(.97); } .btn-primary-login:hover { filter: brightness(1.05); } }
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
        <h1 class="login-title h3">Login</h1>

        <div class="text-center mb-2">
          <p class="text-muted mb-2" style="color:#343a40; opacity:.8">Silakan login untuk mengakses sistem</p>
          @if (session('status'))
            <div class="alert alert-info py-2 px-3">{{ session('status') }}</div>
          @endif
        </div>

        @if ($errors->any())
          <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <div class="form-underline @error('email') error @enderror">
            <span class="icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.866 0-7 3.134-7 7h2c0-2.761 2.239-5 5-5s5 2.239 5 5h2c0-3.866-3.134-7-7-7z"/></svg>
            </span>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email" inputmode="email" autocomplete="username" />
            @error('email')<small class="error-text">{{ $message }}</small>@enderror
          </div>

          <div class="form-underline @error('password') error @enderror" style="padding-right:36px;">
            <span class="icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1a5 5 0 00-5 5v3H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2v-8a2 2 0 00-2-2h-2V6a5 5 0 00-5-5zm-3 8V6a3 3 0 116 0v3H9z"/></svg>
            </span>
            <input type="password" name="password" id="password" required placeholder="Password" autocomplete="current-password" />
            <button type="button" class="toggle-pass" id="togglePassword" aria-label="Tampilkan password">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 110-10 5 5 0 010 10z"/></svg>
            </button>
            @error('password')<small class="error-text">{{ $message }}</small>@enderror
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3" style="margin-top:2px;">
            <div class="form-check mb-0">
              <input class="form-check-input" type="checkbox" name="remember" id="remember">
              <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <a href="#" class="small" style="text-decoration:none;">Forgot Password?</a>
          </div>

          <button type="submit" class="btn-primary-login mb-3">Login</button>

          <button type="button" class="btn-google" onclick="alert('Google Sign-In belum dikonfigurasi');">
            <img alt="Google" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" />
            <span>Login With Google</span>
          </button>

          <div class="auth-alt mt-2">
            <small>Belum punya akun? <a href="{{ route('register.show') }}">Registrasi di sini</a></small>
          </div>
        </form>
      </div>
    </div>
    <div class="pb-3"></div>
    <div class="pb-2 d-none d-sm-block"></div>
  </div>
</div>
<script>
  (function(){
    const btn = document.getElementById('togglePassword');
    const pwd = document.getElementById('password');
    if(btn && pwd){
      btn.addEventListener('click', function(){
        const t = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
        pwd.setAttribute('type', t);
        this.setAttribute('aria-label', t === 'password' ? 'Tampilkan password' : 'Sembunyikan password');
      });
    }
  })();
</script>
@endsection



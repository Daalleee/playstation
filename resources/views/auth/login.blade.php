@extends('layouts.auth')

@section('title', 'Login - PlayStation Rental')

@section('content')
    <div class="auth-header">
        <h1 class="auth-title">Selamat Datang Kembali</h1>
        <p class="auth-subtitle">Silakan login untuk mengakses sistem</p>
    </div>

    @if(session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">Username / Email</label>
            <div class="input-wrapper">
                <input 
                    type="text" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="Masukkan username atau email"
                    required 
                    autofocus
                >
                <i class="fas fa-user input-icon"></i>
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password" 
                    placeholder="Masukkan password"
                    required
                >
                <i class="fas fa-lock input-icon"></i>
                <button type="button" class="password-toggle">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-check">
            <div class="form-check-left">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <a href="{{ route('password.request') }}" class="auth-link">Lupa Password?</a>
        </div>

        <button type="submit" class="btn-auth btn-primary-auth">
            <span>Login</span>
            <i class="fas fa-arrow-right"></i>
        </button>

        <div class="auth-divider">
            <span>atau lanjutkan dengan</span>
        </div>

        <a href="{{ route('auth.google.redirect') }}" class="btn-auth btn-google">
            <img src="https://www.google.com/favicon.ico" alt="Google">
            Login dengan Google
        </a>

        <div class="auth-bottom-links">
            Belum punya akun? <a href="{{ route('register.show') }}" class="auth-link">Daftar Sekarang</a>
        </div>
    </form>
@endsection

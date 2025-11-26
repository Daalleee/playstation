@extends('layouts.auth')

@section('title', 'Lupa Password - PlayStation Rental')

@section('content')
    <div class="auth-header">
        <h1 class="auth-title">Lupa Password?</h1>
        <p class="auth-subtitle">Masukkan email untuk menerima link reset password</p>
    </div>

    @if (session('status'))
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

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="Masukkan email terdaftar"
                    required 
                    autofocus
                >
                <i class="fas fa-envelope input-icon"></i>
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-auth btn-primary-auth">
            <span>Kirim Link Reset</span>
            <i class="fas fa-paper-plane"></i>
        </button>

        <div class="auth-bottom-links">
            Kembali ke <a href="{{ route('login.show') }}" class="auth-link">Halaman Login</a>
        </div>
    </form>
@endsection

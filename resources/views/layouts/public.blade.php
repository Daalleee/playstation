<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Rental PlayStation - Experience the Next Level')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('landing') }}" class="nav-brand">
            <i class="fa-brands fa-playstation"></i>
            <span>RENTAL PS</span>
        </a>
        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}" class="btn-nav btn-login">Dashboard</a>
                @else
                    <a href="{{ route('login.show') }}" class="btn-nav btn-login">Login</a>
                    @if (Route::has('register.show'))
                        <a href="{{ route('register.show') }}" class="btn-nav btn-register">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <i class="fa-brands fa-playstation"></i>
            </div>
            <div class="footer-links">
                <a href="{{ route('about') }}">Tentang Kami</a>
                <a href="{{ route('terms') }}">Syarat & Ketentuan</a>
                <a href="{{ route('privacy') }}">Kebijakan Privasi</a>
                <a href="{{ route('contact') }}">Kontak</a>
            </div>
            <p class="copyright">&copy; {{ date('Y') }} Rental PlayStation. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

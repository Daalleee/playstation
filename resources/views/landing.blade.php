@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <i class="fa-brands fa-playstation hero-logo"></i>
            <h1>Nikmati Pengalaman Baru!</h1>
            <p>Sewa konsol PlayStation terbaru dengan harga terjangkau. Nikmati pengalaman gaming terbaik tanpa batas.</p>
            <div class="hero-buttons">
                <a href="{{ route('register.show') }}" class="btn-hero btn-primary">
                    Mulai Sewa <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#features" class="btn-hero btn-secondary">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-gamepad feature-icon"></i>
                <h3>Konsol Terbaru</h3>
                <p>Tersedia PS4 dan PS5 dengan kondisi prima dan controller original untuk pengalaman terbaik.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-clock feature-icon"></i>
                <h3>Sewa Fleksibel</h3>
                <p>Pilih durasi sewa sesuai kebutuhan Anda, mulai dari harian hingga mingguan dengan harga kompetitif.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-headset feature-icon"></i>
                <h3>Support 24/7</h3>
                <p>Tim kami siap membantu Anda kapan saja jika mengalami kendala teknis selama masa sewa.</p>
            </div>
        </div>
    </section>
@endsection
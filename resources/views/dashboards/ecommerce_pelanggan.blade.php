@extends('layouts.ecommerce')

@section('title', 'Beranda - PlayStation Rental')

@section('content')
<style>
    .category-nav {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        overflow-x: auto;
        padding-bottom: 5px;
    }
    .category-item {
        padding: 8px 16px;
        border-radius: 20px;
        background: #f1f3ff;
        color: #495057;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .category-item:hover {
        background: #e2e6ff;
    }
    .category-item.active {
        background: #4361ee;
        color: white;
    }
</style>
<div class="container">
    <!-- Hero Section -->
    <section class="hero-section rounded-3 mt-4">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3">Sewa PlayStation Favoritmu</h1>
            <p class="lead mb-4">Temukan konsol, game, dan aksesoris terbaik untuk pengalaman bermain terbaik</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('pelanggan.unitps.index') }}" class="btn btn-light btn-lg px-4 py-2">
                    <i class="bi bi-controller me-2"></i>Jelajahi Unit PS
                </a>
                <a href="{{ route('pelanggan.games.index') }}" class="btn btn-outline-light btn-lg px-4 py-2">
                    <i class="bi bi-disc me-2"></i>Jelajahi Games
                </a>
                  <a href="{{ route('pelanggan.accessories.index') }}" class="btn btn-outline-light btn-lg px-4 py-2">
                    <i class="bi bi-disc me-2"></i>Jelajahi Aksesoris
                </a>
            </div>
        </div>
    </section>

    <form method="GET" action="{{ route('dashboard.pelanggan') }}" class="mb-4" id="search-form">
        <div class="position-relative">
            <input type="text" name="q" class="form-control form-control-lg search-input" placeholder="Cari PlayStation, game, atau aksesoris..." value="{{ request('q') }}" onkeypress="handleSearchKeyPress(event, this.form)">
            <button type="submit" class="search-icon-btn position-absolute top-50 start-0 translate-middle-y border-0 bg-transparent ps-3" style="margin-left: 0.5rem; z-index: 5;">
                <i class="bi bi-search search-icon"></i>
            </button>
        </div>
    </form>
    <style>
        .search-input {
            padding-left: 3.5rem !important;
        }
        .search-icon-btn {
            cursor: pointer;
            padding: 0;
            width: auto;
            height: auto;
        }
        .search-icon-btn:hover {
            opacity: 0.8;
        }
    </style>
    <script>
        function handleSearchKeyPress(event, form) {
            if (event.key === 'Enter') {
                event.preventDefault();
                form.submit();
            }
        }
    </script>

{{-- untuk kategori semua produk --}}
    {{-- <div class="category-nav">
        <a href="{{ route('dashboard.pelanggan', ['q' => request('q')]) }}" class="category-item {{ !request('model') && !request()->routeIs('pelanggan.games.*') && !request()->routeIs('pelanggan.accessories.*') && !request()->routeIs('pelanggan.unitps.*') ? 'active' : '' }}">Semua Produk</a>
        <a href="{{ route('pelanggan.unitps.index', ['q' => request('q'), 'model' => 'PlayStation 5']) }}" class="category-item {{ request('model') == 'PlayStation 5' || request()->routeIs('pelanggan.unitps.*') ? 'active' : '' }}">PlayStation 5</a>
        <a href="{{ route('pelanggan.unitps.index', ['q' => request('q'), 'model' => 'PlayStation 4']) }}" class="category-item {{ request('model') == 'PlayStation 4' || request()->routeIs('pelanggan.unitps.*') ? 'active' : '' }}">PlayStation 4</a>
        <a href="{{ route('pelanggan.games.index', ['q' => request('q')]) }}" class="category-item {{ request()->routeIs('pelanggan.games.*') ? 'active' : '' }}">Games</a>
        <a href="{{ route('pelanggan.accessories.index', ['q' => request('q')]) }}" class="category-item {{ request()->routeIs('pelanggan.accessories.*') ? 'active' : '' }}">Aksesoris</a>
    </div> --}}

    <!-- Featured PlayStation Units -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Unit PlayStation</h2>
            <a href="{{ route('pelanggan.unitps.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-right me-1"></i>Lihat Semua
            </a>
        </div>
        
        <div class="row g-4">
            @forelse($unitps as $unit)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card">
                        <div class="product-image">
                            @if($unit->foto)
                                <img src="{{ asset('storage/' . $unit->foto) }}" alt="{{ $unit->name }}" class="img-fluid">
                            @else
                                <img src="https://placehold.co/300x200/f8f9fa/6c757d?text={{ urlencode($unit->model) }}" alt="{{ $unit->name }}" class="img-fluid">
                            @endif
                            @if($unit->instances->where('status', 'available')->count() <= 3)
                                <span class="featured-badge">Stok Terbatas!</span>
                            @endif
                        </div>
                        <div class="product-content">
                            <h5 class="product-title">{{ $unit->name }}</h5>
                            <p class="product-subtitle">{{ $unit->model }} - {{ $unit->brand }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-price">
                                    Rp {{ number_format($unit->price_per_hour, 0, ',', '.') }}/jam
                                </div>
                                <div class="product-stock">
                                    {{ $unit->instances->where('status', 'available')->count() }} unit
                                </div>
                            </div>
                            
                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ route('pelanggan.rentals.create') }}?type=unitps&id={{ $unit->id }}"
                                   class="btn btn-shopping btn-sm flex-grow-1 {{ $unit->instances->where('status', 'available')->count() <= 0 ? 'disabled' : '' }}">
                                    Sewa
                                </a>
                                <button onclick="addToCart('unitps', {{ $unit->id }}, 1)"
                                        class="btn btn-outline-primary btn-sm add-to-cart-btn"
                                        title="Tambahkan ke Keranjang">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-playstation fs-1 text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada unit PlayStation tersedia saat ini.</p>
                    <a href="{{ route('pelanggan.unitps.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right me-1"></i>Kunjungi Katalog
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Featured Games -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Game Terbaru</h2>
            <a href="{{ route('pelanggan.games.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-right me-1"></i>Lihat Semua
            </a>
        </div>
        
        <div class="row g-4">
            @forelse($games as $game)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card">
                        <div class="product-image">
                            @if($game->gambar)
                                <img src="{{ asset('storage/' . $game->gambar) }}" alt="{{ $game->judul }}" class="img-fluid">
                            @else
                                <img src="https://placehold.co/300x200/f8f9fa/6c757d?text=Game" alt="{{ $game->judul }}" class="img-fluid">
                            @endif
                            @if($game->stok <= 5)
                                <span class="featured-badge">Limited</span>
                            @endif
                        </div>
                        <div class="product-content">
                            <h5 class="product-title">{{ $game->judul }}</h5>
                            <p class="product-subtitle">{{ $game->platform }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-price">
                                    Rp {{ number_format($game->harga_per_hari, 0, ',', '.') }}/hari
                                </div>
                                <div class="product-stock">
                                    {{ $game->stok }} tersedia
                                </div>
                            </div>
                            
                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ route('pelanggan.rentals.create') }}?type=game&id={{ $game->id }}"
                                   class="btn btn-shopping btn-sm flex-grow-1 {{ $game->stok <= 0 ? 'disabled' : '' }}">
                                    Sewa
                                </a>
                                <button onclick="addToCart('game', {{ $game->id }}, 1)"
                                        class="btn btn-outline-primary btn-sm add-to-cart-btn"
                                        title="Tambahkan ke Keranjang">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-disc fs-1 text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada game tersedia saat ini.</p>
                    <a href="{{ route('pelanggan.games.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right me-1"></i>Kunjungi Katalog
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Featured Accessories -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Aksesoris Terbaik</h2>
            <a href="{{ route('pelanggan.accessories.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-right me-1"></i>Lihat Semua
            </a>
        </div>
        
        <div class="row g-4">
            @forelse($accessories as $acc)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card">
                        <div class="product-image">
                            @if($acc->gambar)
                                <img src="{{ asset('storage/' . $acc->gambar) }}" alt="{{ $acc->nama }}" class="img-fluid">
                            @else
                                <img src="https://placehold.co/300x200/f8f9fa/6c757d?text=Aksesoris" alt="{{ $acc->nama }}" class="img-fluid">
                            @endif
                        </div>
                        <div class="product-content">
                            <h5 class="product-title">{{ $acc->nama }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-price">
                                    Rp {{ number_format($acc->harga_per_hari, 0, ',', '.') }}/hari
                                </div>
                                <div class="product-stock">
                                    {{ $acc->stok }} tersedia
                                </div>
                            </div>
                            
                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ route('pelanggan.rentals.create') }}?type=accessory&id={{ $acc->id }}"
                                   class="btn btn-shopping btn-sm flex-grow-1 {{ $acc->stok <= 0 ? 'disabled' : '' }}">
                                    Sewa
                                </a>
                                <button onclick="addToCart('accessory', {{ $acc->id }}, 1)"
                                        class="btn btn-outline-primary btn-sm add-to-cart-btn"
                                        title="Tambahkan ke Keranjang">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-plugin fs-1 text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada aksesoris tersedia saat ini.</p>
                    <a href="{{ route('pelanggan.accessories.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right me-1"></i>Kunjungi Katalog
                    </a>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
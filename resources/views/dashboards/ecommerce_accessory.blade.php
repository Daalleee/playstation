@extends('layouts.ecommerce')

@section('title', 'Aksesoris - PlayStation Rental')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <section class="hero-section rounded-3">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3">Aksesoris PlayStation Tersedia</h1>
            <p class="lead">Temukan aksesoris pelengkap untuk pengalaman bermain terbaik</p>
        </div>
    </section>

    <form method="GET" action="{{ route('pelanggan.accessories.index') }}" class="mb-4">
        <div class="position-relative">
            <input type="text" name="q" class="form-control form-control-lg search-input" placeholder="Cari aksesoris..." value="{{ request('q') }}" onkeypress="handleSearchKeyPress(event, this.form)">
            <button type="submit" class="search-icon-btn position-absolute top-50 start-0 translate-middle-y border-0 bg-transparent ps-3">
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

    <div class="category-nav">
        <div class="category-item active">Semua</div>
        <div class="category-item">Controller</div>
        <div class="category-item">Charging Station</div>
        <div class="category-item">Headset</div>
        <div class="category-item">Memory Card</div>
    </div>

    <!-- Accessories -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Aksesoris Tersedia</h2>
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
                    <a href="{{ route('dashboard.pelanggan') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                    </a>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
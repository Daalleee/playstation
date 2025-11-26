@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="text-center py-5 mb-4 rounded-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(108, 99, 255, 0.2), rgba(34, 211, 238, 0.2)); border: 1px solid var(--card-border);">
        <div class="position-relative z-1">
            <h2 class="fw-bold display-5 mb-3 text-white">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="lead text-muted mb-0" style="max-width: 600px; margin: 0 auto;">
                Temukan pengalaman gaming terbaikmu hari ini. Sewa konsol, game, dan aksesoris dengan mudah dan cepat.
            </p>
        </div>
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-grid" style="opacity: 0.1;"></div>
    </div>

    <!-- Unit PS Section -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0"><i class="bi bi-controller me-2 text-primary"></i>Unit PlayStation</h4>
            <a href="{{ route('pelanggan.unitps.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">Lihat Semua</a>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            @forelse($unitps as $unit)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm" style="background: var(--card-bg); border: 1px solid var(--card-border);">
                        <div class="position-relative" style="height: 200px; overflow: hidden; border-radius: 16px 16px 0 0;">
                            @if($unit->foto)
                                <img src="{{ str_starts_with($unit->foto, 'http') ? $unit->foto : asset('storage/' . $unit->foto) }}" 
                                     alt="{{ $unit->name }}" 
                                     class="w-100 h-100 object-fit-cover"
                                     style="transition: transform 0.3s ease;">
                            @else
                                <img src="https://placehold.co/400x300/1e293b/ffffff?text={{ urlencode($unit->model) }}" 
                                     alt="{{ $unit->name }}" 
                                     class="w-100 h-100 object-fit-cover"
                                     style="transition: transform 0.3s ease;">
                            @endif
                            <div class="position-absolute top-0 end-0 m-2">
                                @php 
                                    $stok = $unit->stock ?? 0;
                                    $badgeClass = $stok > 5 ? 'bg-success' : ($stok > 0 ? 'bg-warning text-dark' : 'bg-danger');
                                @endphp
                                <span class="badge {{ $badgeClass }} rounded-pill">{{ $stok }} Unit</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-white mb-1">{{ $unit->name }}</h5>
                            <p class="text-muted small mb-3">{{ $unit->brand }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="text-secondary fw-bold">Rp {{ number_format($unit->price_per_hour, 0, ',', '.') }}<span class="small text-muted fw-normal">/jam</span></div>
                                <a href="{{ route('pelanggan.rentals.create') }}?type=unitps&id={{ $unit->id }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                    Sewa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info bg-opacity-10 border-0 text-info">
                        <i class="bi bi-info-circle me-2"></i>Tidak ada unit PlayStation tersedia saat ini.
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Games Section -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0"><i class="bi bi-disc me-2 text-info"></i>Games Populer</h4>
            <a href="{{ route('pelanggan.games.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">Lihat Semua</a>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            @forelse($games as $game)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm" style="background: var(--card-bg); border: 1px solid var(--card-border);">
                        <div class="position-relative" style="height: 200px; overflow: hidden; border-radius: 16px 16px 0 0;">
                            @if($game->gambar)
                                <img src="{{ str_starts_with($game->gambar, 'http') ? $game->gambar : asset('storage/' . $game->gambar) }}" 
                                     alt="{{ $game->judul }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <img src="https://placehold.co/300x400/1e293b/ffffff?text={{ urlencode($game->judul) }}" alt="{{ $game->judul }}" class="w-100 h-100 object-fit-cover">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-white mb-1 text-truncate">{{ $game->judul }}</h5>
                            <p class="text-muted small mb-3">{{ $game->platform }} • {{ $game->genre }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="text-secondary fw-bold">Rp {{ number_format($game->harga_per_hari, 0, ',', '.') }}<span class="small text-muted fw-normal">/hari</span></div>
                                <a href="{{ route('pelanggan.rentals.create') }}?type=game&id={{ $game->id }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                    Sewa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info bg-opacity-10 border-0 text-info">
                        <i class="bi bi-info-circle me-2"></i>Tidak ada game tersedia saat ini.
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Accessories Section -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0"><i class="bi bi-headset me-2 text-warning"></i>Aksesoris</h4>
            <a href="{{ route('pelanggan.accessories.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">Lihat Semua</a>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            @forelse($accessories as $acc)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm" style="background: var(--card-bg); border: 1px solid var(--card-border);">
                        <div class="position-relative" style="height: 200px; overflow: hidden; border-radius: 16px 16px 0 0;">
                            @if($acc->gambar)
                                <img src="{{ str_starts_with($acc->gambar, 'http') ? $acc->gambar : asset('storage/' . $acc->gambar) }}" 
                                     alt="{{ $acc->nama }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <img src="https://placehold.co/400x300/1e293b/ffffff?text={{ urlencode($acc->nama) }}" alt="{{ $acc->nama }}" class="w-100 h-100 object-fit-cover">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-white mb-1">{{ $acc->nama }}</h5>
                            <p class="text-muted small mb-3">{{ $acc->jenis }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="text-secondary fw-bold">Rp {{ number_format($acc->harga_per_hari, 0, ',', '.') }}<span class="small text-muted fw-normal">/hari</span></div>
                                <a href="{{ route('pelanggan.rentals.create') }}?type=accessory&id={{ $acc->id }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                    Sewa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info bg-opacity-10 border-0 text-info">
                        <i class="bi bi-info-circle me-2"></i>Tidak ada aksesoris tersedia saat ini.
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
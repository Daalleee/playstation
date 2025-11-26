@extends('pemilik.layout')
@section('title','Status Unit & Produk')
@section('owner_content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-1">Status Unit & Produk</h2>
            <p class="text-muted mb-0">Monitoring ketersediaan aset rental secara real-time.</p>
        </div>
    </div>

    <!-- Unit PS Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-white mb-0"><i class="bi bi-controller me-2 text-primary"></i>Unit PlayStation</h5>
            <span class="badge bg-primary bg-opacity-10 text-primary">{{ count($unitps) }} Total</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-10">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-semibold">Produk</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Nama Unit</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Model</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Serial Number</th>
                            <th class="px-4 py-3 text-muted fw-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unitps as $unit)
                            @php $isRented = isset($rentedUnits[$unit->id]) && $rentedUnits[$unit->id] > 0; @endphp
                            <tr>
                                <td class="px-4 py-3">
                                    @if($unit->foto)
                                        <img src="{{ str_starts_with($unit->foto, 'http') ? $unit->foto : asset('storage/' . $unit->foto) }}" 
                                             class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-dark rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                            <i class="bi bi-controller"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-white fw-medium">{{ $unit->name }}</td>
                                <td class="px-4 py-3 text-muted">{{ $unit->model }}</td>
                                <td class="px-4 py-3 text-muted font-monospace small">{{ $unit->serial_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($isRented)
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Sedang Disewa</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data unit</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Games Section -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-white mb-0"><i class="bi bi-disc me-2 text-info"></i>Games</h5>
                    <span class="badge bg-info bg-opacity-10 text-info">{{ count($games) }} Total</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-10 sticky-top backdrop-blur">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-semibold">Produk</th>
                                    <th class="px-4 py-3 text-muted fw-semibold">Judul Game</th>
                                    <th class="px-4 py-3 text-muted fw-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($games as $game)
                                    @php $isRented = isset($rentedGames[$game->id]) && $rentedGames[$game->id] > 0; @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            @if($game->gambar)
                                                <img src="{{ str_starts_with($game->gambar, 'http') ? $game->gambar : asset('storage/' . $game->gambar) }}" 
                                                     class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-dark rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-disc"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-white">{{ $game->judul }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($isRented)
                                                <span class="badge bg-warning-subtle text-warning rounded-pill">Disewa</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success rounded-pill">Ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-3 text-muted">Tidak ada data game</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accessories Section -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-white mb-0"><i class="bi bi-headset me-2 text-warning"></i>Aksesoris</h5>
                    <span class="badge bg-warning bg-opacity-10 text-warning">{{ count($accessories) }} Total</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-10 sticky-top backdrop-blur">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-semibold">Produk</th>
                                    <th class="px-4 py-3 text-muted fw-semibold">Nama Aksesoris</th>
                                    <th class="px-4 py-3 text-muted fw-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accessories as $acc)
                                    @php $isRented = isset($rentedAccessories[$acc->id]) && $rentedAccessories[$acc->id] > 0; @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            @if($acc->gambar)
                                                <img src="{{ str_starts_with($acc->gambar, 'http') ? $acc->gambar : asset('storage/' . $acc->gambar) }}" 
                                                     class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-dark rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-headset"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-white">{{ $acc->nama }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($isRented)
                                                <span class="badge bg-warning-subtle text-warning rounded-pill">Disewa</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success rounded-pill">Ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-3 text-muted">Tidak ada data aksesoris</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .backdrop-blur {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(30, 41, 59, 0.9) !important;
        }
    </style>
@endsection

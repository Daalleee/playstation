@extends('pemilik.layout')
@section('title','Beranda Pemilik')
@section('owner_content')
    @if(session('impersonate_admin_id'))
        <form action="{{ route('admin.impersonate.leave') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm shadow-sm"><i class="bi bi-arrow-return-left me-1"></i> Kembali ke Admin</button>
        </form>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-1">Dashboard Pemilik</h2>
            <p class="text-muted mb-0">Ringkasan performa bisnis Anda hari ini.</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- Unit Tersedia -->
        <div class="col-12 col-md-4 col-lg">
            <a href="{{ route('pemilik.status_produk') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden hover-scale" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); transition: transform 0.3s;">
                    <div class="card-body position-relative z-1 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-white bg-opacity-25 rounded-3 p-2">
                                <i class="bi bi-controller fs-4"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $availableUnits ?? 0 }}</h3>
                        <p class="mb-0 opacity-75">Unit PS Tersedia</p>
                    </div>
                    <div class="position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-10">
                        <i class="bi bi-controller" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Games Tersedia -->
        <div class="col-12 col-md-4 col-lg">
            <a href="{{ route('pemilik.status_produk') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden hover-scale" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); transition: transform 0.3s;">
                    <div class="card-body position-relative z-1 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-white bg-opacity-25 rounded-3 p-2">
                                <i class="bi bi-disc fs-4"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $availableGames ?? 0 }}</h3>
                        <p class="mb-0 opacity-75">Games Tersedia</p>
                    </div>
                    <div class="position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-10">
                        <i class="bi bi-disc" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Aksesoris Tersedia -->
        <div class="col-12 col-md-4 col-lg">
            <a href="{{ route('pemilik.status_produk') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden hover-scale" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); transition: transform 0.3s;">
                    <div class="card-body position-relative z-1 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-white bg-opacity-25 rounded-3 p-2">
                                <i class="bi bi-headset fs-4"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $availableAccessories ?? 0 }}</h3>
                        <p class="mb-0 opacity-75">Aksesoris Tersedia</p>
                    </div>
                    <div class="position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-10">
                        <i class="bi bi-headset" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="col-12 col-md-6 col-lg">
            <a href="{{ route('pemilik.laporan_transaksi') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden hover-scale" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); transition: transform 0.3s;">
                    <div class="card-body position-relative z-1 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-white bg-opacity-25 rounded-3 p-2">
                                <i class="bi bi-receipt fs-4"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">Lihat Laporan <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                        <h3 class="fw-bold mb-1">{{ $todaysTransactions ?? 0 }}</h3>
                        <p class="mb-0 opacity-75">Transaksi Hari Ini</p>
                    </div>
                    <div class="position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-10">
                        <i class="bi bi-receipt" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pendapatan 7 Hari -->
        <div class="col-12 col-md-6 col-lg">
            <a href="{{ route('pemilik.laporan_pendapatan') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden hover-scale" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); transition: transform 0.3s;">
                    <div class="card-body position-relative z-1 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-white bg-opacity-25 rounded-3 p-2">
                                <i class="bi bi-cash-stack fs-4"></i>
                            </div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill">Analisis <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                        <h3 class="fw-bold mb-1">Rp {{ number_format($revTotal7 ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0 opacity-75">Pendapatan 7 Hari</p>
                    </div>
                    <div class="position-absolute bottom-0 end-0 mb-n3 me-n3 opacity-10">
                        <i class="bi bi-wallet2" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Transaksi Terbaru (Brief List) -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-white mb-1">Aktivitas Terbaru</h5>
                <p class="text-muted small mb-0">5 transaksi terakhir yang masuk</p>
            </div>
            <a href="{{ route('pemilik.laporan_transaksi') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-10">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-semibold">Pelanggan</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Item</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Total</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recentTransactions ?? []) as $t)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-25 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            {{ substr($t->customer->name ?? $t->nama_pelanggan ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="fw-medium text-white">{{ $t->customer->name ?? $t->nama_pelanggan ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted">
                                    @if($t->items && $t->items->count() > 0)
                                        @foreach($t->items as $item)
                                            @if($item->rentable_type === 'App\\Models\\UnitPS')
                                                <i class="bi bi-controller me-1"></i> {{ $item->rentable->nama ?? $item->rentable->name ?? 'Unit' }}
                                            @elseif($item->rentable_type === 'App\\Models\\Game')
                                                <i class="bi bi-disc me-1"></i> {{ $item->rentable->judul ?? $item->rentable->title ?? 'Game' }}
                                            @elseif($item->rentable_type === 'App\\Models\\Accessory')
                                                <i class="bi bi-headset me-1"></i> {{ $item->rentable->nama ?? $item->rentable->name ?? 'Aksesoris' }}
                                            @endif
                                            @if(!$loop->last) <br> @endif
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 fw-bold text-success">
                                    Rp {{ number_format($t->total ?? $t->biaya ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    @php $st = $t->status ?? 'selesai'; @endphp
                                    <span class="badge rounded-pill {{ $st==='selesai' || $st==='paid' ? 'bg-success-subtle text-success' : ($st==='active' || $st==='ongoing' ? 'bg-primary-subtle text-primary' : ($st==='pending' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary')) }}">
                                        {{ ucfirst($st) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Belum ada transaksi terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .hover-scale:hover {
            transform: translateY(-5px) !important;
            cursor: pointer;
        }
    </style>
@endsection

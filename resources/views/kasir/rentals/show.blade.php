@extends('kasir.layout')

@section('kasir_content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-white">
                <i class="bi bi-receipt me-2 text-primary"></i>Detail Transaksi
                <span class="text-muted fs-6 ms-2">#{{ $rental->kode ?? $rental->id }}</span>
            </h4>
        </div>
        <a href="{{ route('kasir.rentals.index') }}" class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Items & Payment -->
        <div class="col-lg-8 mb-4">
            <!-- Items Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="mb-0"><i class="bi bi-cart-check me-2"></i>Item Sewa</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rental->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-white">
                                                {{ $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Item Terhapus' }}
                                            </div>
                                            <small class="text-muted">ID: {{ $item->rentable_id }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if($item->rentable_type == 'App\Models\UnitPS')
                                                <span class="badge bg-primary-subtle">Unit PS</span>
                                            @elseif($item->rentable_type == 'App\Models\Game')
                                                <span class="badge bg-info-subtle">Game</span>
                                            @elseif($item->rentable_type == 'App\Models\Accessory')
                                                <span class="badge bg-secondary-subtle">Aksesoris</span>
                                            @else
                                                <span class="badge bg-secondary-subtle">Lainnya</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Rincian Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded bg-dark border border-secondary border-opacity-25">
                                <small class="text-muted d-block mb-1">Total Tagihan</small>
                                <h4 class="text-white mb-0">Rp {{ number_format($rental->total, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded bg-dark border border-secondary border-opacity-25">
                                <small class="text-muted d-block mb-1">Sudah Dibayar</small>
                                <h4 class="text-success mb-0">Rp {{ number_format($rental->paid, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold mb-3 text-white">Status Pembayaran</h6>
                        @if($rental->paid >= $rental->total)
                            <div class="alert alert-success d-flex align-items-center mb-0 border-0 bg-success-subtle text-success">
                                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                <div>
                                    <div class="fw-bold">LUNAS</div>
                                    <small>Seluruh tagihan telah dibayarkan.</small>
                                </div>
                            </div>
                        @elseif($rental->paid > 0)
                            <div class="alert alert-warning d-flex align-items-center mb-0 border-0 bg-warning-subtle text-warning">
                                <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
                                <div>
                                    <div class="fw-bold">KURANG BAYAR</div>
                                    <small>Sisa tagihan: Rp {{ number_format($rental->total - $rental->paid, 0, ',', '.') }}</small>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger d-flex align-items-center mb-0 border-0 bg-danger-subtle text-danger">
                                <i class="bi bi-x-circle-fill fs-4 me-3"></i>
                                <div>
                                    <div class="fw-bold">BELUM LUNAS</div>
                                    <small>Belum ada pembayaran yang diterima.</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Status & Info -->
        <div class="col-lg-4">
            <!-- Status Action Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        @switch($rental->status)
                            @case('sedang_disewa')
                                <div class="badge bg-primary fs-6 px-3 py-2 rounded-pill">Sedang Disewa</div>
                                @break
                            @case('menunggu_konfirmasi')
                                <div class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">Menunggu Konfirmasi</div>
                                @break
                            @case('selesai')
                                <div class="badge bg-success fs-6 px-3 py-2 rounded-pill">Selesai</div>
                                @break
                            @case('cancelled')
                                <div class="badge bg-danger fs-6 px-3 py-2 rounded-pill">Dibatalkan</div>
                                @break
                            @default
                                <div class="badge bg-secondary fs-6 px-3 py-2 rounded-pill">{{ ucfirst($rental->status) }}</div>
                        @endswitch
                    </div>

                    @if($rental->status == 'menunggu_konfirmasi')
                        <div class="alert alert-warning text-start mb-3 border-0 bg-warning-subtle">
                            <small><i class="bi bi-info-circle me-1"></i> Pelanggan telah mengajukan pengembalian. Cek barang sebelum konfirmasi.</small>
                        </div>
                        <form method="POST" action="{{ route('kasir.rentals.confirm-return', $rental) }}" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-success fw-bold" onclick="return confirm('Pastikan semua barang telah kembali dan dicek. Lanjutkan?')">
                                <i class="bi bi-check-lg me-2"></i>Konfirmasi Pengembalian
                            </button>
                        </form>
                    @elseif($rental->status == 'sedang_disewa')
                         <form method="POST" action="{{ route('kasir.rentals.return', $rental) }}" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-primary fw-bold" onclick="return confirm('Proses pengembalian manual?')">
                                <i class="bi bi-box-arrow-in-down me-2"></i>Proses Pengembalian
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3">
                    <h6 class="mb-0 text-white"><i class="bi bi-person me-2"></i>Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                            {{ substr($rental->customer->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <h6 class="mb-0 text-white">{{ $rental->customer->name ?? 'Guest' }}</h6>
                            <small class="text-muted">{{ $rental->customer->email ?? '-' }}</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Telepon</small>
                        <span class="text-white">{{ $rental->customer->phone ?? '-' }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Alamat</small>
                        <span class="text-white">{{ $rental->customer->address ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3">
                    <h6 class="mb-0 text-white"><i class="bi bi-calendar-event me-2"></i>Waktu Sewa</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Mulai Sewa</small>
                        <div class="d-flex align-items-center text-white">
                            <i class="bi bi-calendar-check me-2 text-primary"></i>
                            {{ $rental->start_at ? \Carbon\Carbon::parse($rental->start_at)->format('d M Y, H:i') : '-' }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Jatuh Tempo</small>
                        <div class="d-flex align-items-center text-white">
                            <i class="bi bi-calendar-x me-2 text-danger"></i>
                            {{ $rental->due_at ? \Carbon\Carbon::parse($rental->due_at)->format('d M Y, H:i') : '-' }}
                        </div>
                    </div>
                    @if($rental->returned_at)
                        <div class="pt-3 border-top border-secondary border-opacity-25">
                            <small class="text-muted d-block">Dikembalikan</small>
                            <div class="d-flex align-items-center text-success">
                                <i class="bi bi-check-all me-2"></i>
                                {{ \Carbon\Carbon::parse($rental->returned_at)->format('d M Y, H:i') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

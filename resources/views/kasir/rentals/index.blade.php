@extends('layouts.dashboard')

@section('sidebar_menu')
    @include('kasir.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-receipt me-2 text-primary icon-hover"></i><span class="gradient-text">Daftar Rental</span></h4>
                    <p class="mb-0 text-muted small">Kelola semua transaksi penyewaan</p>
                </div>
                <div>
                    <a href="{{ route('kasir.rentals.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Buat Rental
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('status'))
        <div class="alert alert-success border-0 bg-success-subtle text-success mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4 d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Rentals Table -->
    <div class="card card-hover-lift">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Dibayar</th>
                        <th>Pembayaran</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentals as $r)
                    <tr class="{{ $r->status === 'menunggu_konfirmasi' ? 'table-warning' : '' }}">
                        <td class="fw-bold text-white">{{ $r->kode ?? '#'.$r->id }}</td>
                        <td class="text-white">{{ optional($r->customer)->name }}</td>
                        <td>
                            @php
                              $statusBadge = match($r->status) {
                                'pending' => ['class' => 'bg-warning-subtle', 'text' => 'Menunggu Pembayaran'],
                                'sedang_disewa' => ['class' => 'bg-success-subtle', 'text' => 'Sedang Disewa'],
                                'menunggu_konfirmasi' => ['class' => 'bg-info-subtle', 'text' => '⚠️ Menunggu Konfirmasi'],
                                'selesai' => ['class' => 'bg-primary-subtle', 'text' => 'Selesai'],
                                'cancelled' => ['class' => 'bg-danger-subtle', 'text' => 'Dibatalkan'],
                                default => ['class' => 'bg-secondary-subtle', 'text' => ucfirst($r->status)]
                              };
                            @endphp
                            <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['text'] }}</span>
                        </td>
                        <td class="text-white fw-bold">Rp {{ number_format($r->total, 0, ',', '.') }}</td>
                        <td class="text-white">Rp {{ number_format($r->paid ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @if($r->paid >= $r->total)
                                <span class="badge bg-success-subtle"><i class="bi bi-check-circle-fill me-1"></i> LUNAS</span>
                            @elseif($r->paid > 0)
                                <span class="badge bg-warning-subtle"><i class="bi bi-exclamation-circle me-1"></i> KURANG</span>
                            @else
                                <span class="badge bg-danger-subtle"><i class="bi bi-x-circle-fill me-1"></i> BELUM</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $r->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('kasir.rentals.show', $r) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted opacity-75">
                                <i class="bi bi-inbox display-1 mb-3 d-block"></i>
                                <h5 class="fw-bold">Tidak ada data rental</h5>
                                <p class="mb-0">Belum ada transaksi rental yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($rentals->hasPages())
            <div class="card-footer bg-transparent border-top border-secondary py-3">
                {{ $rentals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

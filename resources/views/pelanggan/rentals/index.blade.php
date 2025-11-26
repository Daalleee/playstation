@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header & Filter -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
                <h4 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary icon-hover"></i><span class="gradient-text">Riwayat Penyewaan Saya</span></h4>
            </div>

            @if(session('status'))
                <div class="alert alert-success bg-success-subtle border-success text-success mb-4">{{ session('status') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger bg-danger-subtle border-danger text-danger mb-4">{{ session('error') }}</div>
            @endif

            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase fw-bold">Status</label>
                    <select name="status" class="form-select bg-dark text-light border-secondary">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Pembayaran</option>
                        <option value="sedang_disewa">Sedang Disewa</option>
                        <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                        <option value="selesai">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase fw-bold">Tanggal</label>
                    <input type="date" name="date" class="form-control bg-dark text-light border-secondary" />
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Cari Riwayat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control bg-dark text-light border-secondary" placeholder="ID Transaksi...">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Rental List -->
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Durasi</th>
                        <th>Item Disewa</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentals as $rental)
                        <tr>
                            <td><span class="font-monospace text-muted">{{ $rental->kode ?? '#'.$rental->id }}</span></td>
                            <td>{{ $rental->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $start = \Carbon\Carbon::parse($rental->start_at);
                                    $end = \Carbon\Carbon::parse($rental->due_at);
                                    $diff = $start->diffInDays($end);
                                    if ($diff == 0) {
                                        $diff = $start->diffInHours($end);
                                        $duration = $diff . ' Jam';
                                    } else {
                                        $duration = $diff . ' Hari';
                                    }
                                @endphp
                                <span class="badge bg-secondary-subtle">{{ $duration }}</span>
                            </td>
                            <td>
                                @foreach($rental->items->take(2) as $item)
                                    @php
                                        $itemName = 'Item';
                                        if($item->rentable) {
                                            $itemName = $item->rentable->name ?? $item->rentable->nama ?? $item->rentable->judul ?? 'Item';
                                        }
                                    @endphp
                                    <div class="text-white small">{{ $itemName }}</div>
                                @endforeach
                                @if($rental->items->count() > 2)
                                    <small class="text-muted">+{{ $rental->items->count() - 2 }} lainnya</small>
                                @endif
                            </td>
                            <td class="text-secondary fw-bold">Rp {{ number_format($rental->total, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusClass = match($rental->status) {
                                        'pending' => 'bg-warning-subtle',
                                        'sedang_disewa' => 'bg-primary-subtle',
                                        'menunggu_konfirmasi' => 'bg-info-subtle',
                                        'selesai' => 'bg-success-subtle',
                                        'cancelled' => 'bg-danger-subtle',
                                        default => 'bg-secondary-subtle'
                                    };
                                    $statusText = match($rental->status) {
                                        'pending' => 'Menunggu Pembayaran',
                                        'sedang_disewa' => 'Sedang Disewa',
                                        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                        'selesai' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default => ucfirst($rental->status)
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                @if($rental->paid >= $rental->total)
                                    <span class="badge bg-success text-white"><i class="bi bi-check-lg me-1"></i>LUNAS</span>
                                @elseif($rental->paid > 0)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i>KURANG</span>
                                @else
                                    <span class="badge bg-danger text-white"><i class="bi bi-x-lg me-1"></i>BELUM</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pelanggan.rentals.show', $rental) }}" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-clock-history fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada riwayat penyewaan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-transparent py-3">
            {{ $rentals->links() }}
        </div>
    </div>
</div>
@endsection
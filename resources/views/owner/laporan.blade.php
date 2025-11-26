@extends('pemilik.layout')
@section('title','Laporan Transaksi')
@section('owner_content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-1">Laporan Transaksi</h2>
            <p class="text-muted mb-0">Riwayat lengkap penyewaan dan status transaksi.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('pemilik.laporan.export') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="format" value="xlsx">
                <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4" style="background: rgba(30, 41, 59, 0.7); border: 1px solid var(--card-border);">
        <div class="card-body">
            <form action="{{ route('pemilik.laporan_transaksi') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control bg-dark text-white border-secondary" value="{{ request('dari') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control bg-dark text-white border-secondary" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter me-2"></i>Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-10">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-semibold">ID</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Pelanggan</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Item Disewa</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Durasi</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Total</th>
                            <th class="px-4 py-3 text-muted fw-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $rental)
                            <tr>
                                <td class="px-4 py-3 font-monospace text-muted">#{{ $rental->kode ?? $rental->id }}</td>
                                <td class="px-4 py-3 text-white">
                                    {{ $rental->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-white">
                                    {{ $rental->customer->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-muted small">
                                    @foreach($rental->items as $item)
                                        <div>
                                            @if($item->rentable_type === 'App\\Models\\UnitPS')
                                                <i class="bi bi-controller me-1"></i>
                                            @elseif($item->rentable_type === 'App\\Models\\Game')
                                                <i class="bi bi-disc me-1"></i>
                                            @elseif($item->rentable_type === 'App\\Models\\Accessory')
                                                <i class="bi bi-headset me-1"></i>
                                            @endif
                                            {{ $item->rentable->nama ?? $item->rentable->judul ?? 'Item' }}
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-muted">
                                    {{ $rental->durasi }} Jam
                                </td>
                                <td class="px-4 py-3 fw-bold text-success">
                                    Rp {{ number_format($rental->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    @php $st = $rental->status; @endphp
                                    <span class="badge rounded-pill {{ $st==='selesai' || $st==='paid' ? 'bg-success-subtle text-success' : ($st==='active' || $st==='sedang_disewa' ? 'bg-primary-subtle text-primary' : ($st==='menunggu_konfirmasi' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $st)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Tidak ada data transaksi yang ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

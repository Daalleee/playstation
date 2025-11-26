@extends('layouts.ecommerce')

@section('title', 'Rental History - PlayStation')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="sedang_disewa" {{ request('status') == 'sedang_disewa' ? 'selected' : '' }}>Sedang Disewa</option>
                                <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}" />
                        </div>
                        <div class="col-md-4 position-relative">
                            <label class="form-label">Cari Rental</label>
                            <div class="position-relative">
                                <input type="text" name="q" placeholder="Cari rental..." class="form-control" value="{{ request('q') }}" onkeypress="handleRentalSearchKeyPress(event, this.form)">
                                <button class="search-icon-btn position-absolute top-50 start-0 translate-middle-y border-0 bg-transparent ps-3" type="submit" style="margin-left: 0.5rem; z-index: 5;">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <style>
                            .form-control {
                                padding-left: 3rem !important;
                            }
                        </style>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary w-100" type="submit">Cari</button>
                        </div>
                    </div>
                    <script>
                        function handleRentalSearchKeyPress(event, form) {
                            if (event.key === 'Enter') {
                                event.preventDefault();
                                form.submit();
                            }
                        }
                    </script>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Item</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rentals as $rental)
                                    <tr>
                                        <td><strong>{{ $rental->kode ?? '#'.$rental->id }}</strong></td>
                                        <td>{{ $rental->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @foreach($rental->items->take(2) as $item)
                                                @php
                                                    $itemName = 'Item';
                                                    if($item->rentable) {
                                                        $itemName = $item->rentable->name ?? $item->rentable->nama ?? $item->rentable->judul ?? 'Item';
                                                    }
                                                @endphp
                                                <div>{{ $itemName }}</div>
                                            @endforeach
                                            @if($rental->items->count() > 2)
                                                <small class="text-muted">+{{ $rental->items->count() - 2 }} lainnya</small>
                                            @endif
                                        </td>
                                        <td><strong>Rp {{ number_format($rental->total, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @php
                                                $statusBadge = match($rental->status) {
                                                    'pending' => ['class' => 'warning', 'text' => 'Menunggu Pembayaran'],
                                                    'sedang_disewa' => ['class' => 'success', 'text' => 'Sedang Disewa'],
                                                    'menunggu_konfirmasi' => ['class' => 'info', 'text' => 'Menunggu Konfirmasi'],
                                                    'selesai' => ['class' => 'success', 'text' => 'Selesai'],
                                                    'cancelled' => ['class' => 'danger', 'text' => 'Dibatalkan'],
                                                    default => ['class' => 'warning', 'text' => ucfirst($rental->status)]
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusBadge['class'] }}">{{ $statusBadge['text'] }}</span>
                                        </td>
                                        <td>
                                            @if($rental->paid >= $rental->total)
                                                <span class="badge bg-success">LUNAS</span>
                                            @elseif($rental->paid > 0)
                                                <span class="badge bg-warning">KURANG</span>
                                            @else
                                                <span class="badge bg-danger"> Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pelanggan.rentals.show', $rental) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="bi bi-clock-history fs-1 text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum ada riwayat rental</h5>
                                            <p class="text-muted">Mulai rental pertama Anda dengan menyewa PlayStation, game, atau aksesoris</p>
                                            <a href="{{ route('dashboard.pelanggan') }}" class="btn btn-primary">
                                                <i class="bi bi-playstation me-1"></i>Sewa Sekarang
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
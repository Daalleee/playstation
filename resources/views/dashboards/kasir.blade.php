@extends('kasir.layout')

@section('kasir_content')
<div class="container-fluid">
    @if(session('impersonate_admin_id'))
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div class="flex-grow-1">
                Anda sedang login sebagai Kasir (Impersonation).
            </div>
            <form action="{{ route('admin.impersonate.leave') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm fw-bold">
                    <i class="bi bi-arrow-return-left me-1"></i> Kembali ke Admin
                </button>
            </form>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Belum Lunas</h6>
                            <h2 class="my-2">{{ $unpaidCount }}</h2>
                            <small class="text-white-50">Transaksi perlu pembayaran</small>
                        </div>
                        <i class="bi bi-cash-coin fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Sedang Disewa</h6>
                            <h2 class="my-2">{{ $activeCount }}</h2>
                            <small class="text-white-50">Unit/Game sedang keluar</small>
                        </div>
                        <i class="bi bi-controller fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Selesai Hari Ini</h6>
                            <h2 class="my-2">{{ $completedTodayCount }}</h2>
                            <small class="text-white-50">Transaksi dikembalikan hari ini</small>
                        </div>
                        <i class="bi bi-check-circle-fill fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-white"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Transaksi</h5>
            <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-sm btn-primary rounded-pill">
                <i class="bi bi-plus-lg me-1"></i>Transaksi Baru
            </a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Unit/Game</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentals as $r)
                        <tr>
                            <td><span class="font-monospace text-muted">#{{ $r->kode ?? $r->id }}</span></td>
                            <td class="fw-bold">{{ $r->customer->name ?? '-' }}</td>
                            <td>
                                @php $names = [];
                                    foreach ($r->items as $it) {
                                        $names[] = ($it->rentable->nama ?? $it->rentable->judul ?? $it->rentable->name ?? '-') . ( $it->quantity>1 ? ' x'.$it->quantity : '' );
                                    }
                                @endphp
                                <div class="text-truncate" style="max-width: 250px;" title="{{ implode(', ', $names) }}">
                                    {{ implode(', ', $names) }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $start = \Carbon\Carbon::parse($r->start_at);
                                    $end = \Carbon\Carbon::parse($r->due_at);
                                    $diff = $start && $end ? $start->diffForHumans($end, [ 'parts'=>2, 'short'=>true, 'syntax'=>\Carbon\CarbonInterface::DIFF_ABSOLUTE ]) : '-';
                                @endphp
                                <span class="badge bg-secondary-subtle">{{ $diff }}</span>
                            </td>
                            <td>
                                @switch($r->status)
                                    @case('sedang_disewa')<span class="badge bg-primary-subtle">Sedang Disewa</span>@break
                                    @case('menunggu_konfirmasi')<span class="badge bg-warning-subtle">Menunggu Konfirmasi</span>@break
                                    @case('selesai')<span class="badge bg-success-subtle">Selesai</span>@break
                                    @case('cancelled')<span class="badge bg-danger-subtle">Dibatalkan</span>@break
                                    @default <span class="badge bg-secondary-subtle">{{ ucfirst($r->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kasir.transaksi.show', $r) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($r->status == 'menunggu_konfirmasi')
                                        <a href="{{ route('kasir.transaksi.show', $r) }}" class="btn btn-sm btn-warning text-dark" title="Konfirmasi Pengembalian">
                                            <i class="bi bi-check-circle"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-transparent py-3">
            <div class="d-flex justify-content-center">
                {{ $rentals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

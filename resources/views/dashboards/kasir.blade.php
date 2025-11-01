@extends('kasir.layout')
@section('title','Beranda Kasir')
@section('kasir_content')
    @if(session('impersonate_admin_id'))
        <form action="{{ route('admin.impersonate.leave') }}" method="POST" class="mb-2">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-arrow-return-left me-1"></i> Kembali ke Admin</button>
        </form>
    @endif

    <div class="text-center mb-3">
        <h1 class="h3">Beranda Kasir</h1>
    </div>

    <div class="card p-3">
        <h6 class="mb-3 text-light">Transaksi Aktif</h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Unit/Game</th>
                        <th>Durasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeRentals as $r)
                        <tr>
                            <td>{{ $r->kode ?? $r->id }}</td>
                            <td>{{ $r->customer->name ?? '-' }}</td>
                            <td>
                                @php $names = [];
                                    foreach ($r->items as $it) {
                                        $names[] = ($it->rentable->nama ?? $it->rentable->judul ?? $it->rentable->name ?? '-') . ( $it->quantity>1 ? ' x'.$it->quantity : '' );
                                    }
                                @endphp
                                {{ implode(', ', $names) }}
                            </td>
                            <td>
                                @php
                                    $start = \Carbon\Carbon::parse($r->start_at);
                                    $end = \Carbon\Carbon::parse($r->due_at);
                                    $diff = $start && $end ? $start->diffForHumans($end, [ 'parts'=>2, 'short'=>true, 'syntax'=>\Carbon\CarbonInterface::DIFF_ABSOLUTE ]) : '-';
                                @endphp
                                {{ $diff }}
                            </td>
                            <td>
                                @switch($r->status)
                                    @case('paid')<span class="badge text-bg-success">Dibayar</span>@break
                                    @case('active')<span class="badge text-bg-primary">Aktif</span>@break
                                    @default <span class="badge text-bg-secondary">{{ ucfirst($r->status) }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada transaksi aktif</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $activeRentals->links() }}
        </div>
    </div>
@endsection

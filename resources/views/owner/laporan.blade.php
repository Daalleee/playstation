@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Laporan Transaksi</h1>
    <a href="{{ route('dashboard.pemilik') }}" style="background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;margin-bottom:1rem;display:inline-block;">&larr; Kembali ke Dashboard</a>
    <div class="mb-2">
        <strong>Filter berdasarkan rentang tanggal (Tgl Sewa):</strong>
    </div>
    <form method="GET" action="" class="mb-3 d-flex gap-2 flex-wrap align-items-end">
        <div>
            <label for="dari" class="form-label mb-1">Dari Tanggal</label>
            <input type="date" id="dari" name="dari" value="{{ request('dari') }}" class="form-control w-auto" placeholder="Dari">
        </div>
        <div>
            <label for="sampai" class="form-label mb-1">Sampai Tanggal</label>
            <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}" class="form-control w-auto" placeholder="Sampai">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Cari</button>
        <a href="?" class="btn btn-outline-secondary mt-3">Reset Filter</a>
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('pemilik.laporan.export', ['format' => 'xlsx', 'dari' => request('dari'), 'sampai' => request('sampai')]) }}" class="btn btn-success">Download Excel</a>
            <a href="{{ route('pemilik.laporan.export', ['format' => 'csv', 'dari' => request('dari'), 'sampai' => request('sampai')]) }}" class="btn btn-secondary">Download CSV</a>
        </div>
    </form>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>No</th>
            <th>Tgl Sewa</th>
            <th>Tgl Kembali</th>
            <th>Pelanggan</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Alamat</th>
            <th>Total</th>
            <th>Status</th>
            <th>Detail Sewa</th>
        </tr>
        </thead>
        <tbody>
        @if (count($rentals) === 0)
        <tr>
            <td colspan="10" class="text-center text-muted">Tidak ada laporan.</td>
        </tr>
        @endif
        @foreach ($rentals as $rental)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rental->start_at ? $rental->start_at->format('d/m/Y') : '-' }}</td>
                <td>{{ $rental->due_at ? $rental->due_at->format('d/m/Y') : '-' }}</td>
                <td>{{ $rental->customer ? $rental->customer->name : '-' }}</td>
                <td>{{ $rental->customer ? $rental->customer->email : '-' }}</td>
                <td>{{ $rental->customer ? $rental->customer->phone : '-' }}</td>
                <td>{{ $rental->customer ? $rental->customer->address : '-' }}</td>
                <td>Rp{{ number_format($rental->total,0,',','.') }}</td>
                <td>
                    @if($rental->status == 'returned')
                        <span style="background: #6c757d; color: #fff; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Dikembalikan</span>
                    @else
                        <span style="background: #ffc107; color: #000; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Menunggu</span>
                    @endif
                </td>
                <td>
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Nama/Judul</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($rental->items as $item)
                            <tr>
                                <td>
                                  @if ($item->rentable_type === 'App\\Models\\UnitPS')
                                    Unit PS
                                  @elseif ($item->rentable_type === 'App\\Models\\Game')
                                    Game
                                  @elseif ($item->rentable_type === 'App\\Models\\Accessory')
                                    Aksesoris
                                  @else
                                    -
                                  @endif
                                </td>
                                <td>
                                  @if ($item->rentable)
                                    {{ $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? '-' }}
                                  @else
                                    -
                                  @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

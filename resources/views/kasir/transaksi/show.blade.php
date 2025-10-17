@extends('kasir.layout')
@section('title','Detail Transaksi - Kasir')
@section('kasir_content')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h5 m-0">Detail Transaksi #{{ $rental->kode ?? $rental->id }}</h1>
        <div>
            <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <div class="card p-3 mb-3">
        <div class="row g-3">
            <div class="col-sm-6 col-md-3">
                <div class="text-muted">Status</div>
                <div>
                    @switch($rental->status)
                        @case('pending')
                            <span class="badge text-bg-warning text-dark">Menunggu</span>
                            @break
                        @case('paid')
                            <span class="badge text-bg-success">Dibayar</span>
                            @break
                        @case('active')
                            <span class="badge text-bg-primary">Aktif</span>
                            @break
                        @case('returned')
                            <span class="badge text-bg-secondary">Dikembalikan</span>
                            @break
                        @case('cancelled')
                            <span class="badge text-bg-danger">Dibatalkan</span>
                            @break
                        @default
                            <span class="badge text-bg-dark">{{ ucfirst($rental->status) }}</span>
                    @endswitch
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="text-muted">Pelanggan</div>
                <div class="fw-semibold">{{ $rental->customer->name ?? '-' }}</div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="text-muted">Tanggal Sewa</div>
                <div>{{ $rental->start_at }}</div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="text-muted">Tanggal Kembali</div>
                <div>{{ $rental->due_at }}</div>
            </div>
        </div>

        @if($rental->status === 'paid')
            <form method="POST" action="{{ route('kasir.transaksi.aktifkan', $rental) }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-primary"><i class="bi bi-play-circle me-1"></i> Aktifkan Sewa</button>
            </form>
        @endif
    </div>

    <div class="card p-3">
        <h6 class="mb-3 text-light">Item Disewa</h6>
        <form method="POST" action="{{ route('kasir.transaksi.pengembalian', $rental) }}" class="mb-0">
            @csrf
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr><th>Nama</th><th>Jenis</th><th>Jumlah</th><th>Kondisi Kembali</th><th>Kembalikan?</th></tr>
                    </thead>
                    <tbody>
                        @foreach($rental->items as $item)
                        <tr>
                            <td>{{ $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name }}</td>
                            <td>{{ class_basename($item->rentable_type) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                <select name="kondisi[{{ $item->id }}]" class="form-select form-select-sm">
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </td>
                            <td><input type="checkbox" name="items[{{ $item->id }}]" value="1" checked></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle me-1"></i> Konfirmasi Pengembalian</button>
            </div>
        </form>
    </div>
@endsection

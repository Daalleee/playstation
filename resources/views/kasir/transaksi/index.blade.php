@include('kasir.partials.nav')
<h1>Cari Transaksi Penyewaan</h1>
<a href="{{ route('dashboard.kasir') }}" style="background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;margin-bottom:1rem;display:inline-block;">&larr; Kembali ke Dashboard</a>
@if(session('status'))
    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        {{ session('status') }}
    </div>
@endif
<form method="GET" action="">
    <label>Kode Transaksi:
        <input type="text" name="rental_kode" value="{{ request('rental_kode') }}">
    </label>
    <button type="submit">Cari</button>
</form>
@if(isset($rental) && $rental)
    @if($rental->status == 'returned')
        <div style="background: #e2e3e5; color: #383d41; padding: 1rem; border-radius: 4px; margin-top: 1rem;">
            Barang pada transaksi kode <b>{{ $rental->kode ?? $rental->id }}</b> sudah dikembalikan oleh pelanggan!
        </div>
    @else
        <hr>
        <h2>Detail Transaksi #{{ $rental->kode ?? $rental->id }}</h2>
        <p>Status: <b>{{ ucfirst($rental->status) }}</b></p>
        <a href="{{ route('kasir.transaksi.show', $rental) }}">Lihat Detail & Pengembalian</a>
    @endif
@endif
<hr>
<h2>Daftar Semua Transaksi</h2>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%">
    <thead>
        <tr>
            <th>Kode Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>Status</th>
            <th>Total</th>
            <th>Lihat</th>
        </tr>
    </thead>
    <tbody>
    @foreach($rentals as $r)
        <tr>
            <td>{{ $r->kode ?? $r->id }}</td>
            <td>{{ $r->customer ? $r->customer->name : '-' }}</td>
            <td>
                @switch($r->status)
                    @case('pending')
                        <span style="background: #ffc107; color: #000; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Menunggu Pembayaran</span>
                        @break
                    @case('paid')
                        <span style="background: #198754; color: #fff; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Sudah Dibayar</span>
                        @break
                    @case('active')
                        <span style="background: #0d6efd; color: #fff; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Aktif (Sedang Disewa)</span>
                        @break
                    @case('returned')
                        <span style="background: #6c757d; color: #fff; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Dikembalikan</span>
                        @break
                    @case('cancelled')
                        <span style="background: #dc3545; color: #fff; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Dibatalkan</span>
                        @break
                    @default
                        <span>{{ ucfirst($r->status) }}</span>
                @endswitch
            </td>
            <td>Rp{{ number_format($r->total,0,',','.') }}</td>
            <td><a href="{{ route('kasir.transaksi.show', $r) }}">Detail & Pengembalian</a></td>
        </tr>
    @endforeach
    </tbody>
</table>

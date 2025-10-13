@include('kasir.partials.nav')
<h1>Detail Transaksi #{{ $rental->kode ?? $rental->id }}</h1>
<p>Status: 
    @switch($rental->status)
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
            <span>{{ ucfirst($rental->status) }}</span>
    @endswitch
</p>
@if($rental->status === 'paid')
    <form method="POST" action="{{ route('kasir.transaksi.aktifkan', $rental) }}" style="margin-bottom:1rem;">
        @csrf
        <button type="submit" style="background: #0d6efd; color: #fff; padding: 0.5rem 1.5rem; border: none; border-radius: 5px;">Aktifkan Sewa (Serah terima barang)</button>
    </form>
@endif
<p>Pelanggan: {{ $rental->customer->name ?? '-' }}</p>
<p>Tanggal Sewa: {{ $rental->start_at }}</p>
<p>Tanggal Kembali: {{ $rental->due_at }}</p>

<h2>Item Disewa</h2>
<form method="POST" action="{{ route('kasir.transaksi.pengembalian', $rental) }}">
    @csrf
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%;margin-bottom:2rem;">
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
                    <select name="kondisi[{{ $item->id }}]">
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </td>
                <td><input type="checkbox" name="items[{{ $item->id }}]" value="1" checked></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <button type="submit" style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px;">Konfirmasi Pengembalian</button>
</form>
<a href="{{ route('kasir.transaksi.index') }}" style="margin-top:1rem;display:inline-block;margin-right:1rem;">&larr; Kembali ke Pencarian</a>
<a href="{{ route('dashboard.kasir') }}" style="margin-top:1rem;display:inline-block;background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;">Kembali ke Dashboard</a>

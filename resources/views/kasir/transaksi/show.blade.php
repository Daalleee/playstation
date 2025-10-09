@include('kasir.partials.nav')
<h1>Detail Transaksi #{{ $rental->id }}</h1>
<p>Status: <b>{{ ucfirst($rental->status) }}</b></p>
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
<a href="{{ route('kasir.transaksi.index') }}" style="margin-top:1rem;display:inline-block;">Kembali ke Pencarian</a>

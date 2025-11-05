@include('kasir.partials.nav')
<h1>Detail Rental {{ $rental->kode ?? '#'.$rental->id }}</h1>

@if(session('status'))
    <div style="padding: 1rem; background: #28a745; color: white; border-radius: 0.25rem; margin-bottom: 1rem;">{{ session('status') }}</div>
@endif

@if(session('error'))
    <div style="padding: 1rem; background: #dc3545; color: white; border-radius: 0.25rem; margin-bottom: 1rem;">{{ session('error') }}</div>
@endif

<div style="background: #f8f9fa; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
    <p><strong>Pelanggan:</strong> {{ optional($rental->customer)->name }}</p>
    <p><strong>Email:</strong> {{ optional($rental->customer)->email }}</p>
    <p><strong>Telepon:</strong> {{ optional($rental->customer)->phone ?? '-' }}</p>
    <p><strong>Alamat:</strong> {{ optional($rental->customer)->address ?? '-' }}</p>
    <p><strong>Status:</strong> 
        @php
          $statusText = match($rental->status) {
            'pending' => 'Menunggu Pembayaran',
            'sedang_disewa' => 'Sedang Disewa',
            'menunggu_konfirmasi' => '⚠️ Menunggu Konfirmasi Pengembalian',
            'selesai' => '✅ Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($rental->status)
          };
        @endphp
        <strong>{{ $statusText }}</strong>
    </p>
    <p><strong>Tanggal Sewa:</strong> {{ $rental->start_at ? \Carbon\Carbon::parse($rental->start_at)->format('d/m/Y') : '-' }}</p>
    <p><strong>Tanggal Kembali:</strong> {{ $rental->due_at ? \Carbon\Carbon::parse($rental->due_at)->format('d/m/Y') : '-' }}</p>
    @if($rental->returned_at)
    <p><strong>Dikembalikan pada:</strong> {{ \Carbon\Carbon::parse($rental->returned_at)->format('d/m/Y H:i') }}</p>
    @endif
    <p><strong>Total:</strong> Rp {{ number_format($rental->total, 0, ',', '.') }}</p>
    <p><strong>Dibayar:</strong> Rp {{ number_format($rental->paid ?? 0, 0, ',', '.') }}</p>
</div>

<h3>Item yang Disewa</h3>
<table border="1" cellpadding="6" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 1rem;">
    <thead>
        <tr style="background: #f8f9fa;">
            <th>Item</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rental->items as $it)
        <tr>
            <td>
                @php
                    $itemName = 'Item';
                    if($it->rentable) {
                        $itemName = $it->rentable->name ?? $it->rentable->nama ?? $it->rentable->judul ?? 'Item';
                    }
                @endphp
                {{ $itemName }} ({{ class_basename($it->rentable_type) }})
            </td>
            <td>{{ $it->quantity }}</td>
            <td>Rp {{ number_format($it->price, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($it->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@if($rental->status === 'menunggu_konfirmasi')
<div style="background: #fff3cd; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem; border-left: 4px solid #ffc107;">
    <h4 style="margin-top: 0;">⚠️ Pengembalian Menunggu Konfirmasi</h4>
    <p>Pelanggan telah mengajukan pengembalian. Silakan periksa kondisi barang dan konfirmasi pengembalian.</p>
    <form method="POST" action="{{ route('kasir.rentals.confirm-return', $rental) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengkonfirmasi pengembalian ini? Stok akan dikembalikan.')">
        @csrf
        <button type="submit" style="padding: 0.5rem 1rem; background: #28a745; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-weight: bold;">
            ✓ Konfirmasi Pengembalian
        </button>
    </form>
</div>
@endif

<div style="margin-top: 1rem;">
    <a href="{{ route('kasir.rentals.index') }}" style="padding: 0.5rem 1rem; background: #6c757d; color: white; text-decoration: none; border-radius: 0.25rem;">Kembali ke Daftar</a>
</div>


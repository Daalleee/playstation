@include('kasir.partials.nav')
<h1>Daftar Rental</h1>
<a href="{{ route('kasir.rentals.create') }}" style="display: inline-block; padding: 0.5rem 1rem; background: #007bff; color: white; text-decoration: none; border-radius: 0.25rem; margin-bottom: 1rem;">Buat Rental</a>

@if(session('status'))
    <div style="padding: 1rem; background: #28a745; color: white; border-radius: 0.25rem; margin-bottom: 1rem;">{{ session('status') }}</div>
@endif

@if(session('error'))
    <div style="padding: 1rem; background: #dc3545; color: white; border-radius: 0.25rem; margin-bottom: 1rem;">{{ session('error') }}</div>
@endif

<table border="1" cellpadding="6" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background: #f8f9fa;">
            <th>Kode</th>
            <th>Pelanggan</th>
            <th>Status</th>
            <th>Total</th>
            <th>Dibayar</th>
            <th>Pembayaran</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rentals as $r)
        <tr style="{{ $r->status === 'menunggu_konfirmasi' ? 'background: #fff3cd;' : '' }}">
            <td>{{ $r->kode ?? '#'.$r->id }}</td>
            <td>{{ optional($r->customer)->name }}</td>
            <td>
                @php
                  $statusText = match($r->status) {
                    'pending' => 'Menunggu Pembayaran',
                    'sedang_disewa' => 'Sedang Disewa',
                    'menunggu_konfirmasi' => '⚠️ Menunggu Konfirmasi',
                    'selesai' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => ucfirst($r->status)
                  };
                @endphp
                <strong>{{ $statusText }}</strong>
            </td>
            <td>Rp {{ number_format($r->total, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($r->paid ?? 0, 0, ',', '.') }}</td>
            <td>
                @if($r->paid >= $r->total)
                    <span style="background:#28a745; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; font-weight:700;">✓ LUNAS</span>
                @elseif($r->paid > 0)
                    <span style="background:#ffc107; color:#000; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; font-weight:700;">⚠ KURANG</span>
                @else
                    <span style="background:#dc3545; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; font-weight:700;">✗ BELUM</span>
                @endif
            </td>
            <td>{{ $r->created_at->format('d/m/Y') }}</td>
            <td>
                <a href="{{ route('kasir.rentals.show', $r) }}" style="padding: 0.25rem 0.5rem; background: #007bff; color: white; text-decoration: none; border-radius: 0.25rem;">Detail</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center; padding: 2rem;">Tidak ada data rental</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 1rem;">
    {{ $rentals->links() }}
</div>


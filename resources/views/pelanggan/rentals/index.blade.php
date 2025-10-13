@include('pelanggan.partials.nav')
<h1>Riwayat Penyewaan</h1>
<a href="{{ route('dashboard.pelanggan') }}" style="background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;margin-bottom:1rem;display:inline-block;">&larr; Kembali ke Dashboard</a>

@if(session('status'))
    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        {{ session('status') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        {{ session('error') }}
    </div>
@endif

@if($rentals->count() > 0)
    <div style="margin-top: 2rem;">
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th>ID Rental</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentals as $rental)
                <tr>
                    <td>#{{ $rental->kode ?? $rental->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($rental->start_at)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($rental->due_at)->format('d M Y') }}</td>
                    <td>
                        @if($rental->status == 'returned')
                            <span style="background: #6c757d; color: #fff; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Dikembalikan</span>
                        @else
                            <span style="background: #ffc107; color: #000; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Menunggu</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($rental->total, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('pelanggan.rentals.show', $rental) }}" style="background: #007bff; color: white; padding: 0.25rem 0.5rem; text-decoration: none; border-radius: 3px;">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 2rem;">
            {{ $rentals->links() }}
        </div>
    </div>
@else
    <div style="text-align: center; margin-top: 3rem;">
        <h3>Belum Ada Riwayat Penyewaan</h3>
        <p>Anda belum pernah melakukan penyewaan.</p>
        <a href="{{ route('pelanggan.unitps.index') }}" style="background: #007bff; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;">
            Mulai Berbelanja
        </a>
    </div>
@endif

@include('pelanggan.partials.nav')
<h1>Detail Penyewaan #{{ $rental->id }}</h1>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Rental Info -->
    <div>
        <h2>Informasi Penyewaan</h2>
        <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem;">
            <div style="margin-bottom: 1rem;">
                <strong>ID Rental:</strong> #{{ $rental->id }}
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Tanggal Sewa:</strong> {{ \Carbon\Carbon::parse($rental->start_at)->format('d M Y') }}
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Tanggal Kembali:</strong> {{ \Carbon\Carbon::parse($rental->due_at)->format('d M Y') }}
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Status:</strong> 
                @if($rental->status == 'returned')
                    <span style="background: #6c757d; color: #fff; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Dikembalikan</span>
                @else
                    <span style="background: #ffc107; color: #000; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Menunggu</span>
                @endif
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Total:</strong> Rp {{ number_format($rental->total, 0, ',', '.') }}
            </div>
            @if($rental->notes)
            <div style="margin-bottom: 1rem;">
                <strong>Catatan:</strong> {{ $rental->notes }}
            </div>
            @endif
            <div style="margin-bottom: 1rem;">
                <strong>Dibuat:</strong> {{ $rental->created_at->format('d M Y H:i') }}
            </div>
        </div>
    </div>

    <!-- Rental Items -->
    <div>
        <h2>Item yang Disewa</h2>
        <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem;">
            @foreach($rental->items as $item)
            <div style="border-bottom: 1px solid #eee; padding: 1rem 0; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                <h4>{{ $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name }}</h4>
                <p><strong>Jenis:</strong> {{ class_basename($item->rentable_type) }}</p>
                <p><strong>Jumlah:</strong> {{ $item->quantity }}</p>
                <p><strong>Harga per Unit:</strong> Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                <p><strong>Subtotal:</strong> Rp {{ number_format($item->total, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Payments -->
@if($rental->payments->count() > 0)
<div style="margin-top: 2rem;">
    <h2>Riwayat Pembayaran</h2>
    <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem;">
        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Metode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rental->payments as $payment)
                <tr>
                    <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td>
                        @if($payment->status == 'completed')
                            <span style="background: #28a745; color: #fff; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Lunas</span>
                        @else
                            <span style="background: #ffc107; color: #000; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Payment Section -->
{{-- Bagian pembayaran dihapus --}}

<div style="margin-top: 2rem;">
    <a href="{{ route('pelanggan.rentals.index') }}" style="background: #6c757d; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;">
        Kembali ke Riwayat
    </a>
</div>

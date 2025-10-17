@extends('layouts.app')
@section('content')
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); position:sticky; top:1rem; min-height:calc(100dvh - 2rem); }
  .dash-main{ flex:1; }
  .page-hero{ text-align:center; padding:1rem; }
  .page-hero h2{ font-weight:800; margin:0; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  .grid-2{ display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
  .badge-warn{ background:#ffc107; color:#000; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-grey{ background:#6c757d; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  table.dark{ width:100%; color:#e7e9ff; border-collapse:collapse; }
  table.dark th, table.dark td{ border:1px solid #2f3561; padding:.5rem .6rem; }
  table.dark thead th{ background:#23284a; font-weight:800; }
  .btn-grey{ background:#6c757d; color:#fff; border:none; border-radius:.4rem; padding:.45rem .8rem; text-decoration:none; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ position:static; } .grid-2{ grid-template-columns:1fr; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')
    <main class="dash-main">
      <div class="page-hero">
        <h2>Detail Penyewaan #{{ $rental->id }}</h2>
      </div>

      <div class="grid-2">
    <!-- Rental Info -->
    <div>
        <h2>Informasi Penyewaan</h2>
        <div class="card-dark">
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
                    <span class="badge-grey">Dikembalikan</span>
                @else
                    <span class="badge-warn">Menunggu</span>
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
        <div class="card-dark">
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
    <div class="card-dark">
        <table class="dark">
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
                            <span class="badge-ok" style="background:#28a745; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem;">Lunas</span>
                        @else
                            <span class="badge-warn">Pending</span>
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
    <a href="{{ route('pelanggan.rentals.index') }}" class="btn-grey">Kembali ke Riwayat</a>
  </div>

    </main>
  </div>
</div>
@endsection

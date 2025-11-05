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
  .btn-return{ background:#f39c12; color:#fff; border:none; border-radius:.4rem; padding:.55rem 1rem; font-weight:700; cursor:pointer; }
  .btn-return:hover{ background:#e67e22; }
  .badge-ok{ background:#1e8449; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-success{ background:#28a745; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-danger{ background:#c0392b; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ position:static; } .grid-2{ grid-template-columns:1fr; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')
    <main class="dash-main">
      <div class="page-hero">
        <h2>Detail Penyewaan #{{ $rental->id }}</h2>
      </div>

      @if(session('status'))
        <div class="alert alert-success mb-3" style="background: #28a745; color: white; padding: 1rem; border-radius: 0.5rem;">
          {{ session('status') }}
        </div>
      @endif
      
      @if(session('error'))
        <div class="alert alert-danger mb-3" style="background: #dc3545; color: white; padding: 1rem; border-radius: 0.5rem;">
          {{ session('error') }}
        </div>
      @endif
      
      @if(session('warning'))
        <div class="alert alert-warning mb-3" style="background: #ffc107; color: #000; padding: 1rem; border-radius: 0.5rem;">
          {{ session('warning') }}
        </div>
      @endif

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
                @php
                  $statusBadge = match($rental->status) {
                    'pending' => ['class' => 'badge-warn', 'text' => 'Menunggu Pembayaran'],
                    'sedang_disewa' => ['class' => 'badge-success', 'text' => 'Sedang Disewa'],
                    'menunggu_konfirmasi' => ['class' => 'badge-warn', 'text' => 'Menunggu Konfirmasi Kasir'],
                    'selesai' => ['class' => 'badge-ok', 'text' => 'Selesai'],
                    'cancelled' => ['class' => 'badge-danger', 'text' => 'Dibatalkan'],
                    default => ['class' => 'badge-grey', 'text' => ucfirst($rental->status)]
                  };
                @endphp
                <span class="{{ $statusBadge['class'] }}">{{ $statusBadge['text'] }}</span>
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Total:</strong> Rp {{ number_format($rental->total, 0, ',', '.') }}
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Dibayar:</strong> Rp {{ number_format($rental->paid ?? 0, 0, ',', '.') }}
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Status Pembayaran:</strong> 
                @if($rental->paid >= $rental->total)
                    <span class="badge-success" style="background:#28a745; color:#fff; border-radius:999px; padding:.3rem .8rem; font-size:.9rem; font-weight:700;">✓ LUNAS</span>
                @elseif($rental->paid > 0)
                    <span class="badge-warn" style="background:#ffc107; color:#000; border-radius:999px; padding:.3rem .8rem; font-size:.9rem; font-weight:700;">⚠ KURANG BAYAR</span>
                @else
                    <span class="badge-danger" style="background:#dc3545; color:#fff; border-radius:999px; padding:.3rem .8rem; font-size:.9rem; font-weight:700;">✗ BELUM LUNAS</span>
                @endif
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
            @forelse($rental->items as $item)
            <div style="border-bottom: 1px solid #2f3561; padding: 1rem 0; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                @php
                    $itemName = 'Item Tidak Ditemukan';
                    if($item->rentable) {
                        $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';
                    }
                @endphp
                <h4 style="color: #e7e9ff; margin-bottom: 0.5rem;">{{ $itemName }}</h4>
                <p style="margin: 0.25rem 0;"><strong>Jenis:</strong> {{ class_basename($item->rentable_type) }}</p>
                <p style="margin: 0.25rem 0;"><strong>Jumlah:</strong> {{ $item->quantity }}</p>
                <p style="margin: 0.25rem 0;"><strong>Harga per Unit:</strong> Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                <p style="margin: 0.25rem 0;"><strong>Subtotal:</strong> Rp {{ number_format($item->total, 0, ',', '.') }}</p>
            </div>
            @empty
            <p class="text-center" style="padding: 2rem;">Tidak ada item</p>
            @endforelse
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
                    <td>{{ ucfirst($payment->method ?? 'N/A') }}</td>
                    <td>
                        @php
                            $status = $payment->transaction_status ?? 'pending';
                        @endphp
                        @if(in_array($status, ['settlement', 'capture']))
                            <span class="badge-ok" style="background:#28a745; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem;">Lunas</span>
                        @elseif($status == 'pending')
                            <span class="badge-warn">Menunggu</span>
                        @else
                            <span class="badge-grey">{{ ucfirst($status) }}</span>
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

<div style="margin-top: 2rem; display: flex; gap: 1rem; align-items: center;">
    <a href="{{ route('pelanggan.rentals.index') }}" class="btn-grey">Kembali ke Riwayat</a>
    
    @if($rental->status === 'sedang_disewa')
      <form method="POST" action="{{ route('pelanggan.rentals.return', $rental) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan barang ini? Silakan pastikan barang dalam kondisi baik.')">
        @csrf
        <button type="submit" class="btn-return">
          <i class="bi bi-box-arrow-in-down"></i> Kembalikan Barang
        </button>
      </form>
    @endif
    
    @if($rental->status === 'menunggu_konfirmasi')
      <div class="alert alert-info" style="background: #17a2b8; color: white; padding: 0.75rem 1rem; border-radius: 0.4rem; margin: 0;">
        <i class="bi bi-info-circle"></i> Pengembalian Anda sedang menunggu konfirmasi dari kasir.
      </div>
    @endif
  </div>

    </main>
  </div>
</div>
@endsection

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
  table.dark{ width:100%; color:#e7e9ff; border-collapse:collapse; }
  table.dark th, table.dark td{ border:1px solid #2f3561; padding:.5rem .6rem; }
  table.dark thead th{ background:#23284a; font-weight:800; }
  .badge-ok{ background:#1f9d62; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-warn{ background:#d97a2b; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .btn-detail{ background:#6f7dd6; color:#fff; border:none; padding:.3rem .6rem; border-radius:.4rem; text-decoration:none; }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="page-hero">
        <h2>Riwayat Penyewaan</h2>
      </div>

      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="card-dark">
        <div class="table-responsive">
          <table class="dark">
            <thead>
              <tr>
                <th>ID Transaksi</th>
                <th>Unit/Game</th>
                <th>Tanggal Sewa</th>
                <th>Durasi</th>
                <th>Biaya</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rentals as $rental)
                @php
                  $start = \Carbon\Carbon::parse($rental->start_at);
                  $due = \Carbon\Carbon::parse($rental->due_at);
                  $hours = $start->diffInHours($due);
                  $firstItem = optional($rental->items->first());
                  $itemName = $firstItem->name ?? ($rental->kode ? 'Transaksi '.$rental->kode : 'Item');
                  $st = strtolower($rental->status ?? 'pending');
                @endphp
                <tr>
                  <td>{{ $rental->kode ?? ('TRX'.$rental->id) }}</td>
                  <td>{{ $itemName }}</td>
                  <td>{{ $start->format('Y-m-d') }}</td>
                  <td>{{ $hours }} Jam</td>
                  <td>Rp {{ number_format($rental->total, 0, ',', '.') }}</td>
                  <td><span class="{{ in_array($st,['returned','selesai']) ? 'badge-ok' : 'badge-warn' }}">{{ ucfirst($st) }}</span></td>
                  <td><a href="{{ route('pelanggan.rentals.show', $rental) }}" class="btn-detail">Detail</a></td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">Belum ada riwayat.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ method_exists($rentals,'links') ? $rentals->links() : '' }}
        </div>
      </div>
    </main>
  </div>
</div>
@endsection

@extends('layouts.app')
@section('content')
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); position:sticky; top:1rem; min-height:calc(100dvh - 2rem); }
  .dash-main{ flex:1; }
  .page-hero{ text-align:center; padding:1rem; }
  .page-hero h2{ margin:0; font-weight:800; }
  .grid-2{ display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  .list-item{ border-top:1px solid #2f3561; padding:.8rem 0; }
  .list-item:first-child{ border-top:none; }
  .muted{ color:#cfd3ff; opacity:.9; }
  .input-dark, .select-dark, .textarea-dark{ width:100%; background:#23284a; color:#eef1ff; border:1px solid #2f3561; border-radius:.6rem; padding:.55rem .75rem; }
  .btn-green{ background:#2ecc71; color:#0e1a2f; font-weight:800; border:none; border-radius:.6rem; padding:.7rem 1rem; width:100%; }
  .btn-grey{ background:#6c757d; color:#fff; border:none; border-radius:.6rem; padding:.55rem .9rem; text-decoration:none; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .grid-2{ grid-template-columns:1fr; } .dash-sidebar{ position:static; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="page-hero"><h2>Buat Penyewaan</h2></div>

      @if($errors->any())
        <div class="alert alert-danger">{!! implode('<br>', $errors->all()) !!}</div>
      @endif

      <div class="grid-2">
        <section class="card-dark">
          <h5 class="mb-3">Item yang Akan Disewa</h5>
          @forelse($cartItems as $item)
            <div class="list-item">
              <div class="fw-bold">{{ $item->name }}</div>
              <div class="muted">Jenis: {{ ucfirst($item->type) }}</div>
              <div class="muted">Harga: Rp {{ number_format($item->price, 0, ',', '.') }} {{ $item->price_type == 'per_jam' ? 'per jam' : 'per hari' }}</div>
              <div class="muted">Jumlah: {{ $item->quantity }}</div>
            </div>
          @empty
            <div class="muted">Keranjang kosong.</div>
          @endforelse
          <div class="mt-3">
            <a href="{{ route('pelanggan.cart.index') }}" class="btn-grey">Kembali ke Keranjang</a>
          </div>
        </section>

        <section class="card-dark">
          <h5 class="mb-3">Detail Penyewaan</h5>
          <form method="POST" action="{{ route('pelanggan.rentals.store') }}">
            @csrf
            <div class="mb-3">
              <label for="rental_date" class="form-label">Tanggal Mulai Sewa</label>
              <input type="date" id="rental_date" name="rental_date" value="{{ old('rental_date', date('Y-m-d')) }}" required class="input-dark">
            </div>
            <div class="mb-3">
              <label for="return_date" class="form-label">Tanggal Kembali</label>
              <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}" required class="input-dark">
            </div>
            <div class="mb-3">
              <label for="notes" class="form-label">Catatan (Opsional)</label>
              <textarea id="notes" name="notes" rows="3" class="textarea-dark">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn-green">Buat Penyewaan</button>
          </form>
        </section>
      </div>
    </main>
  </div>
</div>
@endsection

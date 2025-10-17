@extends('layouts.app')
@section('content')
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); position:sticky; top:1rem; min-height:calc(100dvh - 2rem); }
  .dash-main{ flex:1; }
  .page-hero{ text-align:center; padding:1rem; }
  .page-hero h2{ font-weight:800; margin:0; }
  .filter-row{ display:grid; grid-template-columns: repeat(12,1fr); gap:.75rem; margin:0 1rem 1rem; }
  .select-dark, .input-dark{ width:100%; background:#23284a; color:#eef1ff; border:1px solid #2f3561; border-radius:.6rem; padding:.55rem .75rem; }
  .btn-cta{ background:#2ecc71; border:none; color:#0e1a2f; font-weight:800; padding:.55rem 1rem; border-radius:.6rem; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  table.dark{ width:100%; color:#e7e9ff; border-collapse:collapse; }
  table.dark th, table.dark td{ border:1px solid #2f3561; padding:.5rem .6rem; }
  table.dark thead th{ background:#23284a; font-weight:800; }
  .badge-ok{ background:#1f9d62; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-warn{ background:#d97a2b; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .btn-detail{ background:#6f7dd6; color:#fff; border:none; padding:.3rem .6rem; border-radius:.4rem; text-decoration:none; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ position:static; } .filter-row{ grid-template-columns:1fr; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="page-hero">
        <h2>Daftar Unit/Game</h2>
      </div>

      <form method="GET" class="filter-row">
        <div class="col-span-4">
          <label class="mb-1 d-block">Tipe</label>
          <select name="model" class="select-dark">
            <option value="">Semua</option>
            @foreach (['PS3','PS4','PS5'] as $opt)
              <option value="{{ $opt }}" @selected(request('model')===$opt)>{{ $opt }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-span-4">
          <label class="mb-1 d-block">Platform</label>
          <select name="brand" class="select-dark">
            <option value="">Semua</option>
            <option value="Sony" @selected(request('brand')==='Sony')>Sony</option>
          </select>
        </div>
        <div class="col-span-3">
          <label class="mb-1 d-block">Cari</label>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari unit/game" class="input-dark" />
        </div>
        <div class="col-span-1 d-flex align-items-end">
          <button class="btn-cta" type="submit">Cari</button>
        </div>
      </form>

      <div class="card-dark">
        <div class="table-responsive">
          <table class="dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tipe/Platform</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($units as $unit)
                <tr>
                  <td>{{ $unit->id }}</td>
                  <td>{{ $unit->name ?? $unit->nama }}</td>
                  <td>{{ $unit->model }}</td>
                  <td>
                    @php $st = strtolower($unit->status); @endphp
                    <span class="{{ in_array($st,['available','tersedia']) ? 'badge-ok' : 'badge-warn' }}">{{ ucfirst($unit->status) }}</span>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <a href="{{ route('pelanggan.rentals.create') }}" class="btn-detail">Detail</a>
                      <form method="POST" action="{{ route('pelanggan.cart.add') }}">
                        @csrf
                        <input type="hidden" name="type" value="unitps">
                        <input type="hidden" name="id" value="{{ $unit->id }}">
                        <input type="hidden" name="price_type" value="per_jam">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn-cta">Tambah ke Keranjang</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ method_exists($units,'links') ? $units->withQueryString()->links() : '' }}
        </div>
      </div>
    </main>
  </div>
</div>
@endsection

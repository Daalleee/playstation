@extends('layouts.app')
@section('content')
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; height: 100vh; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); height: 100vh; overflow-y: auto; position: sticky; top: 0; }
  .dash-main{ flex:1; overflow-y: auto; padding: 1rem; }
  .page-hero{ text-align:center; padding:1rem; }
  .page-hero h2{ font-weight:800; margin:0; }
  .filter-row{ display:grid; grid-template-columns: 1fr 1fr 2fr auto; gap:1rem; margin:0 1rem 1rem; align-items:end; }
  .select-dark, .input-dark{ width:100%; background:#23284a; color:#eef1ff; border:1px solid #2f3561; border-radius:.6rem; padding:.55rem .75rem; }
  .btn-cta{ background:#2ecc71; border:none; color:#0e1a2f; font-weight:800; padding:.55rem 1rem; border-radius:.6rem; min-width:120px; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  table.dark{ width:100%; color:#e7e9ff; border-collapse:collapse; }
  table.dark th, table.dark td{ border:1px solid #2f3561; padding:.5rem .6rem; }
  table.dark thead th{ background:#23284a; font-weight:800; }
  .badge-ok{ background:#1a7a4f; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-warn{ background:#b8651f; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-danger{ background:#c0392b; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-success{ background:#1e8449; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; font-weight:700; }
  .badge-warning{ background:#d68910; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; font-weight:700; }
  .btn-detail{ background:#5b6bb8; color:#fff; border:none; padding:.3rem .6rem; border-radius:.4rem; text-decoration:none; }
  .btn-cta{ background:#1e8449; border:none; color:#fff; font-weight:800; padding:.55rem 1rem; border-radius:.6rem; cursor:pointer; }
  .btn-cta:hover{ background:#27ae60; }
  .btn-cta:disabled{ background:#7f8c8d; cursor:not-allowed; opacity:0.6; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ flex:0 0 auto; position:static; height: auto; } .dash-main{ height: auto; } .filter-row{ grid-template-columns:1fr; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="page-hero">
        <h2>Daftar Games PlayStation</h2>
      </div>

      <form method="GET" class="filter-row">
        <div>
          <label class="mb-1 d-block fw-bold">Platform</label>
          <select name="platform" class="select-dark">
            <option value="">Semua Platform</option>
            @foreach (['PS3','PS4','PS5'] as $opt)
              <option value="{{ $opt }}" @selected(request('platform')===$opt)>{{ $opt }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="mb-1 d-block fw-bold">Genre</label>
          <input type="text" name="genre" value="{{ request('genre') }}" class="input-dark" placeholder="Genre" />
        </div>
        <div>
          <label class="mb-1 d-block fw-bold">Cari Game</label>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari game" class="input-dark" />
        </div>
        <div>
          <button class="btn-cta w-100" type="submit">Cari</button>
        </div>
      </form>

      <div class="card-dark">
        <div class="table-responsive">
          <table class="dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Platform/Genre</th>
                <th>Gambar</th>
                <th>Stok</th>
                <th>Harga/Hari</th>
                <th>Kondisi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($games as $game)
                <tr>
                  <td>{{ $game->id }}</td>
                  <td>{{ $game->judul }}</td>
                  <td>{{ $game->platform }}<br><small class="text-muted">{{ $game->genre }}</small></td>
                  <td>
                    @if($game->gambar)
                      <img src="{{ asset('storage/' . $game->gambar) }}" alt="{{ $game->judul }}" style="width:50px; height:50px; object-fit:cover;">
                    @else
                      <img src="https://placehold.co/50x50/49497A/FFFFFF?text=No+Image" alt="{{ $game->judul }}" style="width:50px; height:50px; object-fit:cover;">
                    @endif
                  </td>
                  <td>
                    @php 
                      $stok = $game->stok ?? 0;
                      $badgeClass = $stok > 5 ? 'badge-success' : ($stok > 0 ? 'badge-warning' : 'badge-danger');
                    @endphp
                    <span class="{{ $badgeClass }} d-block">{{ $stok }} Unit</span>
                  </td>
                  <td>Rp {{ number_format($game->harga_per_hari, 0, ',', '.') }}</td>
                  <td>
                    @php $kondisi = strtolower($game->kondisi ?? 'baik'); @endphp
                    <span class="{{ $kondisi === 'baik' ? 'badge-success' : ($kondisi === 'rusak' ? 'badge-danger' : 'badge-warning') }} d-block">{{ ucfirst($game->kondisi) }}</span>
                  </td>
                  <td>
                    <div class="d-flex flex-column gap-2">
                      <a href="#" class="btn-detail">Detail</a>
                      <form method="POST" action="{{ route('pelanggan.cart.add') }}">
                        @csrf
                        <input type="hidden" name="type" value="game">
                        <input type="hidden" name="id" value="{{ $game->id }}">
                        <input type="hidden" name="price_type" value="per_hari">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn-cta w-100" {{ $stok <= 0 ? 'disabled' : '' }}>{{ $stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center">Tidak ada game tersedia.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ method_exists($games,'links') ? $games->withQueryString()->links() : '' }}
        </div>
      </div>
    </main>
  </div>
</div>
@endsection
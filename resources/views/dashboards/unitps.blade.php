@extends('layouts.app')
@section('content')

<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; box-shadow:none; }
  .dash-layout{ display:flex; gap:1rem; height: 100vh; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); height: 100vh; overflow-y: auto; position: sticky; top: 0; }
  .dash-logo{ width:100%; display:grid; place-items:center; margin-bottom:1rem; }
  .dash-logo .circle{ width:96px; height:96px; border-radius:50%; background:#0b3d91; display:grid; place-items:center; box-shadow:inset 0 -8px 14px rgba(0,0,0,.25); }
  .dash-menu{ list-style:none; padding:0; margin:1rem 0 0; display:grid; gap:.4rem; }
  .dash-menu a{ display:flex; align-items:center; gap:.6rem; padding:.6rem .8rem; border-radius:.6rem; color:#e9e9ff; text-decoration:none; font-weight:700; background:transparent; }
  .dash-menu a:hover{ background:rgba(255,255,255,.06); }
  .dash-icon{ width:22px; height:22px; display:inline-grid; place-items:center; }
  .dash-main{ flex:1; overflow-y: auto; padding: 1rem; }
  .dash-hero{ text-align:center; padding:2rem 1rem 1rem; }
  .dash-hero h2{ font-weight:800; }
  .dash-hero p{ color:#aeb5e6; margin-bottom:1.5rem; }
  .dash-card{ background:#49497A; border:1px solid #5a5a8a; border-radius:1rem; padding:1rem; text-align:center; height: 100%; display: flex; flex-direction: column; }
  .dash-frame{ background:#23284a; border:1px solid #2f3561; border-radius:.75rem; padding:0; display:flex; align-items:center; justify-content:center; height:220px; overflow:hidden; }
  .card-content{ flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
  .dash-card img{ width:100%; height:100%; object-fit:cover; filter: drop-shadow(0 .75rem 1rem rgba(0,0,0,.35)); }
  .dash-badge{ display:inline-block; margin-top:.75rem; background:#23284a; color:#cfd3ff; padding:.35rem .75rem; border-radius:.5rem; font-weight:700; }
  .dash-pager .btn{ background:#23284a; color:#cfd3ff; border:none; margin:.15rem; }
  .dash-pager .btn.active{ background:#4750c9; color:#fff; }
  .dash-actions .btn{ border:none; }
  .btn-cta{ background:#4750c9; color:#fff; }
  .btn-cta:hover{ background:#5a63e0; color:#fff; }
  .btn-cta:active{ background:#3a43a8; color:#fff; transform: scale(0.98); }
  .text-price{ color: #7bed9f !important; font-weight: bold; } /* Light green color */
  .badge-success{ background:#2ecc71; color:#0e1a2f; font-weight:800; }
  .badge-warning{ background:#f39c12; color:#0e1a2f; font-weight:800; }
  .badge-danger{ background:#e74c3c; color:#fff; font-weight:800; }
  /* horizontal scroll area */
  .dash-scroll{ overflow-x:auto; display:flex; gap:1rem; scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch; padding:0 .5rem; }
  .dash-page{ flex:0 0 100%; scroll-snap-align:center; }
  .dash-scroll::-webkit-scrollbar{ height:8px; }
  .dash-scroll::-webkit-scrollbar-thumb{ background:#2f3561; border-radius:4px; }
  @media (max-width: 991.98px){
    .dash-layout{ flex-direction:column; }
    .dash-sidebar{ flex:0 0 auto; position:static; height: auto; }
    .dash-main{ height: auto; }
  }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="dash-hero">
        <h2>Sewa Unit PlayStation</h2>
        <p>Temukan PlayStation favoritmu untuk pengalaman bermain terbaik!</p>
      </div>

      <!-- Unit PS Section -->
      <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>Unit PlayStation Tersedia</h3>
          <a href="{{ route('pelanggan.unitps.list') }}" class="btn btn-cta">Lihat Semua</a>
        </div>
        @if($unitps->count() > 0)
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 px-2">
            @foreach($unitps as $unit)
              <div class="col">
                <div class="dash-card">
                  <div class="dash-frame">
                    @if($unit->foto)
                      <img src="{{ asset('storage/' . $unit->foto) }}" alt="{{ $unit->nama }}" class="img-fluid">
                    @else
                      <img src="https://placehold.co/300x200/49497A/FFFFFF?text=Unit+PS" alt="{{ $unit->nama }}" class="img-fluid">
                    @endif
                  </div>
                  <div class="card-content">
                    <div class="p-2 flex-grow-1">
                      <h5 class="mb-1 text-center text-white fw-bold">{{ $unit->nama }}</h5>
                      <div class="fw-bold text-price mb-1 text-center mt-2">Rp {{ number_format($unit->harga_per_jam, 0, ',', '.') }}/jam</div>
                      <div class="text-center">Stok: {{ $unit->stok }}</div>
                    </div>
                    <div class="pt-2 mt-auto">
                      <a href="{{ route('pelanggan.rentals.create') }}?type=unitps&id={{ $unit->id }}" class="btn btn-cta w-100">Sewa Unit</a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="col-12 text-center">
            <p>Tidak ada unit PlayStation tersedia saat ini.</p>
          </div>
        @endif
      </section>

    </main>
  </div>
</div>
@endsection
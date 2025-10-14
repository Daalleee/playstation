@extends('layouts.app')
@section('content')

<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; box-shadow:none; }
  .dash-layout{ display:flex; gap:1rem; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); position:sticky; top:1rem; height:fit-content; }
  .dash-logo{ width:100%; display:grid; place-items:center; margin-bottom:1rem; }
  .dash-logo .circle{ width:96px; height:96px; border-radius:50%; background:#0b3d91; display:grid; place-items:center; box-shadow:inset 0 -8px 14px rgba(0,0,0,.25); }
  .dash-menu{ list-style:none; padding:0; margin:1rem 0 0; display:grid; gap:.4rem; }
  .dash-menu a{ display:flex; align-items:center; gap:.6rem; padding:.6rem .8rem; border-radius:.6rem; color:#e9e9ff; text-decoration:none; font-weight:700; background:transparent; }
  .dash-menu a:hover{ background:rgba(255,255,255,.06); }
  .dash-icon{ width:22px; height:22px; display:inline-grid; place-items:center; }
  .dash-main{ flex:1; }
  .dash-hero{ text-align:center; padding:2rem 1rem 1rem; }
  .dash-hero h2{ font-weight:800; }
  .dash-hero p{ color:#aeb5e6; margin-bottom:0; }
  .dash-card{ background:#1f2446; border:none; border-radius:1rem; padding:1rem; text-align:center; }
  .dash-frame{ background:#23284a; border:1px solid #2f3561; border-radius:.75rem; padding:0; display:flex; align-items:center; justify-content:center; height:220px; overflow:hidden; }
  .dash-card img{ width:100%; height:100%; object-fit:cover; filter: drop-shadow(0 .75rem 1rem rgba(0,0,0,.35)); }
  .dash-badge{ display:inline-block; margin-top:.75rem; background:#23284a; color:#cfd3ff; padding:.35rem .75rem; border-radius:.5rem; font-weight:700; }
  .dash-pager .btn{ background:#23284a; color:#cfd3ff; border:none; margin:.15rem; }
  .dash-pager .btn.active{ background:#4750c9; color:#fff; }
  .dash-actions .btn{ border:none; }
  .btn-cta{ background:#4750c9; color:#fff; }
  /* horizontal scroll area */
  .dash-scroll{ overflow-x:auto; display:flex; gap:1rem; scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch; padding:0 .5rem; }
  .dash-page{ flex:0 0 100%; scroll-snap-align:center; }
  .dash-scroll::-webkit-scrollbar{ height:8px; }
  .dash-scroll::-webkit-scrollbar-thumb{ background:#2f3561; border-radius:4px; }
  @media (max-width: 991.98px){
    .dash-layout{ flex-direction:column; }
    .dash-sidebar{ flex:0 0 auto; position:static; }
  }
</style>

@php
    $ps1 = \App\Models\UnitPS::where('status','available')->where('model','PS1')->count();
    $ps2 = \App\Models\UnitPS::where('status','available')->where('model','PS2')->count();
    $ps4 = \App\Models\UnitPS::where('status','available')->where('model','PS4')->count();
    $ps5 = \App\Models\UnitPS::where('status','available')->where('model','PS5')->count();
    $ps3 = \App\Models\UnitPS::where('status','available')->where('model','PS3')->count();
@endphp

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="dash-hero">
        <h2>Selamat Datang di Rental PS</h2>
        <p>Sewa PlayStation favoritmu sekarang!</p>
      </div>

      <div class="dash-scroll pb-2" id="dashScroll">
      @for($page=0; $page<4; $page++)
      <div class="dash-page">
        <div class="row g-4 px-2 pb-2">
        <div class="col-sm-6 col-lg-6">
          <div class="dash-card">
            <div class="dash-frame">
              <img src="https://cdn.antaranews.com/cache/1200x800/2018/08/PS4-Pro.jpg" alt="PS4">
            </div>
            <div class="dash-badge">Tersedia PS4: {{ $ps4 }}</div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-6">
          <div class="dash-card">
            <div class="dash-frame">
              <img src="https://www.genmuda.com/wp-content/uploads/2016/11/SlimandPro.jpg" alt="PS5">
            </div>
            <div class="dash-badge">Tersedia PS5: {{ $ps5 }}</div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-6">
          <div class="dash-card">
            <div class="dash-frame">
              <img src="https://thumb.viva.id/vivagadget/665x374/2024/08/21/66c60839dd6b4-jangan-salah-pilih-berikut-perbedaan-ps3-slim-dan-superslim-mana-yang-lebih-baik_.jpg" alt="PS3">
            </div>
            <div class="dash-badge">Tersedia PS3: {{ $ps3 }}</div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-6">
          <div class="dash-card">
            <div class="dash-frame">
              <img src="https://cdnpro.eraspace.com/pub/media/wysiwyg/Deskripsi_PDP/ImagePDP2024/Sony-PS-5-Slim-Desc_3.jpg" alt="PS5">
            </div>
            <div class="dash-badge">Tersedia PS5: {{ $ps5 }}</div>
          </div>
        </div>
      </div>
      <div class="dash-actions text-center pb-3">
        <a href="{{ route('pelanggan.rentals.create') }}" class="btn btn-cta me-2">Mulai Sewa</a>
        <a href="{{ route('pelanggan.unitps.index') }}" class="btn btn-secondary">Lihat Unit & Game</a>
      </div>
      </div>
      @endfor
      </div>

      <div class="d-flex justify-content-center dash-pager pb-3">
    <button class="btn btn-sm active" data-index="0">1</button>
    <button class="btn btn-sm" data-index="1">2</button>
    <button class="btn btn-sm" data-index="2">3</button>
    <button class="btn btn-sm" data-index="3">4</button>
      </div>
    </main>
  </div>
</div>

<script>
  // Pager controls for horizontal scroll
  const container = document.getElementById('dashScroll');
  const items = Array.from(container.querySelectorAll('.dash-page'));
  const buttons = Array.from(document.querySelectorAll('.dash-pager .btn'));

  function setActive(i){
    buttons.forEach((b,idx)=> b.classList.toggle('active', idx===i));
  }

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const i = parseInt(btn.dataset.index, 10) || 0;
      const target = items[i];
      if(target){ target.scrollIntoView({behavior:'smooth', inline:'center'}); }
      setActive(i);
    });
  });

  // Update active button on manual scroll
  let ticking = false;
  container.addEventListener('scroll', ()=>{
    if(!ticking){
      window.requestAnimationFrame(()=>{
        let nearest = 0, min = Infinity;
        items.forEach((el,idx)=>{
          const rect = el.getBoundingClientRect();
          const center = Math.abs(rect.left + rect.width/2 - window.innerWidth/2);
          if(center < min){ min = center; nearest = idx; }
        });
        setActive(nearest);
        ticking = false;
      });
      ticking = true;
    }
  });

  // Auto-refresh active transactions every 30 seconds (placeholder)
  setInterval(()=>console.log('Checking for transaction updates...'), 30000);
</script>
@endsection

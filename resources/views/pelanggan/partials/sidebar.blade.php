<style>
  /* Responsive & collapsible sidebar defaults */
  .dash-toggle{ position:fixed; left:12px; top:12px; z-index:1041; background:#4750c9; color:#fff; border:none; padding:.45rem .6rem; border-radius:.5rem; box-shadow:0 .5rem 1rem rgba(0,0,0,.25); display:none; }
  .dash-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1040; display:none; }
  .dash-overlay.show{ display:block; }
  /* Ensure full height */
  .dash-sidebar{ min-height:calc(100dvh - 2rem); transition: transform .25s ease, width .25s ease; will-change: transform; z-index:1042; }
  /* Close button hidden on desktop by default */
  .dash-close{ display:none; }
  /* Mobile drawer (hidden by default) */
  @media (max-width: 991.98px){
    .dash-toggle{ display:inline-block; }
    .dash-sidebar{ position:fixed; top:1rem; left:1rem; transform:translateX(-110%); width:280px; }
    .dash-sidebar.open{ transform:translateX(0); }
    .dash-close{ display:inline-grid; place-items:center; position:absolute; right:.75rem; top:.6rem; width:34px; height:34px; background:rgba(0,0,0,.25); color:#fff; border:0; border-radius:.5rem; }
    /* Global responsive fixes for pelanggan pages */
    html, body{ overflow-x:hidden; }
    .dash-layout{ flex-direction:column !important; }
    .dash-main{ width:100% !important; }
    .filter-row{ grid-template-columns:1fr !important; }
    /* Make custom tables scrollable when not wrapped */
    table.dark{ display:block; overflow-x:auto; width:100%; }
    img, svg{ max-width:100%; height:auto; }
  }
  .dash-logo{ width:100%; display:grid; place-items:center; margin-bottom:1rem; }
  .dash-logo .circle{ width:96px; height:96px; border-radius:50%; background:#0b3d91; display:grid; place-items:center; box-shadow:inset 0 -8px 14px rgba(0,0,0,.25); }
  .dash-menu{ list-style:none; padding:0; margin:1rem 0 0; display:grid; gap:.5rem; }
  .dash-menu a{ display:flex; align-items:center; gap:.6rem; padding:.65rem .9rem; border-radius:.6rem; color:#b8baf0; text-decoration:none; font-weight:700; background:transparent; }
  .dash-menu a:hover, .dash-menu a.active{ background:rgba(255,255,255,.06); color:#fff; }
  .dash-icon{ width:22px; height:22px; display:inline-grid; place-items:center; }
  .dash-logout{ margin-top:1rem; }
</style>
<button type="button" class="dash-toggle" aria-label="Buka/Tutup menu">☰</button>
<div class="dash-overlay"></div>
<aside class="dash-sidebar">
  <button type="button" class="dash-close" aria-label="Tutup">✕</button>
  <div class="dash-logo">
    <div class="circle">
      <img width="44" height="44" alt="PlayStation" src="https://cdn.simpleicons.org/playstation/ffffff" />
    </div>
  </div>
  <ul class="dash-menu">
    <li><a href="{{ route('dashboard.pelanggan') }}" class="{{ request()->routeIs('dashboard.pelanggan') ? 'active' : '' }}"><span class="dash-icon"><i class="bi bi-house-door"></i></span> <span>Beranda</span></a></li>
    <li><a href="{{ route('pelanggan.profile.show') }}" class="{{ request()->routeIs('pelanggan.profile.*') ? 'active' : '' }}"><span class="dash-icon"><i class="bi bi-person"></i></span> <span>Profil</span></a></li>
    <li><a href="{{ route('pelanggan.unitps.index') }}" class="{{ request()->routeIs('pelanggan.unitps.*') ? 'active' : '' }}"><span class="dash-icon"><i class="bi bi-controller"></i></span> <span>Sewa Unit PS</span></a></li>
    <li><a href="{{ route('pelanggan.games.index') }}" class="{{ request()->routeIs('pelanggan.games.*') ? 'active' : '' }}"><span class="dash-icon"><i class="bi bi-disc"></i></span> <span>Sewa Game</span></a></li>
    <li><a href="{{ route('pelanggan.accessories.index') }}" class="{{ request()->routeIs('pelanggan.accessories.*') ? 'active' : '' }}"><span class="dash-icon"><i class="bi bi-plugin"></i></span> <span>Sewa Aksesoris</span></a></li>
    <li><a href="{{ route('pelanggan.cart.index') }}" class="{{ request()->routeIs('pelanggan.cart.*') ? 'active' : '' }}"><span class="dash-icon"><i class="bi bi-cart"></i></span> <span>Keranjang</span></a></li>
    <li><a href="{{ route('pelanggan.rentals.index') }}" class="{{ request()->routeIs('pelanggan.rentals.index') ? 'active' : '' }}"><span class="dash-icon"><i class="bi bi-clock-history"></i></span> <span>Riwayat Penyewaan</span></a></li>
  </ul>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="dash-logout">
    @csrf
    <button type="submit" class="btn btn-danger w-100"><span class="me-2"><i class="bi bi-box-arrow-right"></i></span> Logout</button>
  </form>
</aside>
<script>
  (function(){
    const toggle = document.querySelector('.dash-toggle');
    const sidebar = document.querySelector('.dash-sidebar');
    const overlay = document.querySelector('.dash-overlay');
    const closeBtn = document.querySelector('.dash-close');
    if(!toggle || !sidebar || !overlay) return;
    const open = ()=>{ sidebar.classList.add('open'); overlay.classList.add('show'); };
    const close = ()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); };
    toggle.addEventListener('click', ()=> sidebar.classList.contains('open') ? close() : open());
    overlay.addEventListener('click', close);
    if(closeBtn){ closeBtn.addEventListener('click', close); }
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') close(); });
    const mq = window.matchMedia('(min-width: 992px)');
    mq.addEventListener ? mq.addEventListener('change', ()=>{ if(mq.matches) close(); }) : mq.addListener(()=>{ if(mq.matches) close(); });
  })();
</script>

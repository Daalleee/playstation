<style>
  .dash-toggle{ position:fixed; left:12px; top:12px; z-index:1041; background:#4750c9; color:#fff; border:none; padding:.45rem .6rem; border-radius:.5rem; box-shadow:0 .5rem 1rem rgba(0,0,0,.25); display:none; }
  .dash-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1040; display:none; }
  .dash-overlay.show{ display:block; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); height: 100vh; overflow-y: auto; position: sticky; top: 0; transition: transform .25s ease; z-index:1042; }
  @media (max-width: 991.98px){
    .dash-toggle{ display:inline-block; }
    .dash-layout{ flex-direction:column; }
    .dash-sidebar{ position:fixed; top:1rem; left:1rem; transform:translateX(-110%); width:280px; height: auto; }
    .dash-sidebar.open{ transform:translateX(0); }
    html, body{ overflow-x:hidden; }
  }
  .dash-logo{ width:100%; display:grid; place-items:center; margin-bottom:1rem; }
  .dash-logo .circle{ width:96px; height:96px; border-radius:50%; background:#0b3d91; display:grid; place-items:center; box-shadow:inset 0 -8px 14px rgba(0,0,0,.25); }
  .dash-menu{ list-style:none; padding:0; margin:1rem 0 0; display:grid; gap:.4rem; }
  .dash-menu a{ display:flex; align-items:center; gap:.6rem; padding:.6rem .8rem; border-radius:.6rem; color:#e9e9ff; text-decoration:none; font-weight:700; }
  .dash-menu a:hover{ background:rgba(255,255,255,.06); }
  .dash-icon{ width:22px; height:22px; display:inline-grid; place-items:center; }
</style>
<button type="button" class="dash-toggle" aria-label="Buka/Tutup menu">☰</button>
<div class="dash-overlay"></div>
<aside class="dash-sidebar">
  <div class="dash-logo">
    <div class="circle">
      <img width="44" height="44" alt="PlayStation" src="https://cdn.simpleicons.org/playstation/ffffff" />
    </div>
  </div>
  <ul class="dash-menu">
    <li><a href="{{ route('dashboard.admin') }}"><span class="dash-icon"><i class="bi bi-house-door"></i></span> <span>Beranda</span></a></li>
    <li><a href="{{ route('admin.pelanggan.index') }}"><span class="dash-icon"><i class="bi bi-people"></i></span> <span>Kelola Pelanggan</span></a></li>
    <li><a href="{{ route('admin.pemilik.index') }}"><span class="dash-icon"><i class="bi bi-person-workspace"></i></span> <span>Kelola Pemilik</span></a></li>
    <li><a href="{{ route('admin.kasir.index') }}"><span class="dash-icon"><i class="bi bi-person-vcard"></i></span> <span>Kelola Kasir</span></a></li>
    <li><a href="{{ route('admin.admin.index') }}"><span class="dash-icon"><i class="bi bi-person-gear"></i></span> <span>Kelola Admin</span></a></li>
    <li><a href="{{ route('admin.unitps.index') }}"><span class="dash-icon"><i class="bi bi-controller"></i></span> <span>Tambah Unit PS</span></a></li>
    <li><a href="{{ route('admin.games.index') }}"><span class="dash-icon"><i class="bi bi-disc"></i></span> <span>Tambah Game</span></a></li>
    <li><a href="{{ route('admin.accessories.index') }}"><span class="dash-icon"><i class="bi bi-plugin"></i></span> <span>Tambah Aksesoris</span></a></li>
    <li><a href="{{ route('admin.laporan') }}"><span class="dash-icon"><i class="bi bi-file-earmark-bar-graph"></i></span> <span>Laporan</span></a></li>
    <li>
      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();"><span class="dash-icon"><i class="bi bi-box-arrow-right"></i></span> <span>Logout</span></a>
      <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </li>
  </ul>
</aside>
<script>
  (function(){
    const toggle = document.querySelector('.dash-toggle');
    const sidebar = document.querySelector('.dash-sidebar');
    const overlay = document.querySelector('.dash-overlay');
    if(!toggle || !sidebar || !overlay) return;
    const open = ()=>{ sidebar.classList.add('open'); overlay.classList.add('show'); };
    const close = ()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); };
    toggle.addEventListener('click', ()=> sidebar.classList.contains('open') ? close() : open());
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') close(); });
  })();
</script>

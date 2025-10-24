<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Dashboard Pemilik')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bg:#2f355a; --panel:#3f456f; --panel-soft:#4b5181; --text:#eef0ff; --muted:#c8caf6; }
        body{background:#242947;color:var(--text); overflow-x:hidden;}
        body.no-scroll{ overflow:hidden; }
        .layout{display:flex;min-height:100vh; position:relative;}
        .sidebar{width:270px;background:linear-gradient(180deg,#2b2351,#3a2d67);padding:24px 16px; z-index:1042; border-right:1px solid rgba(255,255,255,.08);}
        .brand{display:flex;flex-direction:column;align-items:center;gap:12px;margin-bottom:28px}
        .brand-logo{width:72px;height:72px;border-radius:18px;background:#0b3d91;display:flex;align-items:center;justify-content:center; box-shadow:0 8px 24px rgba(0,0,0,.35)}
        .brand-logo i{font-size:40px;color:#fff}
        .brand span{font-weight:700;color:#fff;font-size:18px}
        .nav-link{color:var(--muted);padding:12px 14px;border-radius:12px;display:flex;align-items:center;gap:12px;text-decoration:none}
        .nav-link:hover,.nav-link.active{background:rgba(255,255,255,.08);color:#fff}
        .content{flex:1;background:var(--bg)}
        .card{background: #49497A; color:var(--text);border:0;box-shadow:0 6px 24px rgba(0,0,0,.25)}
        .kpi-pill{background:rgba(255,255,255,.08); border-radius:18px; padding:22px; text-align:center; font-weight:700;}
        .table{color:var(--text); background-color: #49497A;}
        .table thead th{background:#2d3192;color:#dbe0ff;border:0}
        .table tbody tr{background: #5a5a8a;}
        .table tbody tr+tr{border-top:1px solid rgba(255,255,255,.06)}
        .dash-toggle{ position:fixed; left:10px; top:10px; z-index:1043; background:#4750c9; color:#fff; border:none; padding:.5rem .65rem; border-radius:.6rem; box-shadow:0 .5rem 1rem rgba(0,0,0,.25); display:none; }
        .dash-close{ position:absolute; right:10px; top:10px; z-index:1; background:#dc3545; color:#fff; border:none; padding:.5rem .65rem; border-radius:.6rem; box-shadow:0 .5rem 1rem rgba(0,0,0,.25); display:none; }
        .dash-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.45); backdrop-filter:saturate(120%) blur(2px); z-index:1041; display:none; }
        .dash-overlay.show{ display:block; }
        .content.disabled{ pointer-events:none; }
        @media (max-width: 991.98px){
            .dash-toggle{ display:inline-block; }
            body.menu-open .dash-toggle{ display:none !important; }
            .sidebar.open .dash-close{ display:inline-block; }
            .layout{ min-height:100dvh; }
            .sidebar{ position:fixed; left:0; top:0; height:100dvh; transform: translateX(-105%); transition: transform .25s ease; }
            .sidebar.open{ transform: translateX(0); }
            .content{ width:100%; padding-top:56px; transition: margin-left .25s ease; }
            .layout.shift .content{ margin-left:270px; }
        }
    </style>
</head>
<body>
<button type="button" class="dash-toggle" aria-label="Buka menu">☰</button>
<div class="dash-overlay"></div>
<div class="layout">
    <aside class="sidebar">
        <button type="button" class="dash-close" aria-label="Tutup menu">✕</button>
        <div class="brand">
            <div class="brand-logo">
                <i class="bi bi-playstation"></i>
            </div>
            <span>Pemilik</span>
        </div>
        <nav class="d-flex flex-column gap-1">
            <a href="{{ route('dashboard.pemilik') }}" class="nav-link {{ request()->routeIs('dashboard.pemilik') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> Beranda
            </a>
            <a href="{{ route('pemilik.status_produk') }}" class="nav-link {{ request()->routeIs('pemilik.status_produk') ? 'active' : '' }}">
                <i class="bi bi-controller"></i> Lihat Status
            </a>
            <a href="{{ route('pemilik.laporan') }}" class="nav-link {{ request()->routeIs('pemilik.laporan') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Akses Laporan
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
            </form>
        </nav>
    </aside>
    <main class="content">
        <div class="container-fluid px-2 px-md-3 py-3">
            @yield('owner_content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (function(){
    const toggle = document.querySelector('.dash-toggle');
    const closeBtn = document.querySelector('.dash-close');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.dash-overlay');
    const content = document.querySelector('.content');
    const layout = document.querySelector('.layout');
    if(!toggle || !sidebar || !overlay) return;
    const open = ()=>{ sidebar.classList.add('open'); overlay.classList.add('show'); document.body.classList.add('no-scroll'); document.body.classList.add('menu-open'); if(content){content.classList.add('disabled');} if(layout){layout.classList.add('shift');} };
    const close = ()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); document.body.classList.remove('no-scroll'); document.body.classList.remove('menu-open'); if(content){content.classList.remove('disabled');} if(layout){layout.classList.remove('shift');} };
    toggle.addEventListener('click', ()=> sidebar.classList.contains('open') ? close() : open());
    if(closeBtn){ closeBtn.addEventListener('click', close); }
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') close(); });
    const mq = window.matchMedia('(min-width: 992px)');
    if(mq.addEventListener){ mq.addEventListener('change', ()=>{ if(mq.matches) close(); }); }
    else if(mq.addListener){ mq.addListener(()=>{ if(mq.matches) close(); }); }
  })();
</script>
</body>
</html>

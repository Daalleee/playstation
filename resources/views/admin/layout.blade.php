<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bg:#1e2553; --panel:#3b3f7a; --panel-soft:#4b4f8a; --text:#e6e8ff; --muted:#b8baf0; }
        body{background:#171b3a;color:var(--text)}
        body.no-scroll{ overflow:hidden; }
        .layout{display:flex;min-height:100vh; position:relative;}
        .sidebar{width:260px;background:linear-gradient(180deg,#23285a,#2e3067);padding:24px 16px; z-index:1042;}
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:28px}
        .brand-logo{width:48px;height:48px;border-radius:12px;background:#0b3d91;display:flex;align-items:center;justify-content:center}
        .brand span{font-weight:600;color:#fff;font-size:18px}
        .nav-link{color:var(--muted);padding:10px 12px;border-radius:10px;display:flex;align-items:center;gap:10px;text-decoration:none}
        .nav-link:hover,.nav-link.active{background:rgba(255,255,255,.06);color:#fff}
        .content{flex:1;background:var(--bg)}
        .header{text-align:center;padding:20px 16px;color:#d7dbff;font-weight:600;font-size:24px}
        .card{background:var(--panel);color:var(--text);border:0;box-shadow:0 6px 24px rgba(0,0,0,.25)}
        .table{color:var(--text)}
        .table thead th{background:#2d3192;color:#dbe0ff;border:0}
        .table tbody tr{background:var(--panel-soft)}
        .table tbody tr+tr{border-top:1px solid rgba(255,255,255,.06)}
        /* Responsive off-canvas */
        .dash-toggle{ position:fixed; left:10px; top:10px; z-index:1043; background:#4750c9; color:#fff; border:none; padding:.5rem .65rem; border-radius:.6rem; box-shadow:0 .5rem 1rem rgba(0,0,0,.25); display:none; }
        .dash-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.45); backdrop-filter:saturate(120%) blur(2px); z-index:1041; display:none; }
        .dash-overlay.show{ display:block; }
        .content.disabled{ pointer-events:none; }
        @media (max-width: 991.98px){
            .dash-toggle{ display:inline-block; }
            .layout{ min-height:100dvh; }
            .sidebar{ position:fixed; left:0; top:0; height:100dvh; transform: translateX(-105%); transition: transform .25s ease; }
            .sidebar.open{ transform: translateX(0); }
            .content{ width:100%; padding-top:56px; }
            .header{font-size:20px;padding:16px 12px}
            .nav-link{padding:8px 10px}
        }
    </style>
</head>
<body>
<button type="button" class="dash-toggle" aria-label="Buka/Tutup menu">☰</button>
<div class="dash-overlay"></div>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo"><i class="bi bi-playstation fs-3 text-light"></i></div>
            <span>Playstation</span>
        </div>
        <nav class="d-flex flex-column gap-1">
            <a href="{{ route('dashboard.admin') }}" class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}"><i class="bi bi-house-door"></i> Beranda</a>
            <a href="{{ route('admin.unitps.index') }}" class="nav-link {{ request()->routeIs('admin.unitps.*') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> Kelola Inventaris</a>
            <a href="{{ route('admin.pelanggan.index') }}" class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Kelola Data Pelanggan</a>
            <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}"><i class="bi bi-graph-up"></i> Laporan</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
            </form>
        </nav>
    </aside>
    <main class="content">
        <div class="container-fluid px-2 px-md-3 py-3">
            @yield('admin_content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (function(){
    const toggle = document.querySelector('.dash-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.dash-overlay');
    const content = document.querySelector('.content');
    if(!toggle || !sidebar || !overlay) return;
    const open = ()=>{ sidebar.classList.add('open'); overlay.classList.add('show'); document.body.classList.add('no-scroll'); if(content){content.classList.add('disabled');} };
    const close = ()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); document.body.classList.remove('no-scroll'); if(content){content.classList.remove('disabled');} };
    toggle.addEventListener('click', ()=> sidebar.classList.contains('open') ? close() : open());
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') close(); });
    const mq = window.matchMedia('(min-width: 992px)');
    if(mq.addEventListener){ mq.addEventListener('change', ()=>{ if(mq.matches) close(); }); }
    else if(mq.addListener){ mq.addListener(()=>{ if(mq.matches) close(); }); }
  })();
</script>
</body>
</html>

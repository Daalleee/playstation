<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Kasir')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bg:#1e2553; --panel:#3b3f7a; --panel-soft:#4b4f8a; --text:#e6e8ff; --muted:#b8baf0; }
        body{background:#171b3a;color:var(--text); overflow-x:hidden;}
        body.no-scroll{ overflow:hidden; }
        .layout{display:flex;min-height:100vh; position:relative;}
        .sidebar{width:260px;background:linear-gradient(180deg,#23285a,#2e3067);padding:24px 16px; z-index:1042;}
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:28px}
        .brand-logo{width:48px;height:48px;border-radius:12px;background:#0b3d91;display:flex;align-items:center;justify-content:center}
        .brand span{font-weight:600;color:#fff;font-size:18px}
        .nav-link{color:var(--muted);padding:10px 12px;border-radius:10px;display:flex;align-items:center;gap:10px;text-decoration:none}
        .nav-link:hover,.nav-link.active{background:rgba(255,255,255,.06);color:#fff}
        .content{flex:1;background:var(--bg)}
        .card{background: #49497A; color:var(--text);border:0;box-shadow:0 6px 24px rgba(0,0,0,.25)}
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
            .layout.shift .content{ margin-left:260px; }
        }
        .badge-status{font-weight:600}
    </style>
</head>
<body>
<button type="button" class="dash-toggle" aria-label="Buka menu">☰</button>
<div class="dash-overlay"></div>
<div class="layout">
    <aside class="sidebar">
        <button type="button" class="dash-close" aria-label="Tutup menu">✕</button>
        <div class="brand">
            <div class="brand-logo"><i class="bi bi-receipt fs-3 text-light"></i></div>
            <span>Kasir</span>
        </div>
        <nav class="d-flex flex-column gap-1">
            <a href="{{ route('dashboard.kasir') }}" class="nav-link {{ request()->routeIs('dashboard.kasir') ? 'active' : '' }}"><i class="bi bi-house-door"></i> Dashboard</a>
            <a href="{{ route('kasir.transaksi.index') }}" class="nav-link {{ request()->routeIs('kasir.transaksi.*') ? 'active' : '' }}"><i class="bi bi-list-check"></i> Transaksi</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
            </form>
        </nav>
    </aside>
    <main class="content">
        <div class="container-fluid px-2 px-md-3 py-3">
            @yield('kasir_content')
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

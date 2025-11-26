<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Beranda Pemilik')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bg:#2f355a; --panel:#3f456f; --panel-soft:#4b5181; --text:#eef0ff; --muted:#c8caf6; }
        body{background:#242947;color:var(--text); overflow-x:hidden; min-height:100dvh; }
        body.no-scroll{ overflow:hidden; }
        .dash-dark{ background:var(--bg); color:var(--text); min-height:100dvh; }
        .dash-layout{ display:flex; gap:1rem; height: 100vh; }
        .dash-sidebar{ flex:0 0 270px; background:linear-gradient(180deg,#2b2351,#3a2d67); border-right:1px solid rgba(255,255,255,.08); padding:1.5rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); height: 100vh; overflow-y: auto; position: sticky; top: 0; z-index: 1042; }
        .dash-main{ flex:1; overflow-y: auto; padding: 1rem; height: 100vh; box-sizing: border-box; }
        .brand{display:flex;flex-direction:column;align-items:center;gap:12px;margin-bottom:28px}
        .brand-logo{width:72px;height:72px;border-radius:18px;background:#0b3d91;display:flex;align-items:center;justify-content:center; box-shadow:0 8px 24px rgba(0,0,0,.35)}
        .brand-logo i{font-size:40px;color:#fff}
        .brand span{font-weight:700;color:#fff;font-size:18px}
        .nav-link{color:var(--muted);padding:12px 14px;border-radius:12px;display:flex;align-items:center;gap:12px;text-decoration:none}
        .nav-link:hover,.nav-link.active{background:rgba(255,255,255,.08);color:#fff}
        .card{background: #49497A; color:var(--text);border:0;box-shadow:0 6px 24px rgba(0,0,0,.25)}
        .kpi-pill{
            background: radial-gradient(circle at top left, rgba(129,140,248,.4), transparent 55%) rgba(15,23,42,.7);
            border-radius: 20px;
            padding: 18px 20px;
            border: 1px solid rgba(148,163,184,.6);
            box-shadow: 0 14px 32px rgba(15,23,42,.8);
            backdrop-filter: blur(10px);
            transition: transform .16s ease-out, box-shadow .16s ease-out, border-color .16s ease-out;
        }
        .kpi-pill:hover{
            transform: translateY(-2px);
            box-shadow: 0 20px 46px rgba(15,23,42,.9);
            border-color: rgba(191,219,254,.9);
        }
        .kpi-pill--clickable{ cursor:pointer; }
        .kpi-pill--active{
            border-color: rgba(191,219,254,.98);
            box-shadow: 0 24px 56px rgba(59,130,246,.7);
        }
        .kpi-label{
            font-size: .78rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .25rem;
        }
        .kpi-value{
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.1;
        }
        .kpi-icon{
            width: 46px;
            height: 46px;
            border-radius: 999px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            box-shadow: 0 10px 25px rgba(15,23,42,.8);
        }
        .kpi-icon i{
            font-size: 1.5rem;
        }
        .kpi-icon--purple{
            background: radial-gradient(circle at top, #6366f1, #312e81);
        }
        .kpi-icon--amber{
            background: radial-gradient(circle at top, #f97316, #92400e);
        }
        .kpi-icon--green{
            background: radial-gradient(circle at top, #22c55e, #065f46);
        }
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
            .dash-layout{ flex-direction:column; }
            .dash-sidebar{ flex:0 0 auto; position:static; height: auto; }
            .dash-main{ height: auto; }
        }
    </style>
</head>
<body>
<button type="button" class="dash-toggle" aria-label="Buka menu">☰</button>
<div class="dash-overlay"></div>
<div class="dash-dark p-3">
    <div class="dash-layout">
        <aside class="dash-sidebar">
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
        <main class="dash-main">
            @yield('owner_content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (function(){
    const toggle = document.querySelector('.dash-toggle');
    const closeBtn = document.querySelector('.dash-close');
    const sidebar = document.querySelector('.dash-sidebar');
    const overlay = document.querySelector('.dash-overlay');
    const content = document.querySelector('.dash-main');
    const body = document.body;
    if(!toggle || !sidebar || !overlay) return;
    const open = ()=>{ sidebar.classList.add('open'); overlay.classList.add('show'); body.classList.add('no-scroll'); body.classList.add('menu-open'); if(content){content.classList.add('disabled');} };
    const close = ()=>{ sidebar.classList.remove('open'); overlay.classList.remove('show'); body.classList.remove('no-scroll'); body.classList.remove('menu-open'); if(content){content.classList.remove('disabled');} };
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

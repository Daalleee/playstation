<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Rental Playstation — Welcome</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    :root{
      --bg:#0f1023; --fg:#ffffff; --muted:#ffffff; --accent:#6c63ff; --accent-2:#22d3ee;
      --card:#181a36; --ring: rgba(255,255,255,.12);
    }
    html,body{ height:100%; }
    body{ margin:0; background: radial-gradient(1200px 600px at 80% -10%, #2a2f74 0%, transparent 60%),
                             radial-gradient(900px 500px at -10% 20%, #1e9bd7 0%, transparent 55%),
                             linear-gradient(180deg, #121332, #0f1023); color:var(--fg); overflow-x:hidden; }
    a{ text-decoration:none }
    .hero{ position:relative; min-height:100dvh; display:flex; align-items:center; }
    .hero .glow{ position:absolute; inset:0; pointer-events:none; }
    .orb{ position:absolute; filter:blur(40px); opacity:.35; animation: floatY 10s ease-in-out infinite; }
    .orb.one{ width:420px; height:420px; background: radial-gradient(circle at 30% 30%, #6c63ff, transparent 60%); top:10%; left:-120px; }
    .orb.two{ width:520px; height:520px; background: radial-gradient(circle at 70% 40%, #22d3ee, transparent 60%); bottom:-120px; right:-140px; animation-delay: -3s; }
    @keyframes floatY{ 0%,100%{ transform: translateY(0) } 50%{ transform: translateY(-16px) } }

    .nav-lite{ position:absolute; top:0; left:0; right:0; padding:16px 0; background:linear-gradient(180deg, rgba(0,0,0,.35), rgba(0,0,0,0)); z-index:10; }
    .brand{ display:flex; align-items:center; gap:.6rem; font-weight:800; }
    .brand .logo{ width:40px; height:40px; border-radius:10px; background:rgba(255,255,255,.08); display:grid; place-items:center; box-shadow: inset 0 0 18px rgba(255,255,255,.12); }

    .headline{ font-size: clamp(28px, 4.5vw, 56px); font-weight:900; letter-spacing:.2px; }
    .sub{ color:var(--muted); font-size: clamp(14px, 1.8vw, 18px); }
    .cta .btn{ padding:.75rem 1.15rem; font-weight:700; border-radius:.8rem; }
    .btn-cta{ background: linear-gradient(90deg, var(--accent), var(--accent-2)); color:#0b0f1b; border:0; box-shadow:0 .75rem 2rem rgba(34,211,238,.25); }
    .btn-ghost{ border:1px solid var(--ring); color:var(--fg); background:rgba(255,255,255,.04); }

    .cardy{ background: var(--card); border:1px solid var(--ring); border-radius:1rem; padding:1rem; height:100%; transition: transform .2s ease, box-shadow .2s ease; }
    .cardy:hover{ transform: translateY(-4px); box-shadow:0 1rem 2rem rgba(0,0,0,.35); }
    .badge-soft{ background:rgba(255,255,255,.06); color:#ffffff; border:1px solid var(--ring); padding:.3rem .55rem; border-radius:.5rem; font-weight:700; }
    /* force white text on any muted elements within this page */
    .text-muted{ color:#ffffff !important; opacity: .95; }

    .marquee{ overflow:hidden; white-space:nowrap; mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent); }
    .marquee .track{ display:inline-block; padding: .5rem 0; animation: marquee 18s linear infinite; }
    @keyframes marquee{ from{ transform: translateX(0) } to{ transform: translateX(-50%) } }

    .footer-lite{ color:#a9b1ff; border-top:1px solid var(--ring); background: linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,0)); }

    /* page transition overlay */
    .page-transition{ position: fixed; inset: 0; pointer-events: none; z-index: 9999; }
    .page-transition .burst{ position: absolute; width: 80px; height: 80px; border-radius: 50%;
      left: var(--x, 50%); top: var(--y, 50%); transform: translate(-50%,-50%) scale(0);
      background: radial-gradient(circle at center, var(--accent-2), var(--accent) 60%, rgba(108,99,255,0.8));
      filter: blur(2px); box-shadow: 0 0 0 0 rgba(108,99,255,.35);
      transition: transform .75s cubic-bezier(.22,.61,.36,1), box-shadow .75s ease; }
    .page-transition.active{ pointer-events: all; }
    .page-transition.active .burst{ transform: translate(-50%,-50%) scale(45);
      box-shadow: 0 0 0 120vmax rgba(108,99,255,.15); }
    /* ripple circles */
    .page-transition .ripple{ position:absolute; left: var(--x, 50%); top: var(--y, 50%);
      width: 10px; height: 10px; border: 2px solid var(--accent-2); border-radius:50%;
      transform: translate(-50%,-50%) scale(0); opacity:.9; }
    .page-transition.active .ripple{ animation: ripple 800ms ease-out forwards; }
    .page-transition.active .r2{ animation-delay: 80ms; border-color: var(--accent); }
    .page-transition.active .r3{ animation-delay: 160ms; border-color: #7c3aed; }
    @keyframes ripple{ to{ transform: translate(-50%,-50%) scale(22); opacity:0; } }
    /* fade fallback in case of reduced motion */
    @media (prefers-reduced-motion: reduce){
      .page-transition .burst{ transition: opacity .4s ease; width:100%; height:100%; border-radius:0; left:50%; top:50%; transform:translate(-50%,-50%); opacity:0; background:#000; }
      .page-transition.active .burst{ opacity:1; }
    }
    /* blur+scale content while leaving */
    body.leaving .hero, body.leaving header, body.leaving footer{
      filter: blur(6px);
      transform: scale(.985);
      transition: filter .5s ease, transform .5s ease;
    }

    /* runner animation - shown during transition */
    .runner{ position:absolute; bottom: 8vh; left: -15%; width: 140px; height: 120px; pointer-events:none; z-index:2; display:none; }
    .runner .char{ position:absolute; inset:0; transform-origin: bottom center; }
    .runner .head{ position:absolute; width:26px; height:26px; border-radius:50%; background:#fff; left:54px; top:8px; box-shadow:0 0 0 3px rgba(0,0,0,.15) inset; }
    .runner .body{ position:absolute; width:12px; height:48px; background:#fff; left:61px; top:28px; border-radius:6px; transform-origin: top center; }
    .runner .arm.left, .runner .arm.right, .runner .leg.left, .runner .leg.right{ position:absolute; width:12px; height:46px; background:#fff; border-radius:6px; transform-origin: top center; }
    .runner .arm.left{ left:48px; top:32px; animation: armSwingL 500ms ease-in-out infinite alternate; }
    .runner .arm.right{ left:74px; top:32px; animation: armSwingR 500ms ease-in-out infinite alternate; }
    .runner .leg.left{ left:56px; top:70px; height:52px; animation: legSwingL 500ms ease-in-out infinite alternate; }
    .runner .leg.right{ left:68px; top:70px; height:52px; animation: legSwingR 500ms ease-in-out infinite alternate; }
    .runner .controller{ position:absolute; width:30px; height:18px; left:28px; top:42px; background:#0b0f1b; border:2px solid #6c63ff; border-radius:6px; transform: rotate(-12deg); box-shadow:0 0 10px rgba(108,99,255,.5);
      display:flex; align-items:center; justify-content:space-between; padding:0 4px; }
    .runner .controller:before, .runner .controller:after{ content:""; display:block; width:6px; height:6px; border-radius:50%; background:#22d3ee; }
    .runner .dust{ position:absolute; bottom:10px; left:40px; width:14px; height:14px; border-radius:50%; background:rgba(255,255,255,.25); filter: blur(2px); animation: dust 600ms ease-out infinite; }
    .runner .dust.d2{ left:20px; width:10px; height:10px; animation-delay:120ms; }
    .runner .dust.d3{ left:60px; width:8px; height:8px; animation-delay:240ms; }

    @keyframes runAcross{ from{ transform: translateX(0); opacity:.9 } 60%{ opacity:1 } to{ transform: translateX(130vw); opacity:.95 } }
    @keyframes runAcrossQuick{ from{ transform: translateX(0); opacity:1 } to{ transform: translateX(130vw); opacity:1 } }
    @keyframes armSwingL{ from{ transform: rotate(30deg) } to{ transform: rotate(-35deg) } }
    @keyframes armSwingR{ from{ transform: rotate(-30deg) } to{ transform: rotate(35deg) } }
    @keyframes legSwingL{ from{ transform: rotate(-25deg) } to{ transform: rotate(35deg) } }
    @keyframes legSwingR{ from{ transform: rotate(25deg) } to{ transform: rotate(-35deg) } }
    @keyframes dust{ from{ transform: translateY(0) scale(1); opacity:.6 } to{ transform: translateY(10px) scale(0.6); opacity:0 } }

    @media (prefers-reduced-motion: reduce){
      .runner{ opacity:.85 }
      .runner .arm.left,.runner .arm.right,.runner .leg.left,.runner .leg.right{ animation: none; }
      .runner .dust{ display:none; }
    }

    /* show runner only during transition */
    body.transition-run .runner{ display:block; animation: runAcrossQuick .9s cubic-bezier(.25,.8,.25,1) forwards; }
  </style>
</head>
<body>
  <div class="page-transition" id="pageTransition"><span class="burst"></span><span class="ripple r1"></span><span class="ripple r2"></span><span class="ripple r3"></span></div>
  <header class="nav-lite">
    <div class="container d-flex align-items-center justify-content-between">
      <div class="brand">
        <div class="logo"><i class="bi bi-playstation text-light fs-5"></i></div>
        <div>Rental Playstation</div>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('login.show') }}" class="btn btn-sm btn-ghost transition-link">Masuk</a>
        <a href="{{ route('register.show') }}" class="btn btn-sm btn-cta transition-link">Daftar</a>
      </div>
    </div>
  </header>

  <section class="hero">
    <div class="glow">
      <div class="orb one"></div>
      <div class="orb two"></div>
    </div>
    <div class="container position-relative" style="z-index:1">
      <!-- runner animation -->
      <div class="runner" aria-hidden="true">
        <div class="char">
          <div class="head"></div>
          <div class="body"></div>
          <div class="arm left"></div>
          <div class="arm right"></div>
          <div class="leg left"></div>
          <div class="leg right"></div>
          <div class="controller"></div>
          <div class="dust d1"></div>
          <div class="dust d2"></div>
          <div class="dust d3"></div>
        </div>
      </div>
      <div class="row g-4 align-items-center">
        <div class="col-12 col-lg-6">
          <span class="badge-soft">Sewa mudah • Banyak pilihan</span>
          <h1 class="headline mt-2 mb-2">Main Lebih Seru, Tanpa Beli Konsol</h1>
          <p class="sub mb-3">Sewa PS, Game, dan Aksesoris favoritmu dengan proses cepat. Kelola penyewaan, bayar, dan lacak status—all in one place.</p>
          <div class="cta d-flex gap-2 flex-wrap">
            <a href="{{ route('register.show') }}" class="btn btn-cta transition-link"><i class="bi bi-rocket-takeoff me-1"></i>Mulai Sekarang</a>
            <a href="{{ route('login.show') }}" class="btn btn-ghost transition-link"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk</a>
          </div>
          <div class="marquee mt-4">
            <div class="track">
              <span class="me-4">🎮 PS5</span>
              <span class="me-4">🎮 PS4</span>
              <span class="me-4">🕹️ Aksesoris</span>
              <span class="me-4">🔥 Game Terbaru</span>
              <span class="me-4">⚡ Proses Cepat</span>
              <span class="me-4">💳 Pembayaran Aman</span>
              <span class="me-4">🎮 PS5</span>
              <span class="me-4">🎮 PS4</span>
              <span class="me-4">🕹️ Aksesoris</span>
              <span class="me-4">🔥 Game Terbaru</span>
              <span class="me-4">⚡ Proses Cepat</span>
              <span class="me-4">💳 Pembayaran Aman</span>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6">
          <div class="row g-3">
            <div class="col-6">
              <div class="cardy text-center">
                <div class="display-6">15+</div>
                <div class="text-muted">Unit PS</div>
              </div>
            </div>
            <div class="col-6">
              <div class="cardy text-center">
                <div class="display-6">80+</div>
                <div class="text-muted">Koleksi Game</div>
              </div>
            </div>
            <div class="col-12">
              <div class="cardy d-flex align-items-center gap-3">
                <div class="fs-3"><i class="bi bi-shield-lock"></i></div>
                <div>
                  <div class="fw-bold">Pembayaran Aman</div>
                  <div class="text-muted">Dukungan metode pembayaran populer & historis transaksi.</div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="cardy d-flex align-items-center gap-3">
                <div class="fs-3"><i class="bi bi-lightning-charge"></i></div>
                <div>
                  <div class="fw-bold">Cepat & Transparan</div>
                  <div class="text-muted">Proses penyewaan jelas, status real-time, dan pengembalian mudah.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mt-4">
        <div class="col-12">
          <div class="cardy d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-controller fs-3"></i>
              <div>
                <div class="fw-bold">Siap bermain hari ini?</div>
                <div class="text-muted">Buat akun atau login untuk mulai menyewa.</div>
              </div>
            </div>
            <div class="d-flex gap-2">
              <a class="btn btn-cta transition-link" href="{{ route('register.show') }}">Daftar Gratis</a>
              <a class="btn btn-ghost transition-link" href="{{ route('login.show') }}">Masuk</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer-lite py-4 mt-4">
    <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div class="text-muted">© {{ date('Y') }} Rental Playstation</div>
      <div class="d-flex gap-3">
        <a href="{{ route('login.show') }}" class="link-light link-underline-opacity-0 transition-link">Masuk</a>
        <a href="{{ route('register.show') }}" class="link-light link-underline-opacity-0 transition-link">Daftar</a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function(){
      const overlay = document.getElementById('pageTransition');
      const burst = overlay ? overlay.querySelector('.burst') : null;
      const links = document.querySelectorAll('a.transition-link');
      const animateAndGo = (e)=>{
        const a = e.currentTarget;
        const href = a.getAttribute('href');
        if(!overlay || !burst || !href) return; // graceful fallback
        e.preventDefault();
        const rect = a.getBoundingClientRect();
        const x = rect.left + rect.width/2 + window.scrollX;
        const y = rect.top + rect.height/2 + window.scrollY;
        overlay.style.setProperty('--x', x + 'px');
        overlay.style.setProperty('--y', y + 'px');
        overlay.classList.add('active');
        document.body.classList.add('leaving');
        // navigate after animation
        setTimeout(()=>{ window.location.href = href; }, 900);
      };
      links.forEach(a => a.addEventListener('click', animateAndGo));
    })();
  </script>
</body>
</html>

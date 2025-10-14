<aside class="dash-sidebar">
  <div class="dash-logo">
    <div class="circle">
      <img width="44" height="44" alt="PlayStation" src="https://cdn.simpleicons.org/playstation/ffffff" />
    </div>
  </div>
  <ul class="dash-menu">
    <li><a href="{{ route('dashboard.pelanggan') }}"><span class="dash-icon">🏠</span> <span>Beranda</span></a></li>
    <li><a href="{{ route('pelanggan.profile.show') }}"><span class="dash-icon">👤</span> <span>Profil</span></a></li>
    <li><a href="{{ route('pelanggan.unitps.index') }}"><span class="dash-icon">🎮</span> <span>Lihat Unit & Game</span></a></li>
    <li><a href="{{ route('pelanggan.accessories.index') }}"><span class="dash-icon">🧩</span> <span>Aksesoris</span></a></li>
    <li><a href="{{ route('pelanggan.rentals.create') }}"><span class="dash-icon">🛒</span> <span>Penyewaan</span></a></li>
    <li><a href="{{ route('pelanggan.rentals.index') }}"><span class="dash-icon">🔁</span> <span>Riwayat Penyewaan</span></a></li>
    <li>
      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="dash-icon">↩️</span> <span>Logout</span></a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </li>
  </ul>
</aside>

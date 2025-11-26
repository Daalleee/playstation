<!-- Kasir Sidebar Menu -->
<a href="{{ route('dashboard.kasir') }}" class="nav-link {{ request()->routeIs('dashboard.kasir') ? 'active' : '' }}">
    <i class="bi bi-grid"></i>
    <span>Beranda</span>
</a>

<div class="sidebar-heading">Operasional</div>

<a href="{{ route('kasir.transaksi.index') }}" class="nav-link {{ request()->routeIs('kasir.transaksi.*') ? 'active' : '' }}">
    <i class="bi bi-receipt"></i>
    <span>Transaksi</span>
</a>
<a href="{{ route('kasir.rentals.index') }}" class="nav-link {{ request()->routeIs('kasir.rentals.*') ? 'active' : '' }}">
    <i class="bi bi-list-check"></i>
    <span>Daftar Sewa</span>
</a>

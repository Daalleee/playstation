<nav style="margin: 0 0 1rem 0;">
    <a href="{{ route('dashboard.pelanggan') }}">Dashboard</a> |
    <a href="{{ route('pelanggan.profile.show') }}">Profil</a> |
    <a href="{{ route('pelanggan.unitps.index') }}">Unit PS</a> |
    <a href="{{ route('pelanggan.games.index') }}">Games</a> |
    <a href="{{ route('pelanggan.accessories.index') }}">Aksesoris</a> |
    <a href="{{ route('pelanggan.rentals.index') }}">Riwayat Sewa</a> |
    <a href="{{ route('pelanggan.cart.index') }}">Keranjang</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline-block; margin-left: 1rem;">
        @csrf
        <button type="submit">Logout</button>
    </form>
    @if(session('impersonate_admin_id'))
        <form action="{{ route('admin.impersonate.leave') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background:#fd7e14; color:white; border:none; padding:4px 12px; border-radius:4px;">Kembali ke Admin</button>
        </form>
    @endif
</nav>

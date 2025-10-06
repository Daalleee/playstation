<nav style="margin: 0 0 1rem 0;">
    <a href="{{ route('dashboard.kasir') }}">Dashboard Kasir</a> |
    <a href="{{ route('kasir.rentals.index') }}">Daftar Rental</a> |
    <a href="{{ route('kasir.rentals.create') }}">Buat Rental</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline-block; margin-left: 1rem;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>



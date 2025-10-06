<nav style="margin: 0 0 1rem 0;">
    <a href="{{ route('dashboard.admin') }}">Dashboard</a> |
    <a href="{{ route('admin.unitps.index') }}">Unit PS</a> |
    <a href="{{ route('admin.games.index') }}">Games</a> |
    <a href="{{ route('admin.accessories.index') }}">Accessories</a> |
    <a href="{{ route('admin.pelanggan.index') }}">Pelanggan</a> |
    <a href="{{ route('admin.staff.index', ['role' => 'kasir']) }}">Staff</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline-block; margin-left: 1rem;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>



<h1>Dashboard Admin</h1>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>

<nav style="margin-top: 1rem;">
    <ul>
        <li><a href="{{ route('admin.pelanggan.index') }}">Kelola Pelanggan</a></li>
        <li><a href="{{ route('admin.unitps.index') }}">Kelola Unit PS</a></li>
        <li><a href="{{ route('admin.games.index') }}">Kelola Games</a></li>
        <li><a href="{{ route('admin.accessories.index') }}">Kelola Accessories</a></li>
        <li><a href="{{ route('admin.staff.index', ['role' => 'kasir']) }}">Manajemen Staff (Kasir)</a></li>
        <li><a href="{{ route('admin.staff.index', ['role' => 'pemilik']) }}">Manajemen Staff (Pemilik)</a></li>
        <li><a href="{{ route('admin.staff.index', ['role' => 'admin']) }}">Manajemen Staff (Admin)</a></li>
    </ul>
</nav>

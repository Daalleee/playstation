<h1>Dashboard Pemilik</h1>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
    </form>

@if(session('impersonate_admin_id'))
    <form action="{{ route('admin.impersonate.leave') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" style="background:#fd7e14; color:white; border:none; padding:4px 12px; border-radius:4px;">Kembali ke Admin</button>
    </form>
@endif

<nav style="margin-top: 1rem;">
    <ul>
        <li><a href="{{ route('pemilik.status_produk') }}">Status Produk</a></li>
        <li><a href="{{ route('pemilik.laporan') }}">Laporan Transaksi</a></li>
    </ul>
</nav>

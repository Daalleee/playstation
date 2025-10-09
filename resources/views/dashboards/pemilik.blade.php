<h1>Dashboard Pemilik</h1>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
    </form>

<nav style="margin-top: 1rem;">
    <ul>
        <li><a href="{{ route('pemilik.status_produk') }}">Status Produk</a></li>
        <li><a href="{{ route('pemilik.laporan') }}">Laporan Transaksi</a></li>
    </ul>
</nav>

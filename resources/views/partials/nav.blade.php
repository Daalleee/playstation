<nav style="margin: 0 0 1rem 0;">
    @php
        $type = $type ?? null;
        $items = [];
        if ($type === 'admin') {
            $items = [
                ['label' => 'Beranda', 'route' => route('dashboard.admin')],
                ['label' => 'Unit PS', 'route' => route('admin.unitps.index')],
                ['label' => 'Games', 'route' => route('admin.games.index')],
                ['label' => 'Aksesoris', 'route' => route('admin.accessories.index')],
                ['label' => 'Pelanggan', 'route' => route('admin.pelanggan.index')],
                ['label' => 'Staff', 'route' => route('admin.kasir.index')],
            ];
        } elseif ($type === 'pelanggan') {
            $items = [
                ['label' => 'Beranda', 'route' => route('dashboard.pelanggan')],
                ['label' => 'Profil', 'route' => route('pelanggan.profile.show')],
                ['label' => 'Unit PS', 'route' => route('pelanggan.unitps.index')],
                ['label' => 'Games', 'route' => route('pelanggan.games.index')],
                ['label' => 'Aksesoris', 'route' => route('pelanggan.accessories.index')],
                ['label' => 'Riwayat Sewa', 'route' => route('pelanggan.rentals.index')],
                ['label' => 'Keranjang', 'route' => route('pelanggan.cart.index')],
            ];
        }
    @endphp

    @foreach ($items as $i)
        <a href="{{ $i['route'] }}">{{ $i['label'] }}</a>@if (!$loop->last) | @endif
    @endforeach

    <form method="POST" action="{{ route('logout') }}" style="display:inline-block; margin-left: 1rem;">
        @csrf
        <button type="submit">Logout</button>
    </form>

    @if ($type === 'pelanggan' && session('impersonate_admin_id'))
        <form action="{{ route('admin.impersonate.leave') }}" method="POST" style="display:inline; margin-left: 0.5rem;">
            @csrf
            <button type="submit" style="background:#fd7e14; color:white; border:none; padding:4px 12px; border-radius:4px;">Kembali ke Admin</button>
        </form>
    @endif
</nav>

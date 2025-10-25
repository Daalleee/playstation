<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css?v=1.2" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css?v=1.2" rel="stylesheet">
    <style>
        :root {
            --bg: #17153B;
            /* Darkest purple */
            --panel: #2E236C;
            /* Medium purple */
            --panel-soft: #433D8B;
            /* Lightest purple */
            --text: #e6e8ff;
            --muted: #b8baf0;
            --accent: #4f46e5;
        }

        body {
            background: #17153B;
            color: var(--text);
        }

        .layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #17153B, #2E236C);
            padding: 24px 16px;
            z-index: 1042;
            height: 100vh;
            overflow-y: auto;
            position: sticky;
            top: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #2E236C;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.07);
        }

        .brand span {
            font-weight: 600;
            color: #fff;
            font-size: 18px
        }

        .nav-link {
            color: var(--muted);
            padding: 10px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.06);
            color: #fff
        }

        .content {
            flex: 1;
            background: var(--bg);
            overflow-y: auto;
            height: 100vh;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 20px 16px;
            color: #d7dbff;
            font-weight: 600;
            font-size: 24px
        }

        /* Updated styles with darker purple rows, grid lines, and hover effects */
        .card {
            background: #17153B;
            color: white;
            border: 0;
            box-shadow: 0 6px 24px rgba(0, 0, 0, .25)
        }

        .card-title {
            color: #dbe0ff
        }

        .table {
            color: white;
            background-color: #17153B;
            border-collapse: collapse;
        }

        .table thead {
            background: #2E236C;
        }

        .table thead th {
            background: #2E236C;
            color: #dbe0ff;
            border: 1px solid #433D8B;
            padding: 0.75rem;
        }

        .table tbody {
            background: #17153B;
        }

        .table tbody tr {
            background: #17153B;
            transition: background-color 0.2s ease;
        }

        /* Darker rows */
        .table tbody tr:hover {
            background: #2E236C;
        }

        /* Hover effect */
        .table tbody tr+tr {
            border-top: 1px solid #433D8B;
        }

        /* Horizontal grid lines */
        .table td,
        .table th {
            background-color: inherit;
            color: white;
            border: 1px solid #433D8B;
            padding: 0.75rem;
        }

        .table-responsive {
            background: #17153B;
        }

        /* Override any Bootstrap default styles */
        .table>:not(caption)>*>* {
            background-color: inherit;
            color: white;
        }

        .table *,
        .card * {
            background-color: inherit;
        }

        .logout {
            margin-top: auto
        }

        a {
            text-decoration: none
        }

        /* Responsive sidebar (off-canvas) */
        .dash-toggle {
            position: fixed;
            left: 12px;
            top: 12px;
            z-index: 1043;
            background: #2E236C;
            color: #fff;
            border: none;
            padding: .45rem .6rem;
            border-radius: .5rem;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .25);
            display: none;
        }

        .dash-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 1041;
            display: none;
        }

        .dash-overlay.show {
            display: block;
        }

        @media (max-width: 991.98px) {
            .dash-toggle {
                display: inline-block;
            }

            .layout {
                min-height: 100dvh;
            }

            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100dvh;
                transform: translateX(-105%);
                transition: transform .25s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content {
                width: 100%;
                height: auto;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <button type="button" class="dash-toggle" aria-label="Buka/Tutup menu">☰</button>
    <div class="dash-overlay"></div>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-logo">
                    <i class="bi bi-playstation fs-3 text-light"></i>
                </div>
                <span>Playstation</span>
            </div>
            <nav class="d-flex flex-column gap-1">
                <a href="{{ route('dashboard.admin') }}"
                    class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}"><i
                        class="bi bi-house-door"></i> Dashboard</a>
                <a href="{{ route('admin.pelanggan.index') }}"
                    class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}"><i
                        class="bi bi-people"></i> Kelola Pelanggan</a>
                <a href="{{ route('admin.pemilik.index') }}"
                    class="nav-link {{ request()->routeIs('admin.pemilik.*') ? 'active' : '' }}"><i
                        class="bi bi-person-workspace"></i> Kelola Pemilik</a>
                <a href="{{ route('admin.kasir.index') }}"
                    class="nav-link {{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}"><i
                        class="bi bi-person-vcard"></i> Kelola Kasir</a>
                <a href="{{ route('admin.admin.index') }}"
                    class="nav-link {{ request()->routeIs('admin.admin.*') ? 'active' : '' }}"><i
                        class="bi bi-person-gear"></i> Kelola Admin</a>
                <a href="{{ route('admin.unitps.index') }}"
                    class="nav-link {{ request()->routeIs('admin.unitps.*') ? 'active' : '' }}"><i
                        class="bi bi-controller"></i> Tambah Unit PS</a>
                <a href="{{ route('admin.games.index') }}"
                    class="nav-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}"><i
                        class="bi bi-disc"></i> Tambah Game</a>
                <a href="{{ route('admin.accessories.index') }}"
                    class="nav-link {{ request()->routeIs('admin.accessories.*') ? 'active' : '' }}"><i
                        class="bi bi-plugin"></i> Tambah Aksesoris</a>
                <a href="{{ route('admin.laporan') }}"
                    class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}"><i
                        class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
                <form method="POST" action="{{ route('logout') }}" class="logout">
                    @csrf
                    <button class="btn btn-danger w-100 mt-2"><i class="bi bi-box-arrow-right me-1"></i> Logout</button>
                </form>
            </nav>
        </aside>
        <main class="content">
            <div class="header">Dashboard Admin</div>
            <div class="container py-3">
                <!-- Statistik Inventaris -->
                <div class="card p-3 mb-4">
                    <h5 class="card-title mb-3">Statistik Inventaris</h5>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-center">Jumlah Stok</th>
                                    <th class="text-center">Unit Tersedia</th>
                                    <th class="text-center">Unit Disewa</th>
                                    <th class="text-center">Unit Rusak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats ?? [] as $row)
                                    <tr>
                                        <td>{{ $row['name'] }}</td>
                                        <td class="text-center">{{ $row['total'] }}</td>
                                        <td class="text-center">{{ $row['available'] }}</td>
                                        <td class="text-center">{{ $row['rented'] }}</td>
                                        <td class="text-center">{{ $row['damaged'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail Unit PS -->
                <div class="card p-3 mb-4">
                    <h5 class="card-title mb-3">Detail Unit PS</h5>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Model</th>
                                    <th>Merek</th>
                                    <th>Nomor Seri</th>
                                    <th>Stok</th>
                                    <th>Kondisi Baik</th>
                                    <th>Kondisi Buruk</th>
                                    <th>Disewa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unitps as $unit)
                                    <tr>
                                        <td>{{ $unit['nama'] }}</td>
                                        <td>{{ $unit['model'] }}</td>
                                        <td>{{ $unit['merek'] }}</td>
                                        <td>{{ $unit['nomor_seri'] }}</td>
                                        <td>{{ $unit['stok'] }}</td>
                                        <td>{{ $unit['kondisi_baik'] }}</td>
                                        <td>{{ $unit['kondisi_buruk'] }}</td>
                                        <td>{{ $unit['disewa'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data unit PS</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail Game -->
                <div class="card p-3 mb-4">
                    <h5 class="card-title mb-3">Detail Game</h5>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Platform</th>
                                    <th>Genre</th>
                                    <th>Stok</th>
                                    <th>Kondisi Baik</th>
                                    <th>Kondisi Buruk</th>
                                    <th>Disewa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($games as $game)
                                    <tr>
                                        <td>{{ $game['judul'] }}</td>
                                        <td>{{ $game['platform'] }}</td>
                                        <td>{{ $game['genre'] }}</td>
                                        <td>{{ $game['stok'] }}</td>
                                        <td>{{ $game['kondisi_baik'] }}</td>
                                        <td>{{ $game['kondisi_buruk'] }}</td>
                                        <td>{{ $game['disewa'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data game</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detail Aksesoris -->
                <div class="card p-3 mb-4">
                    <h5 class="card-title mb-3">Detail Aksesoris</h5>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Stok</th>
                                    <th>Kondisi Baik</th>
                                    <th>Kondisi Buruk</th>
                                    <th>Disewa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accessories as $acc)
                                    <tr>
                                        <td>{{ $acc['nama'] }}</td>
                                        <td>{{ $acc['jenis'] }}</td>
                                        <td>{{ $acc['stok'] }}</td>
                                        <td>{{ $acc['kondisi_baik'] }}</td>
                                        <td>{{ $acc['kondisi_buruk'] }}</td>
                                        <td>{{ $acc['disewa'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center" style="color: #e6e8ff;">Tidak ada data aksesoris</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const toggle = document.querySelector('.dash-toggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.dash-overlay');
            if (!toggle || !sidebar || !overlay) return;
            const open = () => {
                sidebar.classList.add('open');
                overlay.classList.add('show');
            };
            const close = () => {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            };
            toggle.addEventListener('click', () => sidebar.classList.contains('open') ? close() : open());
            overlay.addEventListener('click', close);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });
            const mq = window.matchMedia('(min-width: 992px)');
            if (mq.addEventListener) {
                mq.addEventListener('change', () => {
                    if (mq.matches) close();
                });
            } else if (mq.addListener) {
                mq.addListener(() => {
                    if (mq.matches) close();
                });
            }
        })();
    </script>
</body>

</html>

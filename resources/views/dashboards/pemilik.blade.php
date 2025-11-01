@extends('owner.layout')
@section('title','Beranda Pemilik')
@section('owner_content')
    @if(session('impersonate_admin_id'))
        <form action="{{ route('admin.impersonate.leave') }}" method="POST" class="mb-2">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-arrow-return-left me-1"></i> Kembali ke Admin</button>
        </form>
    @endif

    <div class="text-center mb-4">
        <h1 class="h3">Beranda Pemilik</h1>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="kpi-pill">
                <div class="fs-6">Unit PS Tersedia</div>
                <div class="display-6 fw-bold">{{ $availableUnits ?? 0 }}</div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="kpi-pill">
                <div class="fs-6">Transaksi Hari Ini</div>
                <div class="display-6 fw-bold">{{ $todaysTransactions ?? 0 }}</div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            @php $revTotal7 = is_array($revData ?? null) ? array_sum($revData) : 0; @endphp
            <div class="kpi-pill">
                <div class="fs-6">Pendapatan 7 Hari</div>
                <div class="display-6 fw-bold">Rp. {{ number_format($revTotal7,0,',','.') }}</div>
            </div>
        </div>
    </div>

    <div class="card p-3 mb-4">
        <h6 class="mb-3 text-light">Pendapatan 7 Hari Terakhir</h6>
        <canvas id="revChart" height="90"></canvas>
    </div>

    <div class="card p-3">
        <h6 class="mb-3 text-light">Transaksi Terbaru</h6>
        <div class="table-responsive">
            <table class="custom-table align-middle">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Pelanggan</th>
                        <th>Item Disewa</th>
                        <th>Durasi</th>
                        <th>Biaya</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($recentTransactions ?? []) as $t)
                        <tr>
                            <td>{{ $t->kode ?? $t->id }}</td>
                            <td>{{ $t->customer->name ?? $t->nama_pelanggan ?? '-' }}</td>
                            <td>
                                @if($t->items && $t->items->count() > 0)
                                    @foreach($t->items as $item)
                                        @if($item->rentable_type === 'App\\Models\\UnitPS')
                                            Unit PS: {{ $item->rentable->nama ?? $item->rentable->name ?? 'N/A' }}<br>
                                        @elseif($item->rentable_type === 'App\\Models\\Game')
                                            Game: {{ $item->rentable->judul ?? $item->rentable->title ?? 'N/A' }}<br>
                                        @elseif($item->rentable_type === 'App\\Models\\Accessory')
                                            Aksesoris: {{ $item->rentable->nama ?? $item->rentable->name ?? 'N/A' }}<br>
                                        @else
                                            Lainnya: {{ $item->rentable_type ?? 'Tidak diketahui' }}<br>
                                        @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php
                                    $start = isset($t->start_at) ? \Carbon\Carbon::parse($t->start_at) : null;
                                    $end = isset($t->due_at) ? \Carbon\Carbon::parse($t->due_at) : null;
                                    
                                    if ($start && $end) {
                                        $diff = $start->diff($end);
                                        $totalHours = $diff->h + ($diff->days * 24);
                                        
                                        if ($totalHours >= 24) {
                                            $days = floor($totalHours / 24);
                                            $remainingHours = $totalHours % 24;
                                            $dur = $days . ' Hari';
                                            if ($remainingHours > 0) {
                                                $dur .= ' ' . $remainingHours . ' Jam';
                                            }
                                        } else {
                                            $dur = $totalHours . ' Jam';
                                        }
                                    } else {
                                        $dur = $t->durasi ?? '-';
                                    }
                                @endphp
                                {{ $dur }}
                            </td>
                            <td>
                                @php
                                    $amount = $t->total ?? $t->biaya ?? 0;
                                @endphp
                                Rp. {{ number_format($amount,0,',','.') }}
                            </td>
                            <td>
                                @php $st = $t->status ?? 'selesai'; @endphp
                                <span class="badge {{ $st==='selesai' || $st==='paid' ? 'text-bg-success' : ($st==='active' || $st==='ongoing' ? 'text-bg-primary' : ($st==='pending' ? 'text-bg-warning text-dark' : ($st==='cancelled' ? 'text-bg-danger' : 'text-bg-secondary'))) }}">
                                    @if($st == 'selesai' || $st == 'returned')
                                        Selesai
                                    @elseif($st == 'active' || $st == 'ongoing')
                                        Aktif
                                    @elseif($st == 'pending')
                                        Menunggu
                                    @elseif($st == 'cancelled')
                                        Dibatalkan
                                    @elseif($st == 'paid')
                                        Lunas
                                    @else
                                        {{ ucfirst($st) }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada transaksi terbaru</td>
        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <style>
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            color: #eef0ff;
            background-color: #49497A;
        }
        .custom-table th {
            background-color: #2d3192;
            color: #dbe0ff;
            padding: 0.75rem;
            text-align: left;
            border: 1px solid rgba(255,255,255,.08);
        }
        .custom-table td {
            padding: 0.75rem;
            border: 1px solid rgba(255,255,255,.08);
            color: #eef0ff;
        }
        .custom-table tbody tr {
            background-color: #49497A !important;
        }
        .custom-table tbody tr:hover {
            background-color: #5a5a8a !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            const el = document.getElementById('revChart');
            if(!el) return;
            const labels = @json($revLabels ?? []);
            const data = @json($revData ?? []);
            const ctx = el.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.2)',
                        tension: .3,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: 'rgba(255,255,255,.08)' } },
                        y: { grid: { color: 'rgba(255,255,255,.08)' }, ticks: { callback: v => new Intl.NumberFormat('id-ID').format(v) } }
                    }
                }
            });
        })();
    </script>
@endsection

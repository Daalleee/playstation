@extends('owner.layout')
@section('title','Dashboard Pemilik')
@section('owner_content')
    @if(session('impersonate_admin_id'))
        <form action="{{ route('admin.impersonate.leave') }}" method="POST" class="mb-2">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-arrow-return-left me-1"></i> Kembali ke Admin</button>
        </form>
    @endif

    <div class="text-center mb-4">
        <h1 class="h3">Dashboard Pemilik</h1>
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
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Pelanggan</th>
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
                                @php
                                    $start = isset($t->start_at) ? \Carbon\Carbon::parse($t->start_at) : null;
                                    $end = isset($t->due_at) ? \Carbon\Carbon::parse($t->due_at) : null;
                                    $dur = ($start && $end) ? $start->diffForHumans($end, [ 'parts'=>2, 'short'=>true, 'syntax'=>\Carbon\CarbonInterface::DIFF_ABSOLUTE ]) : ($t->durasi ?? '-');
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
                                <span class="badge {{ $st==='selesai' || $st==='paid' ? 'text-bg-success' : ($st==='active' ? 'text-bg-primary' : 'text-bg-secondary') }}">{{ ucfirst($st) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada transaksi terbaru</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($recentTransactions) && method_exists($recentTransactions,'links'))
            <div class="d-flex justify-content-center">{{ $recentTransactions->links() }}</div>
        @endif
    </div>
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

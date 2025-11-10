@extends('admin.layout')
@section('title','Laporan - Admin')
@section('admin_content')
        <style>
            /* Apply the same purple theme as dashboard */
            .metric{background:#2E236C;border-radius:12px;padding:16px; color: white;}
            .metric .text-muted{color:#e6e8ff !important}
            .metric .fs-4,.metric .fs-5{color:#e6e8ff}
            .table-container{ max-height: 420px; overflow:auto; border-radius:12px; background: #17153B; }
            .table thead th{ position: sticky; top: 0; z-index: 1; }
            @media (max-width: 575.98px){ .metric{ padding:12px } }
            
            /* Apply the same purple theme and grid lines as dashboard */
            .card{background: #17153B; color: white; border:0; box-shadow: 0 6px 24px rgba(0,0,0,.25)}
            .table{color: white; background-color: #17153B; border-collapse: collapse;}
            .table thead{background: #2E236C;}
            .table thead th{background: #2E236C;color:#dbe0ff;border:1px solid #433D8B; padding: 0.75rem;}
            .table tbody{background: #17153B;}
            .table tbody tr{background: #17153B; transition: background-color 0.2s ease;}
            .table tbody tr:hover{background: #2E236C;}
            .table tbody tr+tr{border-top: 1px solid #433D8B;}
            .table td, .table th{background-color: inherit; color: white; border:1px solid #433D8B; padding: 0.75rem;}
            .table-responsive{background: #17153B;}
            
            /* Bright text for empty states */
            .text-muted {color: #e6e8ff !important;}
            
            /* Override any Bootstrap default styles */
            .table>:not(caption)>*>*{background-color: inherit; color: white;}
            .table *, .card * {background-color: inherit;}
        </style>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 m-0">Laporan</h1>
        </div>
        <div class="container-fluid px-2 px-md-0">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="metric">
                        <div class="text-muted">Pendapatan Hari Ini</div>
                        <div class="fs-4 fw-bold">Rp {{ number_format($revenueToday ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric">
                        <div class="text-muted">Pendapatan Bulan Ini</div>
                        <div class="fs-4 fw-bold">Rp {{ number_format($revenueMonth ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric">
                        <div class="text-muted">Total Pendapatan</div>
                        <div class="fs-4 fw-bold">Rp {{ number_format($revenueTotal ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <div class="metric">
                        <div class="text-muted">Total Transaksi</div>
                        <div class="fs-5 fw-semibold">{{ $rentalsTotal ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric">
                        <div class="text-muted">Transaksi Aktif</div>
                        <div class="fs-5 fw-semibold">{{ $rentalsActive ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric">
                        <div class="text-muted">Transaksi Selesai</div>
                        <div class="fs-5 fw-semibold">{{ $rentalsReturned ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="card p-3 mt-3">
                <h6 class="mb-3 text-light">Pembayaran Terbaru</h6>
                <div class="table-responsive table-container">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($latestPayments ?? []) as $pay)
                            <tr>
                                <td>{{ $pay->created_at?->format('d M Y H:i') ?? $pay->paid_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>{{ $pay->rental?->customer?->name ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($pay->amount ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center" style="color: #e6e8ff;">Belum ada pembayaran</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection

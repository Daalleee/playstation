```html
@extends('admin.layout')
@section('title','Laporan - Admin')
@section('admin_content')
    <!-- Header with gradient -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
        <div>
            <h1 class="h3 m-0 fw-bold gradient-text">
                <i class="bi bi-graph-up-arrow me-2"></i>Laporan
            </h1>
            <p class="text-muted mb-0 small">Ringkasan kinerja bisnis Anda</p>
        </div>
    </div>

    <div class="container-fluid px-2 px-md-0">
        <!-- Revenue Cards with Gradient -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="premium-metric gradient-purple animate-slide-up" style="animation-delay: 0.1s">
                    <div class="metric-icon">
                        <i class="bi bi-calendar-day"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-label">Pendapatan Hari Ini</div>
                        <div class="metric-value">Rp {{ number_format($revenueToday ?? 0, 0, ',', '.') }}</div>
                        <div class="metric-trend">
                            <i class="bi bi-arrow-up-short"></i>
                            <span>Hari ini</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="premium-metric gradient-blue animate-slide-up" style="animation-delay: 0.2s">
                    <div class="metric-icon">
                        <i class="bi bi-calendar-month"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-label">Pendapatan Bulan Ini</div>
                        <div class="metric-value">Rp {{ number_format($revenueMonth ?? 0, 0, ',', '.') }}</div>
                        <div class="metric-trend">
                            <i class="bi bi-arrow-up-short"></i>
                            <span>Bulan ini</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="premium-metric gradient-green animate-slide-up" style="animation-delay: 0.3s">
                    <div class="metric-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="metric-content">
                        <div class="metric-label">Total Pendapatan</div>
                        <div class="metric-value">Rp {{ number_format($revenueTotal ?? 0, 0, ',', '.') }}</div>
                        <div class="metric-trend">
                            <i class="bi bi-infinity"></i>
                            <span>Sepanjang waktu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card animate-slide-up" style="animation-delay: 0.4s">
                    <div class="stat-icon bg-primary-subtle">
                        <i class="bi bi-receipt text-primary"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Total Transaksi</div>
                        <div class="stat-number">{{ $rentalsTotal ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card animate-slide-up" style="animation-delay: 0.5s">
                    <div class="stat-icon bg-warning-subtle">
                        <i class="bi bi-hourglass-split text-warning"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Transaksi Aktif</div>
                        <div class="stat-number">{{ $rentalsActive ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card animate-slide-up" style="animation-delay: 0.6s">
                    <div class="stat-icon bg-success-subtle">
                        <i class="bi bi-check-circle text-success"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Transaksi Selesai</div>
                        <div class="stat-number">{{ $rentalsReturned ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments Table with Premium Design -->
        <div class="card premium-table-card animate-slide-up" style="animation-delay: 0.7s">
            <div class="card-body p-0">
                <div class="table-header">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-cash-stack me-2"></i>
                        Pembayaran Terbaru
                    </h6>
                    <span class="badge bg-primary-subtle">{{ count($latestPayments ?? []) }} Transaksi</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 premium-table">
                        <thead>
                            <tr>
                                <th>
                                    <i class="bi bi-calendar3 me-2"></i>Tanggal
                                </th>
                                <th>
                                    <i class="bi bi-person me-2"></i>Customer
                                </th>
                                <th>
                                    <i class="bi bi-credit-card me-2"></i>Metode
                                </th>
                                <th class="text-end">
                                    <i class="bi bi-currency-dollar me-2"></i>Jumlah
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($latestPayments ?? []) as $index => $pay)
                            <tr class="table-row-hover" style="animation-delay: {{ 0.8 + ($index * 0.05) }}s">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="date-badge">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <span class="text-light">{{ ($pay->paid_at ?? $pay->transaction_time ?? $pay->created_at)?->format('d M Y H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm">
                                            {{ substr($pay->rental?->customer?->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="text-light fw-medium">{{ $pay->rental?->customer?->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge method-badge">
                                        @if(strtoupper($pay->method ?? '') === 'MIDTRANS')
                                            <i class="bi bi-credit-card-2-front me-1"></i>
                                        @elseif(strtoupper($pay->method ?? '') === 'CASH')
                                            <i class="bi bi-cash me-1"></i>
                                        @else
                                            <i class="bi bi-wallet2 me-1"></i>
                                        @endif
                                        {{ strtoupper($pay->method ?? '-') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="amount-text">Rp {{ number_format($pay->amount ?? 0, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Belum ada pembayaran</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Gradient Text */
    .gradient-text {
        background: linear-gradient(135deg, #a5b4fc 0%, #818cf8 50%, #6366f1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { 
            opacity: 0; 
            transform: translateY(30px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    .animate-slide-up {
        animation: slideUp 0.6s ease-out backwards;
    }

    /* Premium Metric Cards */
    .premium-metric {
        position: relative;
        padding: 1.75rem;
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
    }

    .premium-metric::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.9;
        z-index: -1;
    }

    .gradient-purple::before {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }

    .gradient-blue::before {
        background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
    }

    .gradient-green::before {
        background: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
    }

    .premium-metric:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 60px rgba(99, 102, 241, 0.4);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .metric-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .metric-content {
        flex: 1;
    }

    .metric-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .metric-value {
        color: white;
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .metric-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.813rem;
        font-weight: 500;
    }

    .metric-trend i {
        font-size: 1.25rem;
    }

    /* Stat Cards */
    .stat-card {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        border-color: rgba(99, 102, 241, 0.4);
        background: rgba(30, 41, 59, 0.8);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .stat-number {
        color: var(--text-main);
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }

    /* Premium Table Card */
    .premium-table-card {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .table-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h6 {
        color: var(--text-main);
        font-size: 1.125rem;
    }

    /* Premium Table */
    .premium-table {
        --bs-table-bg: transparent;
    }

    .premium-table thead th {
        background: rgba(0, 0, 0, 0.3);
        color: var(--text-main);
        font-weight: 700;
        font-size: 0.813rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1.25rem 2rem;
        border: none;
    }

    .premium-table tbody td {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--text-muted);
    }

    .premium-table tbody tr {
        transition: all 0.2s ease;
    }

    .premium-table tbody tr:hover {
        background: rgba(99, 102, 241, 0.08);
        transform: scale(1.01);
    }

    /* Table Elements */
    .date-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(99, 102, 241, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a5b4fc;
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.875rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .method-badge {
        background: rgba(6, 182, 212, 0.2) !important;
        color: #67e8f9 !important;
        border: 1px solid rgba(6, 182, 212, 0.4);
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.813rem;
    }

    .amount-text {
        color: #6ee7b7;
        font-weight: 700;
        font-size: 1.125rem;
    }

    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .premium-metric {
            flex-direction: column;
            text-align: center;
        }

        .metric-icon {
            margin: 0 auto;
        }

        .stat-card {
            flex-direction: column;
            text-align: center;
        }

        .premium-table thead th,
        .premium-table tbody td {
            padding: 1rem;
        }

        .table-header {
            padding: 1rem;
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
@endpush
```

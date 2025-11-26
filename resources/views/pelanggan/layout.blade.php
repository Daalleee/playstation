@extends('layouts.dashboard')

@section('title', 'Dashboard Pelanggan')

@section('header_title', 'Area Member')

@section('sidebar_menu')
    <a href="{{ route('dashboard.pelanggan') }}" class="nav-link {{ request()->routeIs('dashboard.pelanggan') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Beranda">
        <i class="bi bi-grid"></i>
        <span>Beranda</span>
    </a>
    
    <div class="sidebar-heading">Belanja</div>
    
    <a href="{{ route('pelanggan.unitps.index') }}" class="nav-link {{ request()->routeIs('pelanggan.unitps.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Sewa PS">
        <i class="bi bi-controller"></i>
        <span>Sewa PS</span>
    </a>
    <a href="{{ route('pelanggan.games.index') }}" class="nav-link {{ request()->routeIs('pelanggan.games.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Sewa Game">
        <i class="bi bi-disc"></i>
        <span>Sewa Game</span>
    </a>
    <a href="{{ route('pelanggan.accessories.index') }}" class="nav-link {{ request()->routeIs('pelanggan.accessories.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Sewa Aksesoris">
        <i class="bi bi-headset"></i>
        <span>Sewa Aksesoris</span>
    </a>
    
    <div class="sidebar-heading">Transaksi</div>

    <a href="{{ route('pelanggan.cart.index') }}" class="nav-link {{ request()->routeIs('pelanggan.cart.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Keranjang">
        <i class="bi bi-cart"></i>
        <span>Keranjang</span>
    </a>
    <a href="{{ route('pelanggan.rentals.index') }}" class="nav-link {{ request()->routeIs('pelanggan.rentals.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Riwayat Sewa">
        <i class="bi bi-clock-history"></i>
        <span>Riwayat Sewa</span>
    </a>
    
    <div class="sidebar-heading">Akun</div>

    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Profil Saya">
        <i class="bi bi-person-circle"></i>
        <span>Profil Saya</span>
    </a>
@endsection

@push('styles')
<style>
    /* Premium Card Enhancements */
    .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(148, 163, 184, 0.2);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        transition: left 0.5s;
    }

    .card:hover::before {
        left: 100%;
    }

    .card-hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(59, 130, 246, 0.1);
    }

    .card-glow:hover {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.3), 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    /* Interactive Table Enhancements */
    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Premium Button Effects */
    .btn {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:active::after {
        width: 300px;
        height: 300px;
    }

    .btn-primary:hover, .btn-success:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }

    /* Badge Enhancements */
    .badge-pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
    }

    /* Form Input Enhancements */
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 0 12px rgba(59, 130, 246, 0.2);
        transform: translateY(-1px);
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Glassmorphism */
    .glass-card {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    /* Gradient Text */
    .gradient-text {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Hover Scale */
    .hover-scale:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    /* Dynamic Grid Resizing Logic */
    @media (min-width: 1200px) {
        /* Sidebar Open: 3 Columns (Wider Cards) */
        body:not(.sidebar-collapsed) .main-content .row-cols-lg-4 > *,
        body:not(.sidebar-collapsed) .main-content .row-cols-xl-4 > * {
            flex: 0 0 auto;
            width: 33.3333%;
        }
        
        /* Sidebar Collapsed: 5 Columns (Smaller Cards) */
        body.sidebar-collapsed .main-content .row-cols-lg-4 > *,
        body.sidebar-collapsed .main-content .row-cols-xl-4 > * {
            flex: 0 0 auto;
            width: 20%;
        }
    }

    /* Smooth Transition for Grid Items */
    .main-content .row > * {
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease;
    }

    /* Custom Tooltip Styling */
    .tooltip-inner {
        background-color: #1e293b; /* Dark background */
        color: #fff;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 8px 12px;
        font-family: 'Outfit', sans-serif;
        font-size: 0.85rem;
        border-radius: 6px;
    }
    
    .tooltip.bs-tooltip-end .tooltip-arrow::before {
        border-right-color: #1e293b;
    }
</style>
@endpush

@section('content')
    @yield('pelanggan_content')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover' // Explicitly set trigger to hover
            })
        });

        // Optional: Disable tooltips when sidebar is expanded (if desired)
        const body = document.body;
        const sidebar = document.getElementById('sidebar');
        
        function updateTooltips() {
            if (body.classList.contains('sidebar-collapsed')) {
                tooltipList.forEach(t => t.enable());
            } else {
                tooltipList.forEach(t => t.disable());
            }
        }

        // Initial check
        updateTooltips();

        // Listen for sidebar toggle events (assuming toggle button clicks toggle the class)
        const toggleBtn = document.getElementById('sidebarToggle');
        if(toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                // Wait a bit for class toggle to happen
                setTimeout(updateTooltips, 50);
            });
        }
    });
</script>
@endpush

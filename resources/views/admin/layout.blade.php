@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('header_title', 'Admin Dashboard')

@section('sidebar_menu')
    <a href="{{ route('dashboard.admin') }}" class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
        <i class="bi bi-grid"></i>
        <span>Beranda</span>
    </a>
    
    <div class="sidebar-heading">Pengguna</div>
    
    <a href="{{ route('admin.pelanggan.index') }}" class="nav-link {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Pelanggan</span>
    </a>
    <a href="{{ route('admin.pemilik.index') }}" class="nav-link {{ request()->routeIs('admin.pemilik.*') ? 'active' : '' }}">
        <i class="bi bi-person-workspace"></i>
        <span>Pemilik</span>
    </a>
    <a href="{{ route('admin.kasir.index') }}" class="nav-link {{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
        <i class="bi bi-person-vcard"></i>
        <span>Kasir</span>
    </a>
    <a href="{{ route('admin.admin.index') }}" class="nav-link {{ request()->routeIs('admin.admin.*') ? 'active' : '' }}">
        <i class="bi bi-shield-lock"></i>
        <span>Admin</span>
    </a>

    <div class="sidebar-heading">Inventaris</div>

    <a href="{{ route('admin.unitps.index') }}" class="nav-link {{ request()->routeIs('admin.unitps.*') ? 'active' : '' }}">
        <i class="bi bi-controller"></i>
        <span>Unit PS</span>
    </a>
    <a href="{{ route('admin.games.index') }}" class="nav-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
        <i class="bi bi-disc"></i>
        <span>Games</span>
    </a>
    <a href="{{ route('admin.accessories.index') }}" class="nav-link {{ request()->routeIs('admin.accessories.*') ? 'active' : '' }}">
        <i class="bi bi-headset"></i>
        <span>Aksesoris</span>
    </a>

    <div class="sidebar-heading">Laporan</div>

    <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>Laporan</span>
    </a>
@endsection

@section('content')
    @yield('admin_content')
@endsection

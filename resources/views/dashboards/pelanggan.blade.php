@extends('layouts.app')
@section('content')
@include('pelanggan.partials.nav')
<h1>Dashboard Pelanggan</h1>

<p>Selamat datang di dashboard pelanggan! Anda dapat melihat katalog unit PS, games, dan aksesoris yang tersedia untuk disewa.</p>

<div style="margin-top: 2rem;">
    <h2>Menu Utama:</h2>
    <ul>
        <li><a href="{{ route('pelanggan.profile.show') }}">Profil Saya</a> - Kelola informasi pribadi</li>
        <li><a href="{{ route('pelanggan.unitps.index') }}">Lihat Unit PS</a> - Lihat daftar unit PlayStation yang tersedia</li>
        <li><a href="{{ route('pelanggan.games.index') }}">Lihat Games</a> - Lihat daftar game yang tersedia</li>
        <li><a href="{{ route('pelanggan.accessories.index') }}">Lihat Aksesoris</a> - Lihat daftar aksesoris yang tersedia</li>
        <li><a href="{{ route('pelanggan.cart.index') }}">Keranjang</a> - Lihat item yang akan disewa</li>
        <li><a href="{{ route('pelanggan.rentals.index') }}">Riwayat Penyewaan</a> - Lihat riwayat penyewaan Anda</li>
    </ul>
</div>
@endsection

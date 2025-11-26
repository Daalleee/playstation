@extends('layouts.public')

@section('title', 'Syarat & Ketentuan - Rental PlayStation')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Syarat & Ketentuan</h1>
        <p>Harap baca dengan seksama sebelum melakukan penyewaan.</p>
    </div>
    <div class="page-content">
        <div class="content-card">
            <h2>1. Umum</h2>
            <p>Dengan melakukan penyewaan di Rental PlayStation, Anda setuju untuk mematuhi semua syarat dan ketentuan yang berlaku. Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>
            
            <h2>2. Persyaratan Penyewa</h2>
            <ul>
                <li>Penyewa wajib memiliki KTP/SIM asli yang masih berlaku sebagai jaminan.</li>
                <li>Penyewa wajib memberikan nomor telepon dan alamat yang valid.</li>
                <li>Penyewa bertanggung jawab penuh atas unit yang disewa selama masa penyewaan.</li>
            </ul>
            
            <h2>3. Pembayaran & Denda</h2>
            <ul>
                <li>Pembayaran dilakukan di muka (prepaid) sebelum unit diambil atau dikirim.</li>
                <li>Keterlambatan pengembalian akan dikenakan denda sebesar Rp 50.000 per jam.</li>
                <li>Kerusakan atau kehilangan unit/aksesoris akan dikenakan biaya penggantian sesuai harga pasar saat itu.</li>
            </ul>

            <h2>4. Penggunaan Unit</h2>
            <p>Unit hanya boleh digunakan untuk keperluan pribadi dan tidak boleh dipindahtangankan atau disewakan kembali kepada pihak ketiga tanpa izin tertulis dari kami. Dilarang membongkar atau memodifikasi unit konsol.</p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-container {
        padding-top: 80px;
        min-height: 100vh;
        background: radial-gradient(circle at top, #003087 0%, #050505 50%);
    }
    .page-header {
        text-align: center;
        padding: 60px 20px;
    }
    .page-header h1 {
        font-size: 3rem;
        margin-bottom: 15px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    .page-header p {
        color: var(--text-secondary);
        font-size: 1.2rem;
    }
    .page-content {
        max-width: 800px;
        margin: 0 auto 60px;
        padding: 0 20px;
    }
    .content-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .content-card h2 {
        color: var(--ps-blue-light);
        margin-top: 30px;
        margin-bottom: 15px;
        font-size: 1.5rem;
    }
    .content-card h2:first-child {
        margin-top: 0;
    }
    .content-card p, .content-card li {
        color: var(--text-secondary);
        line-height: 1.8;
        margin-bottom: 15px;
        font-size: 1.05rem;
    }
    .content-card ul {
        padding-left: 20px;
    }
    .content-card li {
        margin-bottom: 10px;
    }
</style>
@endpush

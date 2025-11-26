@extends('layouts.public')

@section('title', 'Tentang Kami - Rental PlayStation')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Tentang Kami</h1>
        <p>Mengenal lebih dekat siapa kami dan misi kami.</p>
    </div>
    <div class="page-content">
        <div class="content-card">
            <h2>Siapa Kami?</h2>
            <p>Rental PlayStation adalah penyedia layanan sewa konsol game terkemuka yang berdedikasi untuk memberikan pengalaman gaming terbaik bagi semua orang. Kami percaya bahwa bermain game harus mudah diakses, terjangkau, dan menyenangkan tanpa harus mengeluarkan biaya besar untuk membeli konsol.</p>
            
            <h2>Misi Kami</h2>
            <p>Misi kami adalah menghubungkan gamers dengan teknologi terbaru. Kami menyediakan unit PlayStation 4 dan PlayStation 5 yang terawat dengan baik, dilengkapi dengan perpustakaan game yang luas, sehingga Anda bisa langsung bermain tanpa repot.</p>
            
            <h2>Kenapa Memilih Kami?</h2>
            <ul>
                <li><strong>Kualitas Terjamin:</strong> Unit kami selalu dicek dan dibersihkan sebelum disewakan.</li>
                <li><strong>Harga Transparan:</strong> Tidak ada biaya tersembunyi. Apa yang Anda lihat adalah apa yang Anda bayar.</li>
                <li><strong>Layanan Cepat:</strong> Proses pemesanan dan pengiriman yang cepat dan efisien.</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-container {
        padding-top: 80px; /* Navbar height */
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
        font-size: 1.8rem;
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
    strong {
        color: var(--text-primary);
    }
</style>
@endpush

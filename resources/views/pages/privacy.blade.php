@extends('layouts.public')

@section('title', 'Kebijakan Privasi - Rental PlayStation')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Kebijakan Privasi</h1>
        <p>Bagaimana kami mengelola dan melindungi data Anda.</p>
    </div>
    <div class="page-content">
        <div class="content-card">
            <h2>Pengumpulan Data</h2>
            <p>Kami mengumpulkan informasi pribadi yang Anda berikan saat mendaftar, seperti nama, alamat, nomor telepon, dan alamat email. Data ini digunakan semata-mata untuk keperluan administrasi penyewaan dan verifikasi identitas.</p>
            
            <h2>Penggunaan Data</h2>
            <p>Data Anda digunakan untuk:</p>
            <ul>
                <li>Memproses pesanan penyewaan Anda.</li>
                <li>Menghubungi Anda terkait status pesanan atau pengembalian.</li>
                <li>Meningkatkan layanan kami melalui feedback yang Anda berikan.</li>
            </ul>
            
            <h2>Perlindungan Data</h2>
            <p>Kami berkomitmen untuk menjaga keamanan data pribadi Anda. Kami tidak akan menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali jika diwajibkan oleh hukum.</p>
            
            <h2>Cookie</h2>
            <p>Website kami menggunakan cookie untuk meningkatkan pengalaman pengguna. Anda dapat mengatur browser Anda untuk menolak cookie, namun hal ini mungkin mempengaruhi fungsionalitas website.</p>
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

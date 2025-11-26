@extends('layouts.public')

@section('title', 'Kontak Kami - Rental PlayStation')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Kontak Kami</h1>
        <p>Hubungi kami untuk pertanyaan atau bantuan lebih lanjut.</p>
    </div>
    <div class="page-content">
        <div class="contact-grid">
            <div class="content-card contact-info">
                <h2>Informasi Kontak</h2>
                <p>Jangan ragu untuk menghubungi kami melalui saluran berikut:</p>
                
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Alamat</h3>
                        <p>Jl. Gaming No. 1, Jakarta Selatan, Indonesia</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Telepon / WhatsApp</h3>
                        <p>+62 812 3456 7890</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>support@rentalps.com</p>
                    </div>
                </div>

                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <div class="content-card contact-form-card">
                <h2>Kirim Pesan</h2>
                <form>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" placeholder="Nama Anda">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Email Anda">
                    </div>
                    <div class="form-group">
                        <label>Pesan</label>
                        <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda di sini..."></textarea>
                    </div>
                    <button type="button" class="btn-hero btn-primary w-100">Kirim Pesan</button>
                </form>
            </div>
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
        max-width: 1000px;
        margin: 0 auto 60px;
        padding: 0 20px;
    }
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }
    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }
    .content-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .contact-info h2, .contact-form-card h2 {
        color: var(--ps-blue-light);
        margin-bottom: 25px;
        font-size: 1.8rem;
    }
    .contact-item {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }
    .contact-item i {
        font-size: 1.5rem;
        color: var(--ps-blue-light);
        width: 30px;
        text-align: center;
        margin-top: 5px;
    }
    .contact-item h3 {
        font-size: 1.1rem;
        color: var(--text-primary);
        margin-bottom: 5px;
    }
    .contact-item p {
        color: var(--text-secondary);
        margin: 0;
    }
    .social-links {
        display: flex;
        gap: 20px;
        margin-top: 40px;
    }
    .social-links a {
        color: var(--text-secondary);
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    .social-links a:hover {
        color: var(--ps-blue-light);
        transform: translateY(-3px);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        color: var(--text-primary);
        margin-bottom: 8px;
        font-weight: 500;
    }
    .form-control {
        width: 100%;
        padding: 12px 15px;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--ps-blue-light);
        background: rgba(255,255,255,0.1);
    }
    .w-100 {
        width: 100%;
        justify-content: center;
        margin-top: 10px;
        border: none;
        cursor: pointer;
    }
</style>
@endpush

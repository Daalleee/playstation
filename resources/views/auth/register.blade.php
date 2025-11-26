@extends('layouts.auth')

@section('title', 'Registrasi - PlayStation Rental')

@section('content')
    <div class="auth-header">
        <h1 class="auth-title">Buat Akun Baru</h1>
        <p class="auth-subtitle">Bergabunglah dengan kami dan mulai menyewa</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <div class="input-wrapper">
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    placeholder="Nama Lengkap"
                    required 
                    autofocus
                    autocomplete="name"
                >
                <i class="fas fa-user input-icon"></i>
            </div>
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Alamat</label>
            <div class="input-wrapper">
                <input 
                    type="text" 
                    class="form-control @error('address') is-invalid @enderror" 
                    id="address" 
                    name="address" 
                    value="{{ old('address') }}"
                    placeholder="Alamat Lengkap"
                    required
                    autocomplete="street-address"
                >
                <i class="fas fa-home input-icon"></i>
            </div>
            @error('address')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon</label>
            <div class="input-wrapper">
                <input 
                    type="tel" 
                    class="form-control @error('phone') is-invalid @enderror" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    placeholder="Nomor Telepon (WA)"
                    required
                    inputmode="tel"
                    autocomplete="tel-national"
                >
                <i class="fas fa-phone input-icon"></i>
            </div>
            @error('phone')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="Email Aktif"
                    required
                    autocomplete="email"
                >
                <i class="fas fa-envelope input-icon"></i>
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password" 
                    placeholder="Buat Password"
                    required
                    autocomplete="new-password"
                >
                <i class="fas fa-lock input-icon"></i>
                <button type="button" class="password-toggle">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <div class="input-wrapper">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Ulangi Password"
                    required
                    autocomplete="new-password"
                >
                <i class="fas fa-lock input-icon"></i>
                <button type="button" class="password-toggle">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-check">
            <div class="form-check-left">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">Saya setuju dengan <a class="auth-link" href="#" id="terms-link">Syarat & Ketentuan</a></label>
            </div>
        </div>

        <button type="submit" class="btn-auth btn-primary-auth">
            <span>Daftar Sekarang</span>
            <i class="fas fa-user-plus"></i>
        </button>

        <div class="auth-divider">
            <span>atau daftar dengan</span>
        </div>

        <a href="{{ route('auth.google.redirect') }}" class="btn-auth btn-google">
            <img src="https://www.google.com/favicon.ico" alt="Google">
            Daftar dengan Google
        </a>

        <div class="auth-bottom-links">
            Sudah punya akun? <a href="{{ route('login.show') }}" class="auth-link">Login di sini</a>
        </div>
    </form>

    <!-- Terms Modal -->
    <div id="terms-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Syarat & Ketentuan</h3>
                <button type="button" class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Selamat datang di PlayStation Rental. Harap baca syarat dan ketentuan berikut dengan seksama sebelum mendaftar:</p>
                <ol>
                    <li>Penyewa wajib menyertakan identitas asli (KTP/SIM/Kartu Pelajar) saat melakukan penyewaan.</li>
                    <li>Penyewa bertanggung jawab penuh atas kerusakan atau kehilangan unit yang disewa.</li>
                    <li>Keterlambatan pengembalian akan dikenakan denda sesuai ketentuan yang berlaku.</li>
                    <li>Dilarang memindah-tangankan unit sewaan kepada pihak ketiga tanpa izin.</li>
                    <li>Pembayaran sewa dilakukan di muka atau sesuai kesepakatan.</li>
                    <li>Kami berhak menolak penyewaan jika persyaratan tidak terpenuhi.</li>
                </ol>
                <p>Dengan mendaftar, Anda menyetujui semua ketentuan di atas.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-decline" id="btn-decline">Decline</button>
                <button type="button" class="btn-modal btn-accept" id="btn-accept">Accept</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('terms-modal');
            const link = document.getElementById('terms-link');
            const closeBtn = document.querySelector('.close-modal');
            const acceptBtn = document.getElementById('btn-accept');
            const declineBtn = document.getElementById('btn-decline');
            const checkbox = document.getElementById('terms');

            // Open modal
            link.addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.add('active');
            });

            // Close modal functions
            function closeModal() {
                modal.classList.remove('active');
            }

            closeBtn.addEventListener('click', closeModal);
            
            // Close on outside click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Accept button
            acceptBtn.addEventListener('click', function() {
                checkbox.checked = true;
                closeModal();
            });

            // Decline button
            declineBtn.addEventListener('click', function() {
                checkbox.checked = false;
                closeModal();
            });
        });
    </script>
    @endpush
@endsection

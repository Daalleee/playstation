@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold text-white"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Profil</h4>
                <p class="mb-0 text-muted small">Perbarui informasi akun dan data diri Anda</p>
            </div>
            <div>
                <a href="{{ route('pelanggan.profile.show') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success border-0 bg-success-subtle text-success mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4 d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning border-0 bg-warning-subtle text-warning mb-4 d-flex align-items-start">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1">Profil Belum Lengkap!</h6>
                <p class="mb-0 small">{{ session('warning') }}</p>
            </div>
        </div>
    @endif
    
    @if(empty($user->phone) || empty($user->address))
        <div class="alert alert-info border-0 bg-info-subtle text-info mb-4 d-flex align-items-start">
            <i class="bi bi-info-circle-fill me-3 fs-4 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1">Informasi Penting</h6>
                <p class="mb-0 small">Nomor HP dan Alamat <strong>WAJIB</strong> diisi untuk melakukan pemesanan rental. Lengkapi data Anda sekarang!</p>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('pelanggan.profile.update') }}">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-12">
                        <h5 class="text-white fw-bold border-bottom border-secondary pb-2 mb-3">Informasi Dasar</h5>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small text-uppercase">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                   class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror">
                        </div>
                        @error('name')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small text-uppercase">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                   class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror">
                        </div>
                        @error('email')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-12 mt-4">
                        <h5 class="text-white fw-bold border-bottom border-secondary pb-2 mb-3">Kontak & Alamat</h5>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small text-uppercase">Nomor Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required 
                                   class="form-control bg-dark text-light border-secondary @error('phone') is-invalid @enderror" 
                                   placeholder="Contoh: 081234567890">
                        </div>
                        @error('phone')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small text-uppercase">Alamat Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}" required 
                                   class="form-control bg-dark text-light border-secondary @error('address') is-invalid @enderror" 
                                   placeholder="Jalan, Nomor Rumah, Kota">
                        </div>
                        @error('address')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-12 mt-4">
                        <h5 class="text-white fw-bold border-bottom border-secondary pb-2 mb-3">Keamanan</h5>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small text-uppercase">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" 
                                   class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror" 
                                   placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>
                        @error('password')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small text-uppercase">Konfirmasi Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password_confirmation" 
                                   class="form-control bg-dark text-light border-secondary" 
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                    
                    <div class="col-12 mt-5 d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-lg">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('pelanggan.profile.show') }}" class="btn btn-outline-secondary btn-lg px-4">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
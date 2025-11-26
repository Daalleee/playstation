@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold text-primary"><i class="bi bi-person-circle me-2 text-primary"></i>Profil Pelanggan</h4>
                <p class="mb-0 text-muted small">Kelola informasi akun dan data diri Anda</p>
            </div>
            <div>
                <a href="{{ route('pelanggan.profile.edit') }}" class="btn btn-primary fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profil
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

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card h-100 text-center p-4">
                <div class="card-body">
                    <div class="position-relative d-inline-block mb-4">
                        <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center mx-auto shadow-lg" 
                             style="width: 120px; height: 120px; border: 4px solid var(--card-border);">
                            <i class="bi bi-person-fill display-1 text-muted"></i>
                        </div>
                        <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-dark p-2" 
                             style="width: 24px; height: 24px;"></div>
                    </div>
                    
                    <h4 class="fw-bold text-white mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-4">{{ $user->email }}</p>
                    
                    <div class="d-flex flex-column gap-2 text-start">
                        <div class="p-3 rounded bg-dark border border-secondary">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Status Akun</small>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge bg-success-subtle">Aktif</span>
                                <i class="bi bi-shield-check text-success ms-auto"></i>
                            </div>
                        </div>
                        <div class="p-3 rounded bg-dark border border-secondary">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Bergabung Sejak</small>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="text-white fw-bold">{{ $user->created_at->format('d M Y') }}</span>
                                <i class="bi bi-calendar-check text-primary ms-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom border-secondary">
                    <h5 class="mb-0 fw-bold text-white">Informasi Pribadi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-2">Nama Lengkap</label>
                                <div class="form-control bg-dark text-white border-secondary">{{ $user->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-2">Email</label>
                                <div class="form-control bg-dark text-white border-secondary">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-2">Nomor Telepon</label>
                                <div class="form-control bg-dark text-white border-secondary d-flex align-items-center justify-content-between">
                                    <span>{{ $user->phone ?? '-' }}</span>
                                    @if(empty($user->phone))
                                        <i class="bi bi-exclamation-circle text-warning" data-bs-toggle="tooltip" title="Wajib diisi untuk penyewaan"></i>
                                    @else
                                        <i class="bi bi-check-circle text-success"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-2">Alamat Lengkap</label>
                                <div class="form-control bg-dark text-white border-secondary d-flex align-items-center justify-content-between">
                                    <span class="text-truncate">{{ $user->address ?? '-' }}</span>
                                    @if(empty($user->address))
                                        <i class="bi bi-exclamation-circle text-warning" data-bs-toggle="tooltip" title="Wajib diisi untuk penyewaan"></i>
                                    @else
                                        <i class="bi bi-check-circle text-success"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(empty($user->phone) || empty($user->address))
                        <div class="alert alert-warning border-0 bg-warning-subtle text-warning mt-4 mb-0 d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Profil Belum Lengkap</h6>
                                <p class="mb-2 small">Mohon lengkapi <strong>Nomor Telepon</strong> dan <strong>Alamat</strong> Anda untuk dapat melakukan penyewaan unit atau game.</p>
                                <a href="{{ route('pelanggan.profile.edit') }}" class="btn btn-sm btn-warning fw-bold text-dark">Lengkapi Sekarang</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
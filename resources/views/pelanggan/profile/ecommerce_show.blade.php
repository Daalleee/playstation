@extends('layouts.ecommerce')

@section('title', 'Profil Saya - PlayStation Rental')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profil Saya</h4>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="avatar mb-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; margin: 0 auto;">
                                        <i class="bi bi-person-circle fs-1 text-white"></i>
                                    </div>
                                </div>
                                <h5 class="fw-bold">{{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->email }}</p>
                                
                                <div class="d-grid gap-2">
                                    <span class="badge bg-success mb-2">Status: Aktif</span>
                                    <small class="text-muted">Terdaftar: {{ $user->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                        <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Telepon</label>
                                        <input type="text" class="form-control" value="{{ $user->phone ?? '-' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Alamat</label>
                                        <textarea class="form-control" rows="3" readonly>{{ $user->address ?? '-' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('pelanggan.profile.edit') }}" class="btn btn-primary flex-grow-1">
                                            <i class="bi bi-pencil me-1"></i>Edit Profil
                                        </a>
                                        <a href="{{ route('dashboard.pelanggan') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-1"></i>Kembali
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
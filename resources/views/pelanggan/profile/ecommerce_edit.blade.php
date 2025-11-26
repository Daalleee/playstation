@extends('layouts.ecommerce')

@section('title', 'Edit Profil - PlayStation')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Profil</h4>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                        </div>
                    @endif

                    @if(empty($user->phone) || empty($user->address))
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi Penting:</strong> Nomor HP dan Alamat <strong>WAJIB</strong> diisi untuk melakukan pemesanan rental. Lengkapi data Anda sekarang!
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pelanggan.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="form-control" placeholder="Contoh: +6281234567890">
                                @error('phone')
                                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alamat <span class="text-danger">*</span></label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" required class="form-control" placeholder="Alamat lengkap Anda">
                                @error('address')
                                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Password Baru (kosongkan jika tidak ingin diubah)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                                    </button>
                                    <a href="{{ route('pelanggan.profile.show') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('admin.layout')
@section('title','Edit Pelanggan - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Edit Pelanggan</h1>
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="card p-3">
        <form method="POST" action="{{ route('admin.pelanggan.update', $pelanggan) }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="name" value="{{ old('name', $pelanggan->name) }}" required class="form-control">
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" required class="form-control">
                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Password (kosongkan jika tidak diganti)</label>
                <input type="password" name="password" class="form-control">
                @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">No HP</label>
                <input type="text" name="phone" value="{{ old('phone', $pelanggan->phone) }}" class="form-control">
                @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <input type="text" name="address" value="{{ old('address', $pelanggan->address) }}" class="form-control">
                @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
            </div>
        </form>
    </div>
@endsection

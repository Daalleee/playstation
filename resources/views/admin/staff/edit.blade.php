@extends('admin.layout')
@section('title','Edit ' . ucfirst($role) . ' - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Edit Akun {{ ucfirst($role) }}</h1>
        <a href="{{ route('admin.' . $role . '.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-4">
        <form method="POST" action="{{ route('admin.' . $role . '.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password (kosongkan jika tidak diganti)</label>
                <input type="password" class="form-control" name="password">
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('admin.' . $role . '.index') }}" class="btn btn-danger"><i class="bi bi-x me-1"></i> Batal</a>
            </div>
        </form>
    </div>
@endsection
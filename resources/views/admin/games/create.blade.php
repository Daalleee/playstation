@extends('admin.layout')
@section('title','Tambah Game - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Tambah Game</h1>
        <a href="{{ route('admin.games.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
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
        <form method="POST" action="{{ route('admin.games.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="judul" value="{{ old('judul') }}" required>
                @error('judul')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Platform</label>
                <input type="text" class="form-control" name="platform" value="{{ old('platform') }}" required>
                @error('platform')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Genre</label>
                <input type="text" class="form-control" name="genre" value="{{ old('genre') }}">
                @error('genre')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" class="form-control" name="stok" value="{{ old('stok',0) }}" required>
                @error('stok')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Harga/Hari</label>
                <input type="number" step="0.01" class="form-control" name="harga_per_hari" value="{{ old('harga_per_hari',0) }}" required>
                @error('harga_per_hari')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" class="form-control" name="gambar" accept="image/*">
                @error('gambar')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <input type="text" class="form-control" name="kondisi" value="Baik">
                @error('kondisi')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.games.index') }}" class="btn btn-danger"><i class="bi bi-x me-1"></i> Batal</a>
            </div>
        </form>
    </div>
@endsection


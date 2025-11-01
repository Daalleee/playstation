@extends('admin.layout')
@section('title','Edit Aksesoris - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Edit Aksesoris</h1>
        <a href="{{ route('admin.accessories.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
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
        <form method="POST" action="{{ route('admin.accessories.update', $accessory) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama" value="{{ old('nama', $accessory->nama) }}" required>
                @error('nama')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Jenis</label>
                <input type="text" class="form-control" name="jenis" value="{{ old('jenis', $accessory->jenis) }}" required>
                @error('jenis')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" class="form-control" name="stok" value="{{ old('stok', $accessory->stok) }}" required>
                @error('stok')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Harga/Hari</label>
                <input type="number" step="0.01" class="form-control" name="harga_per_hari" value="{{ old('harga_per_hari', $accessory->harga_per_hari) }}" required>
                @error('harga_per_hari')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" class="form-control" name="gambar" accept="image/*">
                @if($accessory->gambar)
                    <div class="mt-2">
                        <p>Gambar saat ini:</p>
                        <img src="{{ asset('storage/'.$accessory->gambar) }}" alt="gambar" style="max-height:80px" class="img-thumbnail">
                    </div>
                @endif
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
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('admin.accessories.index') }}" class="btn btn-danger"><i class="bi bi-x me-1"></i> Batal</a>
            </div>
        </form>
    </div>
@endsection


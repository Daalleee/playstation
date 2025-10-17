@extends('admin.layout')
@section('title','Tambah Unit PS - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Tambah Unit PS</h1>
        <a href="{{ route('admin.unitps.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="card p-3">
        <form method="POST" action="{{ route('admin.unitps.store') }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required class="form-control">
                @error('nama')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Merek</label>
                <input type="text" name="merek" value="{{ old('merek','Sony') }}" required class="form-control">
                @error('merek')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Model</label>
                <input type="text" name="model" value="{{ old('model') }}" required class="form-control">
                @error('model')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Seri</label>
                <input type="text" name="nomor_seri" value="{{ old('nomor_seri') }}" required class="form-control">
                @error('nomor_seri')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga per Jam</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="0.01" name="harga_per_jam" value="{{ old('harga_per_jam') }}" required class="form-control">
                </div>
                @error('harga_per_jam')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" value="{{ old('stok',1) }}" required class="form-control">
                @error('stok')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" required class="form-select">
                    <option value="available" {{ old('status')==='available'?'selected':'' }}>available</option>
                    <option value="rented" {{ old('status')==='rented'?'selected':'' }}>rented</option>
                    <option value="maintenance" {{ old('status')==='maintenance'?'selected':'' }}>maintenance</option>
                </select>
                @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Foto</label>
                <input type="file" name="foto" accept="image/*" class="form-control">
                @error('foto')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Kondisi</label>
                <input type="text" name="kondisi" value="{{ old('kondisi') }}" class="form-control" placeholder="Contoh: Mulus, ada sedikit baret">
                @error('kondisi')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
@endsection

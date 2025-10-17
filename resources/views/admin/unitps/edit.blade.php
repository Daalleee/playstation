@extends('admin.layout')
@section('title','Edit Unit PS - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Edit Unit PS</h1>
        <a href="{{ route('admin.unitps.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="card p-3">
        <form method="POST" action="{{ route('admin.unitps.update', $unit) }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $unit->nama) }}" required class="form-control">
                @error('nama')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Merek</label>
                <input type="text" name="merek" value="{{ old('merek', $unit->merek) }}" required class="form-control">
                @error('merek')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Model</label>
                <input type="text" name="model" value="{{ old('model', $unit->model) }}" required class="form-control">
                @error('model')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Seri</label>
                <input type="text" name="nomor_seri" value="{{ old('nomor_seri', $unit->nomor_seri) }}" required class="form-control">
                @error('nomor_seri')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga per Jam</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="0.01" name="harga_per_jam" value="{{ old('harga_per_jam', $unit->harga_per_jam) }}" required class="form-control">
                </div>
                @error('harga_per_jam')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $unit->stok) }}" required class="form-control">
                @error('stok')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" required class="form-select">
                    <option value="available" {{ old('status', $unit->status)==='available'?'selected':'' }}>available</option>
                    <option value="rented" {{ old('status', $unit->status)==='rented'?'selected':'' }}>rented</option>
                    <option value="maintenance" {{ old('status', $unit->status)==='maintenance'?'selected':'' }}>maintenance</option>
                </select>
                @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Foto</label>
                <input type="file" name="foto" accept="image/*" class="form-control" id="foto-input">
                @error('foto')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 d-flex align-items-end">
                @php
                    $imgUrl = null;
                    if ($unit->foto) {
                        if (str_starts_with($unit->foto, 'http://') || str_starts_with($unit->foto, 'https://')) {
                            $imgUrl = $unit->foto;
                        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($unit->foto)) {
                            $imgUrl = route('media', ['path' => $unit->foto]);
                        }
                    }
                @endphp
                <div id="preview-wrap" class="ms-md-3">
                    @if($imgUrl)
                        <img id="preview-img" src="{{ $imgUrl }}" alt="foto" style="max-height:100px" class="rounded border">
                    @else
                        <img id="preview-img" src="" alt="foto" style="max-height:100px; display:none" class="rounded border">
                    @endif
                </div>
            </div>
            <div class="col-12">
                <label class="form-label">Kondisi</label>
                <input type="text" name="kondisi" value="{{ old('kondisi', $unit->kondisi) }}" class="form-control" placeholder="Contoh: Mulus, ada sedikit baret">
                @error('kondisi')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
            </div>
        </form>
    </div>
    <script>
      (function(){
        const input = document.getElementById('foto-input');
        const img = document.getElementById('preview-img');
        if(!input || !img) return;
        input.addEventListener('change', (e)=>{
          const file = e.target.files && e.target.files[0];
          if(!file) return;
          const url = URL.createObjectURL(file);
          img.src = url; img.style.display = 'block';
        });
      })();
    </script>
@endsection

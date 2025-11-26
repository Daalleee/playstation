@extends('admin.layout')
@section('title', 'Edit Unit PS - Admin')
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
                @error('nama')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Merek</label>
                <input type="text" name="merek" value="{{ old('merek', $unit->merek) }}" required class="form-control">
                @error('merek')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Model</label>
                <input type="text" name="model" value="{{ old('model', $unit->model) }}" required class="form-control">
                @error('model')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', $unit->stok) }}" required min="1" class="form-control" onchange="updateSerialNumberInputs()">
                @error('stok')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Foto</label>
                <input type="file" name="foto" accept="image/*" class="form-control" id="foto-input">
                @error('foto')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
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
                    @if ($imgUrl)
                        <img id="preview-img" src="{{ $imgUrl }}" alt="foto" style="max-height:100px"
                            class="rounded border">
                    @else
                        <img id="preview-img" src="" alt="foto" style="max-height:100px; display:none"
                            class="rounded border">
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kondisi</label>
                <input type="text" name="kondisi" value="{{ old('kondisi', $unit->kondisi ?? 'Baik') }}"
                    class="form-control" placeholder="Contoh: Mulus, ada sedikit baret">
                @error('kondisi')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Tersedia" {{ old('status', $unit->status ?? 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Disewa" {{ old('status', $unit->status ?? 'Tersedia') == 'Disewa' ? 'selected' : '' }}>Disewa</option>
                    <option value="Maintenance" {{ old('status', $unit->status ?? 'Tersedia') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('admin.unitps.index') }}" class="btn btn-danger"><i class="bi bi-x me-1"></i>
                    Batal</a>
            </div>
        </form>
    </div>
    
    <script>
        (function() {
            const input = document.getElementById('foto-input');
            const img = document.getElementById('preview-img');
            if (!input || !img) return;
            input.addEventListener('change', (e) => {
                const file = e.target.files && e.target.files[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                img.src = url;
                img.style.display = 'block';
            });
        })();
        
        function updateSerialNumberInputs() {
            const stok = parseInt(document.getElementById('stok').value) || 1;
            const container = document.getElementById('serial-number-inputs');
            
            // Get existing values to preserve them when possible
            const existingValues = [];
            const existingInputs = container.querySelectorAll('input[name="serial_numbers[]"]');
            for (let i = 0; i < existingInputs.length; i++) {
                existingValues.push(existingInputs[i].value);
            }
            
            // Clear all inputs
            container.innerHTML = '';
            
            // Create new inputs based on stock value
            for (let i = 0; i < stok; i++) {
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                
                div.innerHTML = `
                    <span class="input-group-text">Unit ${i + 1}</span>
                    <input type="text" name="serial_numbers[]" value="${existingValues[i] || ''}" required class="form-control"
                        pattern="[A-Za-z0-9]+" title="Nomor seri hanya boleh berisi huruf dan angka"
                        oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')"
                        placeholder="Contoh: AB838MJ">
                `;
                
                container.appendChild(div);
            }
        }
    </script>
@endsection
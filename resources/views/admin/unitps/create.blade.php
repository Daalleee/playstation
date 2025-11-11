@extends('admin.layout')
@section('title', 'Tambah Unit PS - Admin')
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
                @error('nama')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Merek</label>
                <input type="text" name="merek" value="{{ old('merek', 'Sony') }}" required class="form-control">
                @error('merek')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Model</label>
                <input type="text" name="model" value="{{ old('model') }}" required class="form-control">
                @error('model')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', 1) }}" required min="1" class="form-control" onchange="updateSerialNumberInputs()">
                @error('stok')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Foto</label>
                <input type="file" name="foto" accept="image/*" class="form-control">
                @error('foto')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Kondisi</label>
                <input type="text" name="kondisi" value="Baik" class="form-control"
                    placeholder="Contoh: Mulus, ada sedikit baret">
                @error('kondisi')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga per Jam</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="0.01" name="harga_per_jam" value="{{ old('harga_per_jam') }}" required
                        class="form-control">
                </div>
                @error('harga_per_jam')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Dynamic Serial Number Inputs -->
            <div class="col-12">
                <label class="form-label">Nomor Seri Unit (isi sesuai jumlah stok)</label>
                <div id="serial-number-inputs">
                    <!-- Serial number inputs will be dynamically added here -->
                    <div class="input-group mb-2">
                        <span class="input-group-text">Unit 1</span>
                        <input type="text" name="serial_numbers[]" value="{{ old('serial_numbers.0') }}" required class="form-control"
                            pattern="[A-Za-z0-9]+" title="Nomor seri hanya boleh berisi huruf dan angka"
                            oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')"
                            placeholder="Contoh: AB838MJ">
                    </div>
                </div>
                <small class="text-white" style="opacity: 0.85;">Masukkan huruf atau angka tanpa karakter khusus</small>
                @error('serial_numbers')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                @error('serial_numbers.*')
                    <div class="text-danger small">Ada nomor seri yang tidak valid</div>
                @enderror
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.unitps.index') }}" class="btn btn-danger"><i class="bi bi-x me-1"></i> Batal</a>
            </div>
        </form>
    </div>
    
    <script>
        function updateSerialNumberInputs() {
            const stok = parseInt(document.getElementById('stok').value) || 1;
            const container = document.getElementById('serial-number-inputs');
            
            // Clear existing inputs except the first one
            while (container.children.length > 1) {
                container.removeChild(container.lastChild);
            }
            
            // Create new inputs based on stock value
            for (let i = 0; i < stok; i++) {
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                
                div.innerHTML = `
                    <span class="input-group-text">Unit ${i + 1}</span>
                    <input type="text" name="serial_numbers[]" required class="form-control"
                        pattern="[A-Za-z0-9]+" title="Nomor seri hanya boleh berisi huruf dan angka"
                        oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')"
                        placeholder="Contoh: AB838MJ">
                `;
                
                container.appendChild(div);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSerialNumberInputs();
        });
    </script>
@endsection

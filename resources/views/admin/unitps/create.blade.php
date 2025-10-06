@include('admin.partials.nav')
<h1>Tambah Unit PS</h1>
<form method="POST" action="{{ route('admin.unitps.store') }}" enctype="multipart/form-data">
    @csrf
<div><label>Nama</label><input type="text" name="nama" value="{{ old('nama') }}" required>@error('nama')<div>{{ $message }}</div>@enderror</div>
<div><label>Merek</label><input type="text" name="merek" value="{{ old('merek','Sony') }}" required>@error('merek')<div>{{ $message }}</div>@enderror</div>
<div><label>Model</label><input type="text" name="model" value="{{ old('model') }}" required>@error('model')<div>{{ $message }}</div>@enderror</div>
<div><label>Nomor Seri</label><input type="text" name="nomor_seri" value="{{ old('nomor_seri') }}" required>@error('nomor_seri')<div>{{ $message }}</div>@enderror</div>
<div><label>Harga per Jam</label><input type="number" step="0.01" name="harga_per_jam" value="{{ old('harga_per_jam') }}" required>@error('harga_per_jam')<div>{{ $message }}</div>@enderror</div>
<div><label>Stok</label><input type="number" name="stok" value="{{ old('stok',1) }}" required>@error('stok')<div>{{ $message }}</div>@enderror</div>
    <div>
        <label>Status</label>
        <select name="status" required>
            <option value="available" {{ old('status')==='available'?'selected':'' }}>available</option>
            <option value="rented" {{ old('status')==='rented'?'selected':'' }}>rented</option>
            <option value="maintenance" {{ old('status')==='maintenance'?'selected':'' }}>maintenance</option>
        </select>
        @error('status')<div>{{ $message }}</div>@enderror
    </div>
    <div><label>Foto (File)</label><input type="file" name="foto" accept="image/*">@error('foto')<div>{{ $message }}</div>@enderror</div>
    <div><label>Kondisi</label><input type="text" name="kondisi" value="{{ old('kondisi') }}">@error('kondisi')<div>{{ $message }}</div>@enderror</div>
    <button type="submit">Simpan</button>
</form>


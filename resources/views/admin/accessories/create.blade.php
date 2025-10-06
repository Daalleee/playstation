@include('admin.partials.nav')
<h1>Tambah Aksesoris</h1>
<form method="POST" action="{{ route('admin.accessories.store') }}" enctype="multipart/form-data">
    @csrf
<div><label>Nama</label><input type="text" name="nama" value="{{ old('nama') }}" required>@error('nama')<div>{{ $message }}</div>@enderror</div>
<div><label>Jenis</label><input type="text" name="jenis" value="{{ old('jenis') }}" required>@error('jenis')<div>{{ $message }}</div>@enderror</div>
<div><label>Stok</label><input type="number" name="stok" value="{{ old('stok',0) }}" required>@error('stok')<div>{{ $message }}</div>@enderror</div>
<div><label>Harga/Hari</label><input type="number" step="0.01" name="harga_per_hari" value="{{ old('harga_per_hari',0) }}" required>@error('harga_per_hari')<div>{{ $message }}</div>@enderror</div>
<div><label>Gambar (File)</label><input type="file" name="gambar" accept="image/*">@error('gambar')<div>{{ $message }}</div>@enderror</div>
<div><label>Kondisi</label><input type="text" name="kondisi" value="{{ old('kondisi') }}">@error('kondisi')<div>{{ $message }}</div>@enderror</div>
    <button type="submit">Simpan</button>
</form>


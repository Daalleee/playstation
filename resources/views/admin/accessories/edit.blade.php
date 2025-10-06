<h1>Edit Aksesoris</h1>
<form method="POST" action="{{ route('admin.accessories.update', $accessory) }}">
    @csrf
    @method('PUT')
<div><label>Nama</label><input type="text" name="nama" value="{{ old('nama', $accessory->nama) }}" required>@error('nama')<div>{{ $message }}</div>@enderror</div>
<div><label>Jenis</label><input type="text" name="jenis" value="{{ old('jenis', $accessory->jenis) }}" required>@error('jenis')<div>{{ $message }}</div>@enderror</div>
<div><label>Stok</label><input type="number" name="stok" value="{{ old('stok', $accessory->stok) }}" required>@error('stok')<div>{{ $message }}</div>@enderror</div>
<div><label>Harga/Hari</label><input type="number" step="0.01" name="harga_per_hari" value="{{ old('harga_per_hari', $accessory->harga_per_hari) }}" required>@error('harga_per_hari')<div>{{ $message }}</div>@enderror</div>
<div><label>Gambar (URL)</label><input type="text" name="gambar" value="{{ old('gambar', $accessory->gambar) }}">@error('gambar')<div>{{ $message }}</div>@enderror</div>
<div><label>Kondisi</label><input type="text" name="kondisi" value="{{ old('kondisi', $accessory->kondisi) }}">@error('kondisi')<div>{{ $message }}</div>@enderror</div>
    <button type="submit">Update</button>
</form>


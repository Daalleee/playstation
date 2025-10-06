<h1>Edit Game</h1>
<form method="POST" action="{{ route('admin.games.update', $game) }}">
    @csrf
    @method('PUT')
<div><label>Judul</label><input type="text" name="judul" value="{{ old('judul', $game->judul) }}" required>@error('judul')<div>{{ $message }}</div>@enderror</div>
    <div><label>Platform</label><input type="text" name="platform" value="{{ old('platform', $game->platform) }}" required>@error('platform')<div>{{ $message }}</div>@enderror</div>
    <div><label>Genre</label><input type="text" name="genre" value="{{ old('genre', $game->genre) }}">@error('genre')<div>{{ $message }}</div>@enderror</div>
<div><label>Stok</label><input type="number" name="stok" value="{{ old('stok', $game->stok) }}" required>@error('stok')<div>{{ $message }}</div>@enderror</div>
<div><label>Harga/Hari</label><input type="number" step="0.01" name="harga_per_hari" value="{{ old('harga_per_hari', $game->harga_per_hari) }}" required>@error('harga_per_hari')<div>{{ $message }}</div>@enderror</div>
<div><label>Gambar (URL)</label><input type="text" name="gambar" value="{{ old('gambar', $game->gambar) }}">@error('gambar')<div>{{ $message }}</div>@enderror</div>
<div><label>Kondisi</label><input type="text" name="kondisi" value="{{ old('kondisi', $game->kondisi) }}">@error('kondisi')<div>{{ $message }}</div>@enderror</div>
    <button type="submit">Update</button>
</form>


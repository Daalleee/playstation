@include('admin.partials.nav')
<h1>Edit Unit PS</h1>
<form method="POST" action="{{ route('admin.unitps.update', $unit) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
<div><label>Nama</label><input type="text" name="nama" value="{{ old('nama', $unit->nama) }}" required>@error('nama')<div>{{ $message }}</div>@enderror</div>
<div><label>Merek</label><input type="text" name="merek" value="{{ old('merek', $unit->merek) }}" required>@error('merek')<div>{{ $message }}</div>@enderror</div>
<div><label>Model</label><input type="text" name="model" value="{{ old('model', $unit->model) }}" required>@error('model')<div>{{ $message }}</div>@enderror</div>
<div><label>Nomor Seri</label><input type="text" name="nomor_seri" value="{{ old('nomor_seri', $unit->nomor_seri) }}" required>@error('nomor_seri')<div>{{ $message }}</div>@enderror</div>
<div><label>Harga per Jam</label><input type="number" step="0.01" name="harga_per_jam" value="{{ old('harga_per_jam', $unit->harga_per_jam) }}" required>@error('harga_per_jam')<div>{{ $message }}</div>@enderror</div>
<div><label>Stok</label><input type="number" name="stok" value="{{ old('stok', $unit->stok) }}" required>@error('stok')<div>{{ $message }}</div>@enderror</div>
    <div>
        <label>Status</label>
        <select name="status" required>
            <option value="available" {{ old('status', $unit->status)==='available'?'selected':'' }}>available</option>
            <option value="rented" {{ old('status', $unit->status)==='rented'?'selected':'' }}>rented</option>
            <option value="maintenance" {{ old('status', $unit->status)==='maintenance'?'selected':'' }}>maintenance</option>
        </select>
        @error('status')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Foto (File)</label>
        <input type="file" name="foto" accept="image/*">
        @if($unit->foto)
            <div>Foto saat ini: <img src="{{ asset('storage/'.$unit->foto) }}" alt="foto" style="max-height:80px"></div>
        @endif
        @error('foto')<div>{{ $message }}</div>@enderror
    </div>
    <div><label>Kondisi</label><input type="text" name="kondisi" value="{{ old('kondisi', $unit->kondisi) }}">@error('kondisi')<div>{{ $message }}</div>@enderror</div>
    <button type="submit">Update</button>
</form>


@include('admin.partials.nav')
<h1>Edit Pelanggan</h1>
<form method="POST" action="{{ route('admin.pelanggan.update', $pelanggan) }}">
    @csrf
    @method('PUT')
    <div>
        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name', $pelanggan->name) }}" required>
        @error('name')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" required>
        @error('email')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Password (kosongkan jika tidak diganti)</label>
        <input type="password" name="password">
        @error('password')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>No HP</label>
        <input type="text" name="phone" value="{{ old('phone', $pelanggan->phone) }}">
        @error('phone')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Alamat</label>
        <input type="text" name="address" value="{{ old('address', $pelanggan->address) }}">
        @error('address')<div>{{ $message }}</div>@enderror
    </div>
    <button type="submit">Update</button>
</form>


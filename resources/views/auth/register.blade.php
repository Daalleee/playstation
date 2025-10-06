<h1>Register</h1>
<form method="POST" action="{{ route('register.post') }}">
    @csrf
    <div>
        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" required />
        @error('name')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required />
        @error('email')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Alamat</label>
        <input type="text" name="address" value="{{ old('address') }}" required />
        @error('address')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>No HP</label>
        <input type="text" name="phone" value="{{ old('phone') }}" required />
        @error('phone')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" required />
        @error('password')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required />
    </div>
    <button type="submit">Daftar</button>
</form>


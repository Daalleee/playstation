@include('admin.partials.nav')
<h1>Buat Akun {{ ucfirst($role) }}</h1>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.staff.store') }}">
    @csrf
    <input type="hidden" name="role" value="{{ $role }}">

    <div>
        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label>Telepon</label>
        <input type="text" name="phone" value="{{ old('phone') }}">
    </div>
    <div>
        <label>Alamat</label>
        <input type="text" name="address" value="{{ old('address') }}">
    </div>

    <button type="submit">Simpan</button>
    <a href="{{ route('admin.staff.index', ['role' => $role]) }}">Batal</a>
</form>



@include('admin.partials.nav')
<h1>Manajemen Staff (Role: {{ $role }})</h1>

@if (session('status'))
    <div style="color: green;">{{ session('status') }}</div>
@endif

<nav>
    <a href="{{ route('admin.staff.index', ['role' => 'admin']) }}">Admin</a> |
    <a href="{{ route('admin.staff.index', ['role' => 'kasir']) }}">Kasir</a> |
    <a href="{{ route('admin.staff.index', ['role' => 'pemilik']) }}">Pemilik</a>
    <a href="{{ route('admin.staff.create', ['role' => $role]) }}" style="margin-left: 1rem;">+ Tambah {{ ucfirst($role) }}</a>
    <a href="{{ route('dashboard.admin') }}" style="margin-left: 1rem;">Kembali</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline-block; margin-left: 1rem;">
        @csrf
        <button type="submit">Logout</button>
    </form>
 </nav>

<table border="1" cellpadding="6" cellspacing="0" style="margin-top: 1rem;">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $users->withQueryString()->links() }}



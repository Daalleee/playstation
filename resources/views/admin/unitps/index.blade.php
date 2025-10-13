@include('admin.partials.nav')
<h1>Kelola Unit PS</h1>
<a href="{{ route('dashboard.admin') }}" style="background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;margin-bottom:1rem;display:inline-block;">&larr; Kembali ke Dashboard</a>
<a href="{{ route('admin.unitps.create') }}">Tambah Unit</a>

@if(session('status'))
    <div>{{ session('status') }}</div>
@endif

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Nama</th>
            <th>Merek</th>
            <th>Model</th>
            <th>Nomor Seri</th>
            <th>Harga/Jam</th>
            <th>Stok</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($units as $u)
        <tr>
            <td>
                @if($u->foto)
                    <img src="{{ asset('storage/'.$u->foto) }}" alt="foto" style="max-height:60px">
                @endif
            </td>
            <td>{{ $u->nama }}</td>
            <td>{{ $u->merek }}</td>
            <td>{{ $u->model }}</td>
            <td>{{ $u->nomor_seri }}</td>
            <td>{{ number_format($u->harga_per_jam, 2) }}</td>
            <td>{{ $u->stok }}</td>
            <td>{{ $u->status }}</td>
            <td>
                <a href="{{ route('admin.unitps.edit', $u) }}">Edit</a>
                <form action="{{ route('admin.unitps.destroy', $u) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus unit?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $units->links() }}


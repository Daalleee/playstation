<h1>Kelola Pelanggan</h1>
<a href="{{ route('admin.pelanggan.create') }}">Tambah Pelanggan</a>

@if(session('status'))
    <div>{{ session('status') }}</div>
@endif

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pelanggan as $p)
        <tr>
            <td>{{ $p->name }}</td>
            <td>{{ $p->email }}</td>
            <td>{{ $p->phone }}</td>
            <td>{{ $p->address }}</td>
            <td>
                <a href="{{ route('admin.pelanggan.edit', $p) }}">Edit</a>
                <form action="{{ route('admin.pelanggan.destroy', $p) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus pelanggan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $pelanggan->links() }}


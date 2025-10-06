<h1>Kelola Aksesoris</h1>
<a href="{{ route('admin.accessories.create') }}">Tambah Aksesoris</a>

@if(session('status'))
    <div>{{ session('status') }}</div>
@endif

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Stok</th>
            <th>Harga/Hari</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accessories as $a)
        <tr>
            <td>{{ $a->nama }}</td>
            <td>{{ $a->jenis }}</td>
            <td>{{ $a->stok }}</td>
            <td>{{ number_format($a->harga_per_hari, 2) }}</td>
            <td>
                <a href="{{ route('admin.accessories.edit', $a) }}">Edit</a>
                <form action="{{ route('admin.accessories.destroy', $a) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus aksesoris?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $accessories->links() }}


<h1>Kelola Game</h1>
<a href="{{ route('admin.games.create') }}">Tambah Game</a>

@if(session('status'))
    <div>{{ session('status') }}</div>
@endif

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Platform</th>
            <th>Genre</th>
            <th>Stok</th>
            <th>Harga/Hari</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($games as $g)
        <tr>
            <td>{{ $g->judul }}</td>
            <td>{{ $g->platform }}</td>
            <td>{{ $g->genre }}</td>
            <td>{{ $g->stok }}</td>
            <td>{{ number_format($g->harga_per_hari, 2) }}</td>
            <td>
                <a href="{{ route('admin.games.edit', $g) }}">Edit</a>
                <form action="{{ route('admin.games.destroy', $g) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus game?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $games->links() }}


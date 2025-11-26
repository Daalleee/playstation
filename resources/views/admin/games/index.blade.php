@extends('admin.layout')
@section('title','Kelola Game - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Daftar Game</h1>
        <div>
            <a href="{{ route('admin.games.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Game</a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Platform</th>
                        <th>Genre</th>
                        <th>Stok</th>
                        <th>Harga/Hari</th>
                        <th>Kondisi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $g)
                    <tr>
                        <td>
                            @if($g->gambar)
                                @if(str_starts_with($g->gambar, 'http'))
                                    <img src="{{ $g->gambar }}" alt="gambar" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                @else
                                    <img src="{{ asset('storage/'.$g->gambar) }}" alt="gambar" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                @endif
                            @endif
                        </td>
                        <td>{{ $g->judul }}</td>
                        <td>{{ $g->platform }}</td>
                        <td>{{ $g->genre }}</td>
                        <td>{{ $g->stok }}</td>
                        <td>Rp {{ number_format($g->harga_per_hari, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($g->kondisi) }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-warning me-1" href="{{ route('admin.games.edit', $g) }}"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('admin.games.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus game?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2">{{ $games->links() }}</div>
    </div>
    

@endsection

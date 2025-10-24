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
                                <img src="{{ asset('storage/'.$g->gambar) }}" alt="gambar" style="max-height:60px" class="rounded">
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
    
    <style>
        /* Apply the same purple theme and grid lines as dashboard */
        .card{background: #17153B; color: white; border:0; box-shadow: 0 6px 24px rgba(0,0,0,.25)}
        .table{color: white; background-color: #17153B; border-collapse: collapse;}
        .table thead{background: #2E236C;}
        .table thead th{background: #2E236C;color:#dbe0ff;border:1px solid #433D8B; padding: 0.75rem;}
        .table tbody{background: #17153B;}
        .table tbody tr{background: #17153B; transition: background-color 0.2s ease;}
        .table tbody tr:hover{background: #2E236C;}
        .table tbody tr+tr{border-top: 1px solid #433D8B;}
        .table td, .table th{background-color: inherit; color: white; border:1px solid #433D8B; padding: 0.75rem;}
        .table-responsive{background: #17153B;}
        
        /* Bright action buttons */
        .btn-warning {background-color: #ffc107 !important; border-color: #ffc107 !important; color: #000 !important;}
        .btn-danger {background-color: #dc3545 !important; border-color: #dc3545 !important; color: #fff !important;}
        .btn-warning:hover {background-color: #e0a800 !important; border-color: #d39e00 !important;}
        .btn-danger:hover {background-color: #c82333 !important; border-color: #bd2130 !important;}
        
        /* Override any Bootstrap default styles */
        .table>:not(caption)>*>*{background-color: inherit; color: white;}
        .table *, .card * {background-color: inherit;}
    </style>
@endsection

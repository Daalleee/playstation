@extends('admin.layout')
@section('title','Kelola Data Pelanggan - Admin')
@section('admin_content')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 m-0">Kelola Data Pelanggan</h1>
            <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> Tambah Pelanggan</a>
        </div>
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div class="card p-3">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Alamat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggan as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->email }}</td>
                            <td>{{ $p->phone }}</td>
                            <td>{{ $p->address }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.pelanggan.edit', $p) }}" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.pelanggan.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelanggan?')">
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
            <div class="mt-2">{{ $pelanggan->links() }}</div>
        </div>
        

@endsection

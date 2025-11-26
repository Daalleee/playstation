@extends('admin.layout')
@section('title','Kelola Aksesoris - Admin')
@section('admin_content')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 m-0">Daftar Aksesoris</h1>
            <div>
                <a href="{{ route('admin.accessories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Aksesoris</a>
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
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Stok</th>
                            <th>Harga/Hari</th>
                            <th>Kondisi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accessories as $a)
                        <tr>
                            <td>
                                @if($a->gambar)
                                    @if(str_starts_with($a->gambar, 'http'))
                                        <img src="{{ $a->gambar }}" alt="gambar" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                    @else
                                        <img src="{{ asset('storage/'.$a->gambar) }}" alt="gambar" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                    @endif
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>{{ $a->nama }}</td>
                            <td>{{ $a->jenis }}</td>
                            <td>{{ $a->stok }}</td>
                            <td>Rp {{ number_format($a->harga_per_hari, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($a->kondisi) }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-warning me-1" href="{{ route('admin.accessories.edit', $a) }}"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.accessories.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aksesoris?')">
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
            <div class="mt-2">{{ $accessories->links() }}</div>
        </div>
        

@endsection


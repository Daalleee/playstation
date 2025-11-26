@extends('admin.layout')
@section('title','Kelola Unit PS - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Daftar Unit PS</h1>
        <div>
            <a href="{{ route('admin.unitps.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Unit</a>
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
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Merek</th>
                        <th>Model</th>
                        <th>No. Seri</th>
                        <th>Harga/Jam</th>
                        <th>Stok</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $u)
                    <tr>
                        <td>
                            @if($u->foto)
                                @if(str_starts_with($u->foto, 'http'))
                                    <img src="{{ $u->foto }}" alt="foto" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                @else
                                    <img src="{{ asset('storage/'.$u->foto) }}" alt="foto" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                @endif
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $u->nama ?? $u->name }}</td>
                        <td>{{ $u->merek ?? $u->brand }}</td>
                        <td>{{ $u->model }}</td>
                        <td>{{ $u->nomor_seri ?? $u->serial_number }}</td>
                        <td>Rp {{ number_format($u->harga_per_jam ?? $u->price_per_hour, 0, ',', '.') }}</td>
                        <td>{{ $u->stok ?? $u->stock }}</td>
                        <td>{{ ucfirst($u->kondisi) }}</td>
                        <td>
                            @if($u->status == 'Tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($u->status == 'Disewa')
                                <span class="badge bg-warning text-dark">Disewa</span>
                            @else
                                <span class="badge bg-danger">Maintenance</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-warning me-1" href="{{ route('admin.unitps.edit', $u) }}"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('admin.unitps.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus unit ini?')">
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
        <div class="mt-2">{{ $units->links() }}</div>
    </div>
@endsection

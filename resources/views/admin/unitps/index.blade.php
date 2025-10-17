@extends('admin.layout')
@section('title','Kelola Inventaris - Admin')
@section('admin_content')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 m-0">Kelola Inventaris</h1>
            <a href="{{ route('admin.unitps.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Unit</a>
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
                            <th>Nomor Seri</th>
                            <th>Harga/Jam</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $u)
                        <tr>
                            <td>
                                @php
                                    $foto = $u->foto;
                                    $imgUrl = null;
                                    if ($foto) {
                                        if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
                                            $imgUrl = $foto;
                                        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($foto)) {
                                            $imgUrl = route('media', ['path' => $foto]);
                                        } else {
                                            // Legacy: try common extensions if path has no extension
                                            if (!str_contains($foto, '.')) {
                                                foreach (['jpg','jpeg','png','webp','gif'] as $ext) {
                                                    $candidate = $foto.'.'.$ext;
                                                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($candidate)) {
                                                        $imgUrl = route('media', ['path' => $candidate]);
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="foto" style="max-height:60px" class="rounded">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $u->nama }}</td>
                            <td>{{ $u->merek }}</td>
                            <td>{{ $u->model }}</td>
                            <td>{{ $u->nomor_seri }}</td>
                            <td>Rp {{ number_format($u->harga_per_jam, 0, ',', '.') }}</td>
                            <td>{{ $u->stok }}</td>
                            <td><span class="badge {{ $u->status==='available' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $u->status }}</span></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-warning" href="{{ route('admin.unitps.edit', $u) }}"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.unitps.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus unit?')">
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

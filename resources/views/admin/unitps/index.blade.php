@extends('admin.layout')
@section('title','Kelola Inventaris - Admin')
@section('admin_content')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 m-0">Daftar Unit PS</h1>
            <a href="{{ route('admin.unitps.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Unit PS</a>
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
                            <th>Model</th>
                            <th>Merek</th>
                            <th>Nomor Seri</th>
                            <th>Harga/Jam</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
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
                            <td>{{ $u->model }}</td>
                            <td>{{ $u->merek }}</td>
                            <td>{{ $u->nomor_seri ?? '-' }}</td>
                            <td>Rp {{ number_format($u->harga_per_jam, 0, ',', '.') }}</td>
                            <td>{{ $u->stok }}</td>
                            <td>{{ ucfirst($u->kondisi) }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-warning me-1" href="{{ route('admin.unitps.edit', $u) }}"><i class="bi bi-pencil-square"></i></a>
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
            
            /* Ensure Nomor Seri column is visible */
            .table td:nth-child(5), .table th:nth-child(5) { 
                min-width: 120px; 
                text-align: center; 
                background-color: inherit !important;
                color: white !important;
            }
            
            /* Override any Bootstrap default styles */
            .table>:not(caption)>*>*{background-color: inherit; color: white;}
            .table *, .card * {background-color: inherit;}
        </style>
@endsection

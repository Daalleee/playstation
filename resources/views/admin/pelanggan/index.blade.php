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

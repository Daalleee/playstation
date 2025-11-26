@extends('admin.layout')
@section('title','Manajemen Staff (' . ucfirst($role) . ') - Admin')
@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 m-0">Daftar {{ ucfirst($role) }}</h1>
        <div>
            <a href="{{ route('admin.' . $role . '.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Akun {{ ucfirst($role) }}</a>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : ($user->role == 'pemilik' ? 'success' : 'warning') }} text-dark">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-warning me-1" href="{{ route('admin.' . $role . '.edit', $user) }}"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('admin.' . $role . '.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus staff?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data {{ ucfirst($role) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-2">{{ $users->withQueryString()->links() }}</div>
    </div>
    

@endsection



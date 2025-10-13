@include('pelanggan.partials.nav')
<h1>Profil Saya</h1>
<a href="{{ route('dashboard.pelanggan') }}" style="background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;margin-bottom:1rem;display:inline-block;">&larr; Kembali ke Dashboard</a>

@if(session('status'))
    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        {{ session('status') }}
    </div>
@endif

<div style="max-width: 600px; margin-top: 2rem;">
    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border: 1px solid #dee2e6;">
        <h2>Informasi Pribadi</h2>
        
        <div style="margin-bottom: 1rem;">
            <strong>Nama:</strong> {{ $user->name }}
        </div>
        
        <div style="margin-bottom: 1rem;">
            <strong>Email:</strong> {{ $user->email }}
        </div>
        
        <div style="margin-bottom: 1rem;">
            <strong>Telepon:</strong> {{ $user->phone ?? 'Belum diisi' }}
        </div>
        
        <div style="margin-bottom: 1rem;">
            <strong>Alamat:</strong> {{ $user->address ?? 'Belum diisi' }}
        </div>
        
        <div style="margin-bottom: 1rem;">
            <strong>Role:</strong> {{ ucfirst($user->role) }}
        </div>
        
        <div style="margin-bottom: 1rem;">
            <strong>Bergabung:</strong> {{ $user->created_at->format('d M Y H:i') }}
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="{{ route('pelanggan.profile.edit') }}" style="background: #007bff; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;">
                Edit Profil
            </a>
        </div>
    </div>
</div>

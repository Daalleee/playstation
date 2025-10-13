@include('pelanggan.partials.nav')
<h1>Edit Profil</h1>
<a href="{{ route('pelanggan.profile.show') }}" style="background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;margin-bottom:1rem;display:inline-block;">&larr; Kembali ke Profil</a>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('pelanggan.profile.update') }}" style="max-width: 600px; margin-top: 2rem;">
    @csrf
    @method('PUT')
    
    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border: 1px solid #dee2e6;">
        <h2>Informasi Pribadi</h2>
        
        <div style="margin-bottom: 1rem;">
            <label for="name" style="display: block; margin-bottom: 0.5rem;"><strong>Nama:</strong></label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="email" style="display: block; margin-bottom: 0.5rem;"><strong>Email:</strong></label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="phone" style="display: block; margin-bottom: 0.5rem;"><strong>Telepon:</strong></label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="address" style="display: block; margin-bottom: 0.5rem;"><strong>Alamat:</strong></label>
            <textarea id="address" name="address" rows="3" 
                      style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">{{ old('address', $user->address) }}</textarea>
        </div>
        
        <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Ubah Password (Opsional)</h3>
        
        <div style="margin-bottom: 1rem;">
            <label for="current_password" style="display: block; margin-bottom: 0.5rem;"><strong>Password Saat Ini:</strong></label>
            <input type="password" id="current_password" name="current_password" 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="password" style="display: block; margin-bottom: 0.5rem;"><strong>Password Baru:</strong></label>
            <input type="password" id="password" name="password" 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
        </div>
        
        <div style="margin-bottom: 1rem;">
            <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem;"><strong>Konfirmasi Password Baru:</strong></label>
            <input type="password" id="password_confirmation" name="password_confirmation" 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
        </div>
        
        <div style="margin-top: 2rem;">
            <button type="submit" style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                Simpan Perubahan
            </button>
            <a href="{{ route('pelanggan.profile.show') }}" style="background: #6c757d; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; margin-left: 0.5rem;">
                Batal
            </a>
        </div>
    </div>
</form>

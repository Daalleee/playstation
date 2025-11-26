@extends($layout)

@section('title', 'Edit Profil')
@section('header_title', 'Edit Profil')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <div id="avatar-preview-container" class="rounded-circle overflow-hidden bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 120px; height: 120px; font-size: 3rem;">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                                @else
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                @endif
                            </div>
                            <label for="avatar" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer shadow-sm" style="cursor: pointer; right: -10px;" data-bs-toggle="tooltip" title="Ubah Foto">
                                <i class="bi bi-camera-fill"></i>
                                <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                        <h5 class="mt-3 mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                        
                        <!-- Test Button -->
                        <button type="button" class="btn btn-sm btn-outline-info mt-2" data-bs-toggle="modal" data-bs-target="#previewModal">
                            <i class="bi bi-eye-fill me-1"></i> Preview Foto
                        </button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', Auth::user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Preview Foto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="modal-preview-image" src="" alt="Preview" class="img-fluid rounded shadow-lg" style="max-height: 400px;">
                <div id="modal-preview-placeholder" class="d-none display-1 fw-bold text-secondary">
                    <!-- Initials will be injected here if no image -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    console.log('Profile edit script loaded');
    
    // Make functions globally accessible
    window.currentImageSrc = null;

    window.previewImage = function(input) {
        console.log('previewImage called', input.files);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                window.currentImageSrc = e.target.result;
                console.log('Image loaded, src length:', e.target.result.length);
                
                // Update main profile avatar
                var container = document.getElementById('avatar-preview-container');
                if (container) {
                    var img = container.querySelector('img');
                    if (img) {
                        img.src = e.target.result;
                    } else {
                        container.innerHTML = '<img src="' + e.target.result + '" alt="Avatar" class="w-100 h-100 object-fit-cover">';
                    }
                }

                // Update navbar avatar
                var navbarAvatar = document.getElementById('navbarAvatar');
                if (navbarAvatar) {
                    var navbarImg = navbarAvatar.querySelector('img');
                    if (navbarImg) {
                        navbarImg.src = e.target.result;
                    } else {
                        navbarAvatar.innerHTML = '<img src="' + e.target.result + '" alt="Avatar" class="w-100 h-100 object-fit-cover">';
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Update modal image when modal is about to be shown
    // No need for DOMContentLoaded since this script loads at the end of the page
    const previewModal = document.getElementById('previewModal');
    console.log('Script executing, previewModal element:', previewModal);
    
    if (previewModal) {
        console.log('Registering modal show event listener');
        previewModal.addEventListener('show.bs.modal', function () {
            console.log('Modal showing event fired!');
            console.log('currentImageSrc value:', window.currentImageSrc);
            
            const modalImg = document.getElementById('modal-preview-image');
            const modalPlaceholder = document.getElementById('modal-preview-placeholder');
            
            // Get image source from avatar container or currentImageSrc
            let src = window.currentImageSrc;
            console.log('Step 1 - currentImageSrc:', src ? 'Found' : 'Not found');
            
            if (!src) {
                const container = document.getElementById('avatar-preview-container');
                const img = container ? container.querySelector('img') : null;
                console.log('Step 2 - Looking in DOM, img element:', img);
                if (img) {
                    src = img.src;
                    console.log('Step 3 - Found src from DOM:', src.substring(0, 50));
                }
            }
            
            console.log('Final src:', src ? 'FOUND' : 'NOT FOUND');
            
            if (src) {
                console.log('Setting modal image src');
                modalImg.src = src;
                modalImg.classList.remove('d-none');
                modalPlaceholder.classList.add('d-none');
            } else {
                console.log('No src found, showing placeholder');
                modalImg.classList.add('d-none');
                modalPlaceholder.classList.remove('d-none');
                modalPlaceholder.textContent = '{{ substr(Auth::user()->name, 0, 1) }}';
            }
        });
        console.log('Event listener registered successfully');
    } else {
        console.error('previewModal element not found!');
    }
</script>
@endsection

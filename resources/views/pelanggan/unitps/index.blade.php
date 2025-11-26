@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header & Search -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
                <h4 class="mb-0 fw-bold"><i class="bi bi-controller me-2 text-primary icon-hover"></i><span class="gradient-text">Daftar Unit PlayStation</span></h4>
            </div>

            <form method="GET" action="{{ route('pelanggan.unitps.list') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase fw-bold">Model</label>
                    <select name="model" class="form-select bg-dark text-light border-secondary">
                        <option value="">Semua Model</option>
                        @foreach (['PS3','PS4','PS5'] as $opt)
                            <option value="{{ $opt }}" @selected(request('model')===$opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase fw-bold">Merek</label>
                    <select name="brand" class="form-select bg-dark text-light border-secondary">
                        <option value="">Semua Merek</option>
                        <option value="Sony" @selected(request('brand')==='Sony')>Sony</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small text-uppercase fw-bold">Cari Unit</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-dark text-light border-secondary" placeholder="Nama unit...">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-filter me-1 icon-hover"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unit List -->
    <div class="card card-glow">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Unit</th>
                        <th>Model/Merek</th>
                        <th>Foto</th>
                        <th class="text-center">Stok</th>
                        <th>Harga/Jam</th>
                        <th style="min-width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td><span class="font-monospace text-muted">#{{ $unit->id }}</span></td>
                            <td class="fw-bold text-white">{{ $unit->name }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle">{{ $unit->model }}</span>
                                <div class="small text-muted mt-1">{{ $unit->brand }}</div>
                            </td>
                            <td>
                                @if($unit->foto)
                                    <img src="{{ str_starts_with($unit->foto, 'http') ? $unit->foto : asset('storage/' . $unit->foto) }}" 
                                         alt="{{ $unit->name }}" 
                                         class="rounded shadow-sm" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-dark rounded d-flex align-items-center justify-content-center text-muted border border-secondary" style="width: 60px; height: 60px;">
                                        <i class="bi bi-controller"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @php 
                                    $stok = $unit->stock ?? 0;
                                    $badgeClass = $stok > 5 ? 'bg-success-subtle' : ($stok > 0 ? 'bg-warning-subtle' : 'bg-danger-subtle');
                                @endphp
                                <span class="badge {{ $badgeClass }} {{ $stok > 0 ? 'badge-pulse' : '' }}">{{ $stok }} Unit</span>
                            </td>
                            <td class="text-secondary fw-bold">Rp {{ number_format($unit->price_per_hour, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="number" class="form-control form-control-sm bg-dark text-light border-secondary text-center" value="1" min="1" max="{{ $stok }}" 
                                           style="width: 60px;" 
                                           id="quantity_{{ $unit->id }}" 
                                           {{ $stok <= 0 ? 'disabled' : '' }}>
                                    
                                    <button type="button" class="btn btn-sm btn-primary add-to-cart-btn flex-grow-1" 
                                            data-type="unitps" 
                                            data-id="{{ $unit->id }}" 
                                            data-price_type="per_jam"
                                            data-stok="{{ $stok }}"
                                            {{ $stok <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus me-1"></i> {{ $stok > 0 ? 'Sewa' : 'Habis' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-controller fs-1 d-block mb-2 opacity-50"></i>
                                Tidak ada unit PlayStation yang sesuai kriteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer border-0 bg-transparent py-3">
            {{ method_exists($units,'links') ? $units->withQueryString()->links() : '' }}
        </div>
    </div>
</div>

<script>
    // Add real-time validation for quantity inputs
    document.querySelectorAll('input[id^="quantity_"]').forEach(input => {
      input.addEventListener('input', function() {
        const max = parseInt(this.max);
        const value = parseInt(this.value);
        
        if (value > max) {
          this.value = max;
        } else if (value < 1) {
          this.value = 1;
        }
      });
    });
    
    // Handle add to cart AJAX requests
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
      button.addEventListener('click', function() {
        const type = this.getAttribute('data-type');
        const id = this.getAttribute('data-id');
        const price_type = this.getAttribute('data-price_type');
        const stok = parseInt(this.getAttribute('data-stok'));
        
        // Get quantity from the input field
        const quantityInput = document.getElementById('quantity_' + id);
        const quantity = parseInt(quantityInput.value);
        
        // Validate quantity
        if(quantity < 1 || quantity > stok) {
          Swal.fire({
            icon: 'warning',
            title: 'Jumlah Tidak Valid',
            text: 'Jumlah harus antara 1 dan ' + stok,
            background: '#1e293b',
            color: '#fff'
          });
          return;
        }
        
        // Disable button to prevent multiple clicks
        this.disabled = true;
        const originalHTML = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        if (!csrfToken) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'CSRF token tidak ditemukan. Silakan refresh halaman.',
            background: '#1e293b',
            color: '#fff'
          });
          this.disabled = false;
          this.innerHTML = originalHTML;
          return;
        }
        
        // Create form data
        const formData = new FormData();
        formData.append('type', type);
        formData.append('id', id);
        formData.append('quantity', quantity);
        formData.append('price_type', price_type);
        
        fetch('/pelanggan/cart/add', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
          }
          return response.json();
        })
        .then(data => {
          if(data.success) {
            // Show success message using SweetAlert2 Toast
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              background: '#1e293b',
              color: '#fff',
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            });
            
            Toast.fire({
              icon: 'success',
              title: data.message
            });
            
            // Reset quantity input to 1
            quantityInput.value = 1;
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: data.message || 'Terjadi kesalahan',
              background: '#1e293b',
              color: '#fff'
            });
          }
          
          // Restore button
          this.disabled = false;
          this.innerHTML = originalHTML;
        })
        .catch(error => {
          console.error('Error:', error);
          const errorMessage = error.message || error.error || 'Terjadi kesalahan saat menambahkan ke keranjang';
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage,
            background: '#1e293b',
            color: '#fff'
          });
          
          // Restore button
          this.disabled = false;
          this.innerHTML = originalHTML;
        });
      });
    });
</script>
@endsection
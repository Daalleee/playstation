@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header & Filter -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                <h4 class="mb-3 mb-md-0 fw-bold"><i class="bi bi-controller me-2 text-primary icon-hover"></i><span class="gradient-text">Daftar Games PlayStation</span></h4>
            </div>

            <form method="GET" action="{{ route('pelanggan.games.list') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted fw-bold small text-uppercase">Platform</label>
                    <select name="platform" class="form-select bg-dark text-light border-secondary">
                        <option value="">Semua Platform</option>
                        @foreach (['PS3','PS4','PS5'] as $opt)
                            <option value="{{ $opt }}" @selected(request('platform')===$opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted fw-bold small text-uppercase">Genre</label>
                    <input type="text" name="genre" value="{{ request('genre') }}" class="form-control bg-dark text-light border-secondary" placeholder="Contoh: Action, RPG">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted fw-bold small text-uppercase">Cari Game</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-dark text-light border-secondary" placeholder="Judul game...">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100 fw-bold" type="submit">
                        <i class="bi bi-funnel me-1 icon-hover"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Games List -->
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th style="width: 25%;">Judul</th>
                        <th>Platform/Genre</th>
                        <th>Gambar</th>
                        <th>Stok</th>
                        <th>Harga/Hari</th>
                        <th>Kondisi</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($games as $game)
                        <tr>
                            <td class="text-muted">#{{ $game->id }}</td>
                            <td>
                                <div class="fw-bold text-white">{{ $game->judul }}</div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle mb-1">{{ $game->platform }}</span>
                                <div class="small text-muted">{{ $game->genre }}</div>
                            </td>
                            <td>
                                @if($game->gambar)
                                    <img src="{{ str_starts_with($game->gambar, 'http') ? $game->gambar : asset('storage/' . $game->gambar) }}" 
                                         alt="{{ $game->judul }}" 
                                         class="rounded shadow-sm" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-dark rounded d-flex align-items-center justify-content-center text-muted border border-secondary" style="width: 60px; height: 60px;">
                                        <i class="bi bi-disc"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php 
                                    $stok = $game->stok ?? 0;
                                    $badgeClass = $stok > 5 ? 'bg-success-subtle' : ($stok > 0 ? 'bg-warning-subtle' : 'bg-danger-subtle');
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $stok }} Unit</span>
                            </td>
                            <td class="fw-bold text-white">Rp {{ number_format($game->harga_per_hari, 0, ',', '.') }}</td>
                            <td>
                                @php $kondisi = strtolower($game->kondisi ?? 'baik'); @endphp
                                <span class="badge {{ $kondisi === 'baik' ? 'bg-success-subtle' : ($kondisi === 'rusak' ? 'bg-danger-subtle' : 'bg-warning-subtle') }}">
                                    {{ ucfirst($game->kondisi) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control bg-dark text-light border-secondary text-center" 
                                               value="1" min="1" max="{{ $stok }}" 
                                               id="quantity_{{ $game->id }}" 
                                               {{ $stok <= 0 ? 'disabled' : '' }}>
                                        <button type="button" class="btn btn-primary add-to-cart-btn" 
                                                data-type="game" 
                                                data-id="{{ $game->id }}" 
                                                data-price_type="per_hari"
                                                data-stok="{{ $stok }}"
                                                {{ $stok <= 0 ? 'disabled' : '' }}>
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </div>
                                    @if($stok <= 0)
                                        <small class="text-danger text-center" style="font-size: 0.7rem;">Stok Habis</small>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted opacity-75">
                                    <i class="bi bi-controller display-1 mb-3 d-block"></i>
                                    <h5 class="fw-bold">Tidak ada game ditemukan</h5>
                                    <p class="mb-0">Coba ubah filter pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($games->hasPages())
            <div class="card-footer bg-transparent border-top border-secondary py-3">
                {{ $games->withQueryString()->links() }}
            </div>
        @endif
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
            const originalHtml = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            
            fetch('/pelanggan/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    type: type,
                    id: id,
                    quantity: quantity,
                    price_type: price_type
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Show success message
                    showFlashMessage(data.message, 'success');
                    // Reset quantity input to 1
                    quantityInput.value = 1;
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        background: '#1e293b',
                        color: '#fff'
                    });
                }
                
                // Restore button
                this.disabled = false;
                this.innerHTML = originalHtml;
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menambahkan ke keranjang',
                    background: '#1e293b',
                    color: '#fff'
                });
                
                // Restore button
                this.disabled = false;
                this.innerHTML = originalHtml;
            });
        });
    });
</script>
@endsection
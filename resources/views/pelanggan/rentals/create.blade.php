@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-plus-circle me-2 text-primary icon-hover"></i><span class="gradient-text">Buat Penyewaan</span></h4>
                    <p class="mb-0 text-muted small">Lengkapi detail penyewaan Anda</p>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4 d-flex align-items-start">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1">Terjadi Kesalahan!</h6>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Items to Rent -->
        <div class="col-lg-6">
            <div class="card card-hover-lift h-100">
                <div class="card-body">
                    <h5 class="mb-4 fw-bold text-white"><i class="bi bi-cart-check me-2 text-success"></i>Item yang Akan Disewa</h5>
                    
                    @forelse($cartItems as $item)
                        @php
                            $itemModel = null;
                            $imageField = '';
                            $modelClass = null;
                            
                            // Determine model class and image field
                            if(isset($directItem) && $directItem) {
                                $itemType = $item['type'] ?? null;
                                $itemId = $item['item_id'] ?? $item['id'] ?? null;
                            } else {
                                $itemType = $item->type ?? null;
                                $itemId = $item->item_id ?? null;
                            }
                            
                            switch($itemType) {
                                case 'unitps':
                                    $modelClass = 'App\\Models\\UnitPS';
                                    $imageField = 'foto';
                                    break;
                                case 'game':
                                    $modelClass = 'App\\Models\\Game';
                                    $imageField = 'gambar';
                                    break;
                                case 'accessory':
                                    $modelClass = 'App\\Models\\Accessory';
                                    $imageField = 'gambar';
                                    break;
                            }
                            
                            if($modelClass && $itemId) {
                                $itemModel = $modelClass::find($itemId);
                            }
                            
                            // Get item details
                            if(isset($directItem) && $directItem) {
                                $itemName = $item['name'] ?? 'Unknown';
                                $itemPrice = $item['price'] ?? 0;
                                $itemPriceType = $item['price_type'] ?? 'per_hari';
                                $itemQuantity = $item['quantity'] ?? 1;
                            } else {
                                $itemName = $item->name ?? 'Unknown';
                                $itemPrice = $item->price ?? 0;
                                $itemPriceType = $item->price_type ?? 'per_hari';
                                $itemQuantity = $item->quantity ?? 1;
                            }
                        @endphp
                        
                        <div class="d-flex align-items-start mb-3 pb-3 border-bottom border-secondary">
                            <!-- Image -->
                            <div class="flex-shrink-0 me-3">
                                @if($itemModel && $itemModel->$imageField)
                                    <img src="{{ str_starts_with($itemModel->$imageField, 'http') ? $itemModel->$imageField : asset('storage/' . $itemModel->$imageField) }}" 
                                         alt="{{ $itemName }}" 
                                         class="rounded shadow-sm" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="bg-dark rounded d-flex align-items-center justify-content-center text-muted border border-secondary" style="width: 100px; height: 100px;">
                                        <i class="bi bi-box-seam fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Details -->
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-white mb-2">{{ $itemName }}</h6>
                                
                                @if($itemModel)
                                    @if($itemType == 'unitps')
                                        <div class="text-muted small mb-1"><i class="bi bi-cpu me-1"></i> Model: {{ $itemModel->model ?? 'N/A' }}</div>
                                        <div class="text-muted small mb-1"><i class="bi bi-tag me-1"></i> Merek: {{ $itemModel->brand ?? 'N/A' }}</div>
                                        <div class="text-muted small mb-1"><i class="bi bi-box-seam me-1"></i> Stok: {{ $itemModel->stock ?? 0 }}</div>
                                    @elseif($itemType == 'game')
                                        <div class="text-muted small mb-1"><i class="bi bi-controller me-1"></i> Platform: {{ $itemModel->platform ?? 'N/A' }}</div>
                                        <div class="text-muted small mb-1"><i class="bi bi-joystick me-1"></i> Genre: {{ $itemModel->genre ?? 'N/A' }}</div>
                                        <div class="text-muted small mb-1"><i class="bi bi-box-seam me-1"></i> Stok: {{ $itemModel->stok ?? 0 }}</div>
                                    @elseif($itemType == 'accessory')
                                        <div class="text-muted small mb-1"><i class="bi bi-headset me-1"></i> Jenis: {{ $itemModel->jenis ?? 'N/A' }}</div>
                                        <div class="text-muted small mb-1"><i class="bi bi-box-seam me-1"></i> Stok: {{ $itemModel->stok ?? 0 }}</div>
                                    @endif
                                @endif
                                
                                <div class="mt-2">
                                    <span class="badge bg-primary-subtle">Rp {{ number_format($itemPrice, 0, ',', '.') }} {{ $itemPriceType == 'per_jam' ? 'per jam' : 'per hari' }}</span>
                                    @if($itemType == 'game' || $itemType == 'accessory')
                                        <span class="badge bg-info-subtle ms-1">Qty: {{ $itemQuantity }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Keranjang kosong</p>
                        </div>
                    @endforelse
                    
                    <!-- Action Buttons -->
                    @if(isset($directItem) && $directItem)
                        <div class="d-flex gap-2 mt-4">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary flex-fill">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            @if(!$cartItems->isEmpty())
                                @php $firstItem = $cartItems->first(); @endphp
                                <form method="POST" action="{{ route('pelanggan.cart.add') }}" class="flex-fill">
                                    @csrf
                                    @if(is_array($firstItem))
                                        <input type="hidden" name="type" value="{{ $firstItem['type'] }}">
                                        <input type="hidden" name="id" value="{{ $firstItem['item_id'] ?? $firstItem['id'] ?? null }}">
                                        <input type="hidden" name="price_type" value="{{ $firstItem['price_type'] }}">
                                        <input type="hidden" name="quantity" value="{{ $firstItem['quantity'] ?? 1 }}">
                                    @else
                                        <input type="hidden" name="type" value="{{ $firstItem->type }}">
                                        <input type="hidden" name="id" value="{{ $firstItem->item_id }}">
                                        <input type="hidden" name="price_type" value="{{ $firstItem->price_type }}">
                                        <input type="hidden" name="quantity" value="{{ $firstItem->quantity }}">
                                    @endif
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="mt-4">
                            <a href="{{ route('pelanggan.cart.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Rental Details Form -->
        <div class="col-lg-6">
            <div class="card card-hover-lift h-100">
                <div class="card-body">
                    <h5 class="mb-4 fw-bold text-white"><i class="bi bi-calendar-check me-2 text-info"></i>Detail Penyewaan</h5>
                    
                    <form method="POST" action="{{ route('pelanggan.rentals.store') }}{{ isset($directItem) && $directItem && request()->has('type') && request()->has('id') ? '?type=' . request('type') . '&id=' . request('id') : '' }}">
                        @csrf
                        
                        @if(isset($directItem) && $directItem && request()->has('type') && request()->has('id'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="hidden" name="id" value="{{ request('id') }}">
                            <input type="hidden" name="quantity" value="1">
                        @endif
                        
                        <!-- Rental Date -->
                        <div class="mb-4">
                            <label for="rental_date" class="form-label fw-bold text-white">
                                <i class="bi bi-calendar-event me-1 text-success"></i> Tanggal Mulai Sewa
                            </label>
                            <input type="date" 
                                   id="rental_date" 
                                   name="rental_date" 
                                   value="{{ old('rental_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required 
                                   class="form-control bg-dark text-light border-secondary @error('rental_date') is-invalid @enderror">
                            @error('rental_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Return Date -->
                        <div class="mb-4">
                            <label for="return_date" class="form-label fw-bold text-white">
                                <i class="bi bi-calendar-x me-1 text-warning"></i> Tanggal Kembali
                            </label>
                            <input type="date" 
                                   id="return_date" 
                                   name="return_date" 
                                   value="{{ old('return_date') }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                   required 
                                   class="form-control bg-dark text-light border-secondary @error('return_date') is-invalid @enderror">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small">
                                <i class="bi bi-info-circle me-1"></i> Maksimal durasi sewa: 30 hari
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold text-white">
                                <i class="bi bi-sticky me-1 text-info"></i> Catatan (Opsional)
                            </label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="4" 
                                      class="form-control bg-dark text-light border-secondary @error('notes') is-invalid @enderror" 
                                      placeholder="Tambahkan catatan khusus untuk penyewaan ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success w-100 btn-lg fw-bold">
                            <i class="bi bi-check-circle me-2"></i> Buat Penyewaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Date validation
document.addEventListener('DOMContentLoaded', function() {
    const rentalDateInput = document.getElementById('rental_date');
    const returnDateInput = document.getElementById('return_date');
    
    // Set minimum return date based on rental date
    rentalDateInput.addEventListener('change', function() {
        const rentalDate = new Date(this.value);
        rentalDate.setDate(rentalDate.getDate() + 1);
        const minReturnDate = rentalDate.toISOString().split('T')[0];
        returnDateInput.min = minReturnDate;
        
        // Clear return date if it's before the new minimum
        if (!returnDateInput.value || new Date(returnDateInput.value) < rentalDate) {
            returnDateInput.value = '';
        }
    });
    
    // Validate return date is after rental date
    returnDateInput.addEventListener('change', function() {
        const returnDate = new Date(this.value);
        const rentalDate = new Date(rentalDateInput.value);
        
        if (returnDate <= rentalDate) {
            this.value = '';
            alert('Tanggal kembali harus setelah tanggal mulai sewa.');
        }
    });
});
</script>
@endsection
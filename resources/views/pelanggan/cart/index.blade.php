@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold"><i class="bi bi-cart3 me-2 text-primary icon-hover"></i><span class="gradient-text">Keranjang Penyewaan</span></h4>
            <div>
                @if(!$cartItems->isEmpty())
                    <form id="clear-cart-form" method="POST" action="{{ route('pelanggan.cart.clear') }}" class="d-inline">
                        @csrf
                        <button type="button" class="btn btn-outline-danger btn-sm"
                            onclick="return confirmAction('Apakah Anda yakin ingin mengosongkan keranjang?', 'clear-cart-form')">
                            <i class="bi bi-trash me-1"></i> Hapus Semua
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Cart Items -->
    <div class="card card-glow">
        <div class="table-responsive">
            <table id="cart-table" class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 40%;">Nama Item</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th style="width: 15%;">Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cartItems as $item)
                        @php
                            $itemData = $item->item;
                            $availableStock = $item->getAvailableStock();
                            $hasStock = $item->hasEnoughStock();
                        @endphp
                        <tr class="{{ !$hasStock ? 'table-warning' : '' }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $item->item_image }}" alt="{{ $item->item_name }}" 
                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-white">{{ $item->item_name }}</div>
                                        @if(!$hasStock)
                                            <small class="text-warning d-block mt-1"><i class="bi bi-exclamation-triangle me-1"></i>Stok kurang (Tersedia: {{ $availableStock }})</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $typeLabel = match($item->type) {
                                        'unitps' => 'Unit PS',
                                        'game' => 'Game',
                                        'accessory' => 'Aksesoris',
                                        default => ucfirst($item->type)
                                    };
                                    $badgeClass = match($item->type) {
                                        'unitps' => 'bg-primary-subtle',
                                        'game' => 'bg-info-subtle',
                                        'accessory' => 'bg-warning-subtle',
                                        default => 'bg-secondary-subtle'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $typeLabel }}</span>
                            </td>
                            <td class="text-secondary fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}<span class="text-muted small fw-normal">/{{ $item->price_type == 'per_jam' ? 'jam' : 'hari' }}</span></td>
                            <td>
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <button type="button" class="btn btn-outline-secondary" 
                                            onclick="decreaseQuantity('{{ $item->type }}', {{ $item->item_id }})"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                    <span class="form-control bg-dark text-light text-center border-secondary d-flex align-items-center justify-content-center" 
                                          id="quantity_{{ $item->type }}_{{ $item->item_id }}" 
                                          data-price="{{ $item->price }}"
                                          data-max-stock="{{ $availableStock }}"
                                          data-original-value="{{ $item->quantity }}">{{ $item->quantity }}</span>
                                    <button type="button" class="btn btn-outline-secondary" 
                                            onclick="increaseQuantity('{{ $item->type }}', {{ $item->item_id }})"
                                            {{ $item->quantity >= $availableStock ? 'disabled' : '' }}>+</button>
                                </div>
                            </td>
                            <td class="fw-bold text-white" id="total_{{ $item->type }}_{{ $item->item_id }}">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td>
                                <form id="delete-item-{{ $item->type }}-{{ $item->item_id }}" method="POST" action="{{ route('pelanggan.cart.remove') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $item->type }}">
                                    <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="return confirmAction('Hapus item ini dari keranjang?', 'delete-item-{{ $item->type }}-{{ $item->item_id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted opacity-75">
                                    <i class="bi bi-cart-x display-1 mb-3 d-block"></i>
                                    <h5 class="fw-bold">Keranjang Anda kosong</h5>
                                    <p class="mb-4">Belum ada item yang ditambahkan.</p>
                                    <a href="{{ route('pelanggan.unitps.index') }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-cart-plus me-2"></i>Mulai Belanja
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(!$cartItems->isEmpty())
        <div class="card-footer bg-transparent border-top border-secondary py-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    @php
                        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);
                        $user = auth()->user();
                        $needsProfileUpdate = empty($user->phone) || empty($user->address);
                    @endphp
                    <div class="text-muted small text-uppercase fw-bold mb-1">Total Estimasi</div>
                    <div class="display-6 fw-bold text-white grand-total">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>
                <div>
                    @if($needsProfileUpdate)
                        <a href="{{ route('pelanggan.profile.edit') }}" 
                           class="btn btn-warning btn-lg px-4 fw-bold"
                           onclick="event.preventDefault(); Swal.fire({
                               icon: 'info',
                               title: 'Profil Belum Lengkap',
                               text: 'Silakan lengkapi nomor telepon dan alamat Anda terlebih dahulu sebelum melakukan penyewaan.',
                               confirmButtonText: 'Lengkapi Sekarang',
                               confirmButtonColor: '#f59e0b'
                           }).then((result) => {
                               if (result.isConfirmed) {
                                   window.location.href = '{{ route('pelanggan.profile.edit') }}';
                               }
                           });">
                            <i class="bi bi-exclamation-triangle me-2"></i> Lengkapi Profil Dulu
                        </a>
                    @else
                        <a href="{{ route('pelanggan.rentals.create') }}"
                            class="btn btn-success btn-lg px-5 fw-bold shadow-lg">
                            <i class="bi bi-check-circle me-2"></i> Checkout Sekarang
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    // Function to update grand total
    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('[id^="total_"]').forEach(element => {
            const text = element.textContent.replace(/[^\d]/g, '');
            const value = parseInt(text) || 0;
            grandTotal += value;
        });
        
        const grandTotalElement = document.querySelector('.grand-total');
        if(grandTotalElement) {
            const formattedTotal = 'Rp ' + grandTotal.toLocaleString('id-ID').replace(/\,/g, '.');
            grandTotalElement.textContent = formattedTotal;
        }
    }
    
    // Function to increase quantity
    function increaseQuantity(type, itemId) {
        const quantityElement = document.getElementById('quantity_' + type + '_' + itemId);
        const currentQty = parseInt(quantityElement.textContent);
        const maxStock = parseInt(quantityElement.dataset.maxStock || 999);
        
        if(currentQty >= maxStock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Maksimal',
                text: 'Jumlah sudah mencapai stok maksimal (' + maxStock + ')',
                background: '#1e293b',
                color: '#fff'
            });
            return;
        }
        
        const newQuantity = currentQty + 1;
        updateCartQuantity(type, itemId, newQuantity);
    }
    
    // Function to decrease quantity
    function decreaseQuantity(type, itemId) {
        const quantityElement = document.getElementById('quantity_' + type + '_' + itemId);
        const currentQty = parseInt(quantityElement.textContent);
        
        if(currentQty <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Minimal 1',
                text: 'Jumlah minimal adalah 1',
                background: '#1e293b',
                color: '#fff'
            });
            return;
        }
        
        const newQuantity = currentQty - 1;
        updateCartQuantity(type, itemId, newQuantity);
    }
    
    // Function to update cart quantity
    function updateCartQuantity(type, itemId, newQuantity) {
        // Find buttons and disable them
        const minusBtn = document.querySelector(`button[onclick*="decreaseQuantity('${type}', ${itemId}"]`);
        const plusBtn = document.querySelector(`button[onclick*="increaseQuantity('${type}', ${itemId}"]`);
        
        if(minusBtn) minusBtn.disabled = true;
        if(plusBtn) plusBtn.disabled = true;
        
        // Get the quantity element to restore if needed
        const quantityElement = document.getElementById('quantity_' + type + '_' + itemId);
        const originalValue = quantityElement.textContent;
        
        // Show loading state
        if(minusBtn) minusBtn.textContent = '...';
        if(plusBtn) plusBtn.textContent = '...';
        
        fetch('/pelanggan/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                type: type,
                item_id: itemId,
                quantity: newQuantity
            })
        })
        .then(response => {
            // Check if response is ok before proceeding
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Server error');
                }).catch(() => {
                    // If it's not JSON, treat as general error
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            
            // Check if response is HTML (indicates redirect or error page) 
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('text/html')) {
                // This means Laravel returned a redirect or error page
                throw new Error('Server returned HTML instead of JSON - possible authentication or validation error');
            }
            
            return response.json();
        })
        .then(data => {
            if(data.success) {
                // Update the displayed quantity
                quantityElement.textContent = newQuantity;
                quantityElement.dataset.originalValue = newQuantity;
                
                // Update row total
                const totalElement = document.getElementById('total_' + type + '_' + itemId);
                const price = parseFloat(quantityElement.dataset.price || 0);
                const newTotal = price * newQuantity;
                const formattedTotal = 'Rp ' + newTotal.toLocaleString('id-ID').replace(/\,/g, '.');
                totalElement.textContent = formattedTotal;
                
                // Update grand total
                updateGrandTotal();
                
                // Update button states
                const maxStock = parseInt(quantityElement.dataset.maxStock || 999);
                if(minusBtn) minusBtn.disabled = (newQuantity <= 1);
                if(plusBtn) plusBtn.disabled = (newQuantity >= maxStock);
                
                // Show success message (optional, maybe too noisy for simple increment)
                // alert(data.message);
            } else {
                // Show error message and reset quantity display
                // Show error message and reset quantity display
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Terjadi kesalahan saat memperbarui jumlah',
                    background: '#1e293b',
                    color: '#fff'
                });
                quantityElement.textContent = originalValue;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Check if it's a parsing error (meaning we got HTML instead of JSON)
            if (error.message.includes('Unexpected token') || error.message.includes('HTML instead of JSON')) {
                // This means we likely got redirected to an error page
                location.reload(); // Reload to get proper state
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memperbarui jumlah: ' + error.message,
                    background: '#1e293b',
                    color: '#fff'
                });
                quantityElement.textContent = originalValue;
            }
        })
        .finally(() => {
            // Restore buttons
            const finalMinusBtn = document.querySelector(`button[onclick*="decreaseQuantity('${type}', ${itemId}"]`);
            const finalPlusBtn = document.querySelector(`button[onclick*="increaseQuantity('${type}', ${itemId}"]`);
            
            if(finalMinusBtn) {
                finalMinusBtn.disabled = false;
                finalMinusBtn.textContent = '-';
            }
            if(finalPlusBtn) {
                finalPlusBtn.disabled = false;
                finalPlusBtn.textContent = '+';
            }
        });
    }
</script>
@endsection
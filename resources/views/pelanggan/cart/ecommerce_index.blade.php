@extends('layouts.ecommerce')

@section('title', 'Keranjang - PlayStation')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-cart me-2"></i>Keranjang Anda</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
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
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->item_image }}" alt="{{ $item->item_name }}" 
                                                     class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-1">{{ $item->item_name }}</h6>
                                                    @if(!$hasStock)
                                                        <small class="text-warning"><i class="bi bi-exclamation-triangle"></i> Stok tidak mencukupi (tersedia: {{ $availableStock }})</small>
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
                                            @endphp
                                            <span class="badge bg-info">{{ $typeLabel }}</span>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}/{{ $item->price_type == 'per_jam' ? 'jam' : 'hari' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        onclick="decreaseQuantity('{{ $item->type }}', {{ $item->item_id }})"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <span id="quantity_{{ $item->type }}_{{ $item->item_id }}"
                                                      class="mx-2"
                                                      data-price="{{ $item->price }}"
                                                      data-max-stock="{{ $availableStock }}"
                                                      data-original-value="{{ $item->quantity }}"
                                                      style="min-width: 30px; display: inline-block; text-align: center;">
                                                    {{ $item->quantity }}
                                                </span>
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        onclick="increaseQuantity('{{ $item->type }}', {{ $item->item_id }})"
                                                        {{ $item->quantity >= $availableStock ? 'disabled' : '' }}>
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td id="total_{{ $item->type }}_{{ $item->item_id }}">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('pelanggan.cart.remove') }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $item->type }}">
                                                <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        onclick="return confirm('Hapus item ini dari keranjang?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-center">
                                                <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                                                <h5 class="text-muted">Keranjang Anda kosong</h5>
                                                <p class="text-muted">Mulai jelajahi produk kami untuk menambahkan item ke keranjang</p>
                                                <a href="{{ route('dashboard.pelanggan') }}" class="btn btn-primary">
                                                    <i class="bi bi-search me-1"></i>Jelajahi Produk
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(!$cartItems->isEmpty())
                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Pastikan informasi kontak Anda lengkap untuk proses penyewaan
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    @php
                                        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);
                                        $user = auth()->user();
                                        $needsProfileUpdate = empty($user->phone) || empty($user->address);
                                    @endphp
                                    <h5 class="card-title">Ringkasan</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total:</span>
                                        <strong id="grand-total">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                                    </div>
                                    
                                    @if($needsProfileUpdate)
                                        <a href="{{ route('pelanggan.profile.edit') }}" 
                                           class="btn btn-warning w-100 mb-2">
                                            <i class="bi bi-person-circle me-1"></i>Lengkapi Profil
                                        </a>
                                    @else
                                        <a href="{{ route('pelanggan.rentals.create') }}" 
                                           class="btn btn-success w-100 mb-2">
                                            <i class="bi bi-play-circle me-1"></i>Lanjutkan Penyewaan
                                        </a>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('pelanggan.cart.clear') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger w-100"
                                                onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
                                            <i class="bi bi-trash me-1"></i>Hapus Semua
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
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

        const grandTotalElement = document.getElementById('grand-total');
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
            showFlashMessage('Jumlah sudah mencapai stok maksimal (' + maxStock + ')', 'warning');
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
            showFlashMessage('Jumlah minimal adalah 1', 'warning');
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
        if(minusBtn) minusBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
        if(plusBtn) plusBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';

        fetch('/pelanggan/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
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

                // Update cart badge count
                updateCartBadge();

                // Update button states
                const maxStock = parseInt(quantityElement.dataset.maxStock || 999);
                if(minusBtn) minusBtn.disabled = (newQuantity <= 1);
                if(plusBtn) plusBtn.disabled = (newQuantity >= maxStock);

                // Show success message
                showFlashMessage(data.message, 'success');
            } else {
                // Show error message and reset quantity display
                showFlashMessage(data.message || 'Terjadi kesalahan saat memperbarui jumlah', 'danger');
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
                showFlashMessage('Terjadi kesalahan saat memperbarui jumlah: ' + error.message, 'danger');
                quantityElement.textContent = originalValue;
            }
        })
        .finally(() => {
            // Restore buttons
            const finalMinusBtn = document.querySelector(`button[onclick*="decreaseQuantity('${type}', ${itemId}"]`);
            const finalPlusBtn = document.querySelector(`button[onclick*="increaseQuantity('${type}', ${itemId}"]`);

            if(finalMinusBtn) {
                finalMinusBtn.disabled = false;
                finalMinusBtn.innerHTML = '<i class="bi bi-dash"></i>';
            }
            if(finalPlusBtn) {
                finalPlusBtn.disabled = false;
                finalPlusBtn.innerHTML = '<i class="bi bi-plus"></i>';
            }
        });
    }
</script>
@endsection
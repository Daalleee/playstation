@extends('layouts.app')
@section('content')
    <style>
        .dash-dark {
            background: #2b3156;
            color: #e7e9ff;
            border-radius: 0;
            min-height: 100dvh;
        }

        .dash-layout {
            display: flex;
            gap: 1rem;
            height: 100vh;
        }

        .dash-sidebar {
            flex: 0 0 280px;
            background: #3a2a70;
            border-radius: 1rem;
            padding: 1.25rem 1rem;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, .25);
            height: 100vh;
            overflow-y: auto;
            position: sticky;
            top: 0;
        }

        .dash-main {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .page-hero {
            text-align: center;
            padding: 1rem;
        }

        .page-hero h2 {
            font-weight: 800;
            margin: 0;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr auto;
            gap: 1rem;
            margin: 0 1rem 1rem;
            align-items: end;
        }

        .select-dark,
        .input-dark {
            width: 100%;
            background: #23284a;
            color: #eef1ff;
            border: 1px solid #2f3561;
            border-radius: .6rem;
            padding: .55rem .75rem;
        }

        .btn-cta {
            background: #2ecc71;
            border: none;
            color: #0e1a2f;
            font-weight: 800;
            padding: .55rem 1rem;
            border-radius: .6rem;
            min-width: 120px;
        }

        .card-dark {
            background: #1f2446;
            border: none;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, .25);
        }

        table.dark {
            width: 100%;
            color: #e7e9ff;
            border-collapse: collapse;
        }

        table.dark th,
        table.dark td {
            border: 1px solid #2f3561;
            padding: .5rem .6rem;
        }

        table.dark thead th {
            background: #23284a;
            font-weight: 800;
        }

        .badge-ok {
            background: #1a7a4f;
            color: #fff;
            border-radius: 999px;
            padding: .2rem .6rem;
            font-size: .85rem;
        }

        .badge-warn {
            background: #b8651f;
            color: #fff;
            border-radius: 999px;
            padding: .2rem .6rem;
            font-size: .85rem;
        }

        .badge-danger {
            background: #c0392b;
            color: #fff;
            border-radius: 999px;
            padding: .2rem .6rem;
            font-size: .85rem;
        }

        .badge-success {
            background: #1e8449;
            color: #fff;
            border-radius: 999px;
            padding: .2rem .6rem;
            font-size: .85rem;
            font-weight: 700;
        }

        .badge-warning {
            background: #d68910;
            color: #fff;
            border-radius: 999px;
            padding: .2rem .6rem;
            font-size: .85rem;
            font-weight: 700;
        }

        .btn-detail {
            background: #5b6bb8;
            color: #fff;
            border: none;
            padding: .3rem .6rem;
            border-radius: .4rem;
            text-decoration: none;
        }

        .btn-cta {
            background: #1e8449;
            border: none;
            color: #fff;
            font-weight: 800;
            padding: .55rem 1rem;
            border-radius: .6rem;
            cursor: pointer;
        }

        .btn-cta:hover {
            background: #27ae60;
        }

        .btn-cta:disabled {
            background: #7f8c8d;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-danger {
            background: #c0392b;
            border: none;
            color: #fff;
            font-weight: 800;
            padding: .55rem 1rem;
            border-radius: .6rem;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #e74c3c;
        }

        .btn-warning {
            background: #f39c12;
            border: none;
            color: #fff;
            font-weight: 800;
            padding: .55rem 1rem;
            border-radius: .6rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-warning:hover {
            background: #e67e22;
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .dash-layout {
                flex-direction: column;
            }

            .dash-sidebar {
                flex: 0 0 auto;
                position: static;
                height: auto;
            }

            .dash-main {
                height: auto;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="dash-dark p-3">
        <div class="dash-layout">
            @include('pelanggan.partials.sidebar')

            <main class="dash-main">
                <div class="page-hero">
                    <h2>Keranjang Penyewaan</h2>
                </div>

                <div class="card-dark">
                    <div class="table-responsive">
                        <table id="cart-table" class="dark">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Tipe</th>
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
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $item->item_image }}" alt="{{ $item->item_name }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 0.5rem;">
                                                <div>
                                                    <div class="fw-bold">{{ $item->item_name }}</div>
                                                    @if(!$hasStock)
                                                        <small class="text-warning">⚠️ Stok tidak mencukupi (tersedia: {{ $availableStock }})</small>
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
                                            {{ $typeLabel }}
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}/{{ $item->price_type == 'per_jam' ? 'jam' : 'hari' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="decreaseQuantity('{{ $item->type }}', {{ $item->item_id }})"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                                <span id="quantity_{{ $item->type }}_{{ $item->item_id }}" 
                                                      data-price="{{ $item->price }}"
                                                      data-max-stock="{{ $availableStock }}"
                                                      data-original-value="{{ $item->quantity }}">{{ $item->quantity }}</span>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="increaseQuantity('{{ $item->type }}', {{ $item->item_id }})"
                                                        {{ $item->quantity >= $availableStock ? 'disabled' : '' }}>+</button>
                                            </div>
                                        </td>
                                        <td id="total_{{ $item->type }}_{{ $item->item_id }}">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('pelanggan.cart.remove') }}"
                                                class="d-inline" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $item->type }}">
                                                <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-cart-x fs-1"></i>
                                                <p class="mt-2">Keranjang Anda kosong.</p>
                                                <a href="{{ route('pelanggan.unitps.index') }}" class="btn btn-cta mt-2">Mulai Belanja</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            @php
                                $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);
                                $user = auth()->user();
                                $needsProfileUpdate = empty($user->phone) || empty($user->address);
                            @endphp
                            <div class="fw-bold fs-5">Total: Rp {{ number_format($total, 0, ',', '.') }}</div>
                            <div>
                                @if($needsProfileUpdate && !$cartItems->isEmpty())
                                    <a href="{{ route('pelanggan.profile.edit') }}" 
                                       class="btn btn-warning me-2"
                                       onclick="alert('Silakan lengkapi nomor telepon dan alamat Anda terlebih dahulu sebelum melakukan penyewaan.')">
                                        <i class="bi bi-exclamation-triangle"></i> Lengkapi Profil Dulu
                                    </a>
                                @else
                                    <a href="{{ route('pelanggan.rentals.create') }}"
                                        class="btn btn-cta me-2 {{ $cartItems->isEmpty() ? 'disabled' : '' }}">Buat
                                        Penyewaan</a>
                                @endif
                                @if (!$cartItems->isEmpty())
                                    <form method="POST" action="{{ route('pelanggan.cart.clear') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">Hapus
                                            Semua</button>
                                    </form>
                                @else
                                    <button class="btn btn-danger" disabled>Hapus Semua</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </main>
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
            
            const grandTotalElement = document.querySelector('.fw-bold.fs-5');
            if(grandTotalElement) {
                const formattedTotal = 'Total: Rp ' + grandTotal.toLocaleString('id-ID').replace(/\,/g, '.');
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
            if(minusBtn) minusBtn.textContent = '...';
            if(plusBtn) plusBtn.textContent = '...';
            
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
                    finalMinusBtn.textContent = '-';
                }
                if(finalPlusBtn) {
                    finalPlusBtn.disabled = false;
                    finalPlusBtn.textContent = '+';
                }
            });
        }
    </script>
</div>
@endsection
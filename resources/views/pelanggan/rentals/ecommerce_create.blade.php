@extends('layouts.ecommerce')

@section('title', 'Buat Penyewaan - PlayStation Rental')

@section('content')
<div class="container">
    <div class="row g-4">
        <!-- Shopping Cart Section -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-cart me-2"></i>Item yang Akan Disewa</h4>
                </div>
                <div class="card-body">
                    @forelse($cartItems as $item)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="row align-items-center">
                                @if(isset($directItem) && $directItem)
                                    @php
                                        $itemModel = null;
                                        $imageField = '';
                                        $modelClass = null;
                                        switch($item['type']) {
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
                                        if($modelClass) {
                                            $itemId = $item['item_id'] ?? $item['id'] ?? null;
                                            $itemModel = $modelClass::find($itemId);
                                        }
                                    @endphp

                                    <div class="col-md-3">
                                        @if($itemModel && $itemModel->$imageField)
                                            <img src="{{ asset('storage/' . $itemModel->$imageField) }}" alt="{{ $item['name'] }}" class="img-fluid rounded">
                                        @else
                                            <img src="https://placehold.co/150x150/f8f9fa/6c757d?text={{ urlencode($item['type']) }}" alt="{{ $item['name'] }}" class="img-fluid rounded">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h5>{{ $item['name'] }}</h5>
                                        @if($itemModel)
                                            @if($item['type'] == 'unitps')
                                                <p class="text-muted mb-1"><strong>Model:</strong> {{ $itemModel->model ?? 'N/A' }}</p>
                                                <p class="text-muted mb-1"><strong>Merek:</strong> {{ $itemModel->brand ?? 'N/A' }}</p>
                                            @elseif($item['type'] == 'game')
                                                <p class="text-muted mb-1"><strong>Platform:</strong> {{ $itemModel->platform ?? 'N/A' }}</p>
                                                <p class="text-muted mb-1"><strong>Genre:</strong> {{ $itemModel->genre ?? 'N/A' }}</p>
                                            @elseif($item['type'] == 'accessory')
                                                <p class="text-muted mb-1"><strong>Jenis:</strong> {{ $itemModel->jenis ?? 'N/A' }}</p>
                                            @endif
                                        @endif
                                        <p class="text-primary fw-bold mb-0">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }} {{ $item['price_type'] == 'per_jam' ? 'per jam' : 'per hari' }}
                                        </p>
                                    </div>
                                @else
                                    @php
                                        $itemModel = null;
                                        $imageField = '';
                                        $modelClass = null;
                                        switch($item->type) {
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
                                        if($modelClass && $item->item_id) {
                                            $itemModel = $modelClass::find($item->item_id);
                                        }
                                    @endphp

                                    <div class="col-md-3">
                                        @if($itemModel && $itemModel->$imageField)
                                            <img src="{{ asset('storage/' . $itemModel->$imageField) }}" alt="{{ $item->name }}" class="img-fluid rounded">
                                        @else
                                            <img src="https://placehold.co/150x150/f8f9fa/6c757d?text={{ urlencode($item->type) }}" alt="{{ $item->name }}" class="img-fluid rounded">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h5>{{ $item->name }}</h5>
                                        @if($itemModel)
                                            @if($item->type == 'unitps')
                                                <p class="text-muted mb-1"><strong>Model:</strong> {{ $itemModel->model ?? 'N/A' }}</p>
                                                <p class="text-muted mb-1"><strong>Merek:</strong> {{ $itemModel->brand ?? 'N/A' }}</p>
                                                <p class="text-muted mb-1"><strong>Stok Tersedia:</strong> {{ $itemModel->stock ?? 0 }}</p>
                                            @elseif($item->type == 'game')
                                                <p class="text-muted mb-1"><strong>Platform:</strong> {{ $itemModel->platform ?? 'N/A' }}</p>
                                                <p class="text-muted mb-1"><strong>Genre:</strong> {{ $itemModel->genre ?? 'N/A' }}</p>
                                                <p class="text-muted mb-1"><strong>Stok Tersedia:</strong> {{ $itemModel->stok ?? 0 }}</p>
                                            @elseif($item->type == 'accessory')
                                                <p class="text-muted mb-1"><strong>Jenis:</strong> {{ $itemModel->jenis ?? 'N/A' }}</p>
                                                <p class="text-muted mb-1"><strong>Stok Tersedia:</strong> {{ $itemModel->stok ?? 0 }}</p>
                                            @endif
                                        @endif
                                        <p class="text-primary fw-bold mb-0">
                                            Rp {{ number_format($item->price, 0, ',', '.') }} {{ $item->price_type == 'per_jam' ? 'per jam' : 'per hari' }}
                                        </p>
                                        @if($item->type == 'game' || $item->type == 'accessory')
                                            <p class="text-muted mb-0"><strong>Jumlah:</strong> {{ $item->quantity }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                            <p class="text-muted">Keranjang kosong.</p>
                            <a href="{{ route('dashboard.pelanggan') }}" class="btn btn-primary">Jelajahi Produk</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Rental Details Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Detail Penyewaan</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pelanggan.rentals.store') }}{{ isset($directItem) && $directItem && request()->has('type') && request()->has('id') ? '?type=' . request('type') . '&id=' . request('id') : '' }}">
                        @csrf
                        @if(isset($directItem) && $directItem && request()->has('type') && request()->has('id'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="hidden" name="id" value="{{ request('id') }}">
                        @endif

                        <div class="mb-3">
                            <label for="rental_date" class="form-label"><i class="bi bi-calendar me-1"></i>Tanggal Mulai Sewa</label>
                            <input type="date" id="rental_date" name="rental_date" value="{{ old('rental_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required class="form-control">
                        </div>
                        
                        <div class="mb-3">
                            <label for="return_date" class="form-label"><i class="bi bi-calendar-check me-1"></i>Tanggal Kembali</label>
                            <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label"><i class="bi bi-sticky me-1"></i>Catatan (Opsional)</label>
                            <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            @if(isset($directItem) && $directItem)
                                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>
                            @else
                                <a href="{{ route('pelanggan.cart.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Keranjang
                                </a>
                            @endif
                            
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>Selesaikan Penyewaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add to Cart for Direct Item -->
            @if(isset($directItem) && $directItem)
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-bag-plus me-2"></i>Tambah ke Keranjang</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('pelanggan.cart.add') }}">
                            @csrf
                            @php
                                $firstItem = $cartItems->first();
                            @endphp
                            @if(is_array($firstItem))
                                <input type="hidden" name="type" value="{{ $firstItem['type'] }}">
                                <input type="hidden" name="id" value="{{ $firstItem['item_id'] ?? $firstItem['id'] ?? null }}">
                                <input type="hidden" name="price_type" value="{{ $firstItem['price_type'] }}">

                                @if($firstItem['type'] == 'unitps')
                                    @php
                                        $availableStock = $firstItem['stok'] ?? 0;
                                        if (isset($firstItem['item_id'])) {
                                            $unitPS = \App\Models\UnitPS::find($firstItem['item_id']);
                                            if ($unitPS) {
                                                $availableStock = $unitPS->instances()->where('status', 'available')->count();
                                            }
                                        }
                                    @endphp
                                    @if($availableStock > 0)
                                        <div class="mb-3">
                                            <label for="quantity_direct" class="form-label">Jumlah</label>
                                            <input type="number" id="quantity_direct" name="quantity" value="{{ $firstItem['quantity'] ?? 1 }}" min="1" max="{{ $availableStock }}" class="form-control">
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Maaf, unit ini sedang tidak tersedia.
                                        </div>
                                    @endif
                                @elseif($firstItem['type'] == 'game' || $firstItem['type'] == 'accessory')
                                    <div class="mb-3">
                                        <label for="quantity_direct" class="form-label">Jumlah</label>
                                        <input type="number" id="quantity_direct" name="quantity" value="{{ $firstItem['quantity'] ?? 1 }}" min="1" max="{{ $firstItem['stok'] ?? 10 }}" class="form-control">
                                    </div>
                                @else
                                    <input type="hidden" name="quantity" value="1">
                                @endif
                            @else
                                <input type="hidden" name="type" value="{{ $firstItem->type }}">
                                <input type="hidden" name="id" value="{{ $firstItem->item_id }}">
                                <input type="hidden" name="price_type" value="{{ $firstItem->price_type }}">

                                @if($firstItem->type == 'unitps')
                                    @php
                                        $availableStock = $firstItem->stok ?? 0;
                                        if ($firstItem->item_id) {
                                            $unitPS = \App\Models\UnitPS::find($firstItem->item_id);
                                            if ($unitPS) {
                                                $availableStock = $unitPS->instances()->where('status', 'available')->count();
                                            }
                                        }
                                    @endphp
                                    @if($availableStock > 0)
                                        <div class="mb-3">
                                            <label for="quantity_direct" class="form-label">Jumlah</label>
                                            <input type="number" id="quantity_direct" name="quantity" value="{{ $firstItem->quantity }}" min="1" max="{{ $availableStock }}" class="form-control">
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Maaf, unit ini sedang tidak tersedia.
                                        </div>
                                    @endif
                                @elseif($firstItem->type == 'game' || $firstItem->type == 'accessory')
                                    <div class="mb-3">
                                        <label for="quantity_direct" class="form-label">Jumlah</label>
                                        <input type="number" id="quantity_direct" name="quantity" value="{{ $firstItem->quantity }}" min="1" max="{{ $firstItem->stok ?? 10 }}" class="form-control">
                                    </div>
                                @else
                                    <input type="hidden" name="quantity" value="1">
                                @endif
                            @endif
                            <button type="submit" class="btn btn-info w-100">
                                <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Set up date validation
    document.addEventListener('DOMContentLoaded', function() {
        const rentalDateInput = document.getElementById('rental_date');
        const returnDateInput = document.getElementById('return_date');

        // Set minimum return date based on rental date
        rentalDateInput.addEventListener('change', function() {
            const rentalDate = new Date(this.value);
            rentalDate.setDate(rentalDate.getDate() + 1);
            const minReturnDate = rentalDate.toISOString().split('T')[0];
            returnDateInput.min = minReturnDate;

            // If return date is not set or is before the new minimum, clear it
            if (!returnDateInput.value || new Date(returnDateInput.value) < rentalDate) {
                returnDateInput.value = '';
            }
        });

        // When return date changes, validate it's after rental date
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
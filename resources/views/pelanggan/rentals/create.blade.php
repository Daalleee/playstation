@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; height: 100vh; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); height: 100vh; overflow-y: auto; position: sticky; top: 0; }
  .dash-main{ flex:1; overflow-y: auto; padding: 1rem; }
  .page-hero{ text-align:center; padding:1rem; }
  .page-hero h2{ margin:0; font-weight:800; }
  .grid-2{ display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  .list-item{ border-top:1px solid #2f3561; padding:.8rem 0; }
  .list-item:first-child{ border-top:none; }
  .muted{ color:#cfd3ff; opacity:.9; }
  .input-dark, .select-dark, .textarea-dark{ width:100%; background:#23284a; color:#eef1ff; border:1px solid #2f3561; border-radius:.6rem; padding:.55rem .75rem; }
  .btn-green{ background:#2ecc71; color:#0e1a2f; font-weight:800; border:none; border-radius:.6rem; padding:.7rem 1rem; width:100%; }
  .btn-grey{ background:#6c757d; color:#fff; border:none; border-radius:.6rem; padding:.55rem .9rem; text-decoration:none; }
  .btn-cart{ background:#007bff; color:#fff; border:none; border-radius:.6rem; padding:.7rem 1rem; width:200px; text-decoration:none; display:flex; align-items:center; gap:.3rem; justify-content: center; transition: all 0.2s ease; }
  .btn-back{ background:#6c757d; color:#fff; border:none; border-radius:.6rem; padding:.7rem 1rem; width:200px; text-decoration:none; display:flex; align-items:center; gap:.3rem; justify-content: center; transition: all 0.2s ease; }
  .btn-cart:hover{ background:#0069d9; transform: translateY(-2px); }
  .btn-cart:active{ background:#005cbf; transform: translateY(0); }
  .btn-back:hover{ background:#5a6268; transform: translateY(-2px); }
  .btn-back:active{ background:#495057; transform: translateY(0); }
  .btn-green:hover{ background:#28d66a; transform: translateY(-2px); }
  .btn-green:active{ background:#22c55e; transform: translateY(0); }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ flex:0 0 auto; position:static; height: auto; } .dash-main{ height: auto; } .grid-2{ grid-template-columns:1fr; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="page-hero"><h2>Buat Penyewaan</h2></div>

      @if($errors->any())
        <div class="alert alert-danger">{!! implode('<br>', $errors->all()) !!}</div>
      @endif

      <div class="grid-2">
        <section class="card-dark">
          <h5 class="mb-3">Item yang Akan Disewa</h5>
          @forelse($cartItems as $item)
            <div class="list-item">
              <div class="d-flex align-items-start">
                @if(isset($directItem) && $directItem)
                  <!-- Get the item model to display image -->
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
                        $itemId = $item['id'] ?? $item['item_id'] ?? null;
                        if(!$itemId && isset($_GET['id'])) {
                            $itemId = $_GET['id'];
                        }
                        $itemModel = $modelClass::find($itemId);
                    }
                  @endphp
                  
                  @if($itemModel && $itemModel->$imageField)
                    <img src="{{ asset('storage/' . $itemModel->$imageField) }}" alt="{{ $item['name'] }}" class="me-3" style="width: 120px; height: 120px; object-fit: cover;">
                  @else
                    <img src="https://placehold.co/120x120/49497A/FFFFFF?text=Item" alt="{{ $item['name'] }}" class="me-3" style="width: 120px; height: 120px; object-fit: cover;">
                  @endif
                  
                  <div class="flex-grow-1">
                    <div class="fw-bold">{{ $item['name'] }}</div>
                    @if($itemModel)
                      @if($item['type'] == 'unitps')
                        <div class="muted">Model: {{ $itemModel->model ?? 'N/A' }}</div>
                        <div class="muted">Merek: {{ $itemModel->merek ?? 'N/A' }}</div>
                        <div class="muted">Kondisi: {{ $itemModel->kondisi ?? 'N/A' }}</div>
                      @elseif($item['type'] == 'game')
                        <div class="muted">Platform: {{ $itemModel->platform ?? 'N/A' }}</div>
                        <div class="muted">Genre: {{ $itemModel->genre ?? 'N/A' }}</div>
                        <div class="muted">Kondisi: {{ $itemModel->kondisi ?? 'N/A' }}</div>
                      @elseif($item['type'] == 'accessory')
                        <div class="muted">Jenis: {{ $itemModel->jenis ?? 'N/A' }}</div>
                        <div class="muted">Kondisi: {{ $itemModel->kondisi ?? 'N/A' }}</div>
                      @endif
                    @endif
                    <div style="padding-left: 0;">
                      <div class="muted">Harga: Rp {{ number_format($item['price'], 0, ',', '.') }} {{ $item['price_type'] == 'per_jam' ? 'per jam' : 'per hari' }}</div>
                    </div>

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
                  
                  @if($itemModel && $itemModel->$imageField)
                    <img src="{{ asset('storage/' . $itemModel->$imageField) }}" alt="{{ $item->name }}" class="me-3" style="width: 120px; height: 120px; object-fit: cover;">
                  @else
                    <img src="https://placehold.co/120x120/49497A/FFFFFF?text=Item" alt="{{ $item->name }}" class="me-3" style="width: 120px; height: 120px; object-fit: cover;">
                  @endif
                  
                  <div class="flex-grow-1">
                    <div class="fw-bold">{{ $item->name }}</div>
                    @if($itemModel)
                      @if($item->type == 'unitps')
                        <div class="muted">Model: {{ $itemModel->model ?? 'N/A' }}</div>
                        <div class="muted">Merek: {{ $itemModel->merek ?? 'N/A' }}</div>
                        <div class="muted">Kondisi: {{ $itemModel->kondisi ?? 'N/A' }}</div>
                        <div class="muted">Stok Tersedia: {{ $itemModel->stok ?? 0 }}</div>
                      @elseif($item->type == 'game')
                        <div class="muted">Platform: {{ $itemModel->platform ?? 'N/A' }}</div>
                        <div class="muted">Genre: {{ $itemModel->genre ?? 'N/A' }}</div>
                        <div class="muted">Kondisi: {{ $itemModel->kondisi ?? 'N/A' }}</div>
                        <div class="muted">Stok Tersedia: {{ $itemModel->stok ?? 0 }}</div>
                      @elseif($item->type == 'accessory')
                        <div class="muted">Jenis: {{ $itemModel->jenis ?? 'N/A' }}</div>
                        <div class="muted">Kondisi: {{ $itemModel->kondisi ?? 'N/A' }}</div>
                        <div class="muted">Stok Tersedia: {{ $itemModel->stok ?? 0 }}</div>
                      @endif
                    @endif
                    <div class="mt-1" style="padding-left: 0;">
                      <div class="muted">Harga: Rp {{ number_format($item->price, 0, ',', '.') }} {{ $item->price_type == 'per_jam' ? 'per jam' : 'per hari' }}</div>
                      @if($item->type == 'game' || $item->type == 'accessory')
                        <div class="muted">Jumlah: {{ $item->quantity }}</div>
                      @endif
                    </div>
                  </div>
                @endif
              </div>
            </div>
          @empty
            <div class="muted">Keranjang kosong.</div>
          @endforelse
          @if(isset($directItem) && $directItem)
            <div class="text-center mt-2">
              <form method="POST" action="{{ route('pelanggan.cart.add') }}">
                @csrf
                @php
                  $firstItem = $cartItems->first();
                @endphp
                @if(is_array($firstItem))
                  <input type="hidden" name="type" value="{{ $firstItem['type'] }}">
                  <input type="hidden" name="id" value="{{ $firstItem['item_id'] ?? $_GET['id'] ?? $firstItem['id'] ?? null }}">
                  <input type="hidden" name="price_type" value="{{ $firstItem['price_type'] }}">
                  
                  @if($firstItem['type'] == 'unitps')
                    <!-- For Unit PS, only allow 1 quantity -->
                    <input type="hidden" name="quantity" value="1">
                  @elseif($firstItem['type'] == 'game' || $firstItem['type'] == 'accessory')
                    <!-- For Games and Accessories, allow quantity adjustment -->
                    <div class="d-flex justify-content-center mt-3">
                      <div class="d-flex align-items-center gap-2">
                        <label for="quantity_direct" class="mb-0" style="font-size: 0.9rem;">Jumlah:</label>
                        <input type="number" id="quantity_direct" name="quantity" value="{{ $firstItem['quantity'] ?? 1 }}" min="1" max="{{ $firstItem['stok'] ?? 10 }}" class="form-control" style="width: 60px; height: 30px; font-size: 0.9rem; padding: 2px 5px;">
                      </div>
                    </div>
                  @else
                    <!-- Default case -->
                    <input type="hidden" name="quantity" value="1">
                  @endif
                @else
                  <input type="hidden" name="type" value="{{ $firstItem->type }}">
                  <input type="hidden" name="id" value="{{ $firstItem->item_id }}">
                  <input type="hidden" name="price_type" value="{{ $firstItem->price_type }}">
                  
                  @if($firstItem->type == 'unitps')
                    <!-- For Unit PS, only allow 1 quantity -->
                    <input type="hidden" name="quantity" value="1">
                  @elseif($firstItem->type == 'game' || $firstItem->type == 'accessory')
                    <!-- For Games and Accessories, allow quantity adjustment -->
                    <div class="d-flex justify-content-center mt-3">
                      <div class="d-flex align-items-center gap-2">
                        <label for="quantity_direct" class="mb-0" style="font-size: 0.9rem;">Jumlah:</label>
                        <input type="number" id="quantity_direct" name="quantity" value="{{ $firstItem->quantity }}" min="1" max="{{ $firstItem->stok ?? 10 }}" class="form-control" style="width: 60px; height: 30px; font-size: 0.9rem; padding: 2px 5px;">
                      </div>
                    </div>
                  @else
                    <!-- Default case -->
                    <input type="hidden" name="quantity" value="1">
                  @endif
                @endif
                <div class="mt-3">
                  <button type="submit" class="btn-cart" style="max-width: 200px; margin: 0 auto; display: inline-block;">
                    <i class="bi bi-cart"></i> Keranjang
                  </button>
                </div>
              </form>
            </div>
          @endif
          <div class="text-center mt-3">
            @if(isset($directItem) && $directItem)
              <a href="javascript:history.back()" class="btn-back" style="max-width: 200px; margin: 0 auto; display: inline-block;">
                <i class="bi bi-arrow-left"></i> Kembali
              </a>
            @else
              <a href="{{ route('pelanggan.cart.index') }}" class="btn-back" style="max-width: 200px; margin: 0 auto; display: inline-block;">
                <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
              </a>
            @endif
          </div>
        </section>

        <section class="card-dark">
          <h5 class="mb-3">Detail Penyewaan</h5>
          <form method="POST" action="{{ route('pelanggan.rentals.store') }}">
            @csrf
            <div class="mb-3">
              <label for="rental_date" class="form-label">Tanggal Mulai Sewa</label>
              <input type="date" id="rental_date" name="rental_date" value="{{ old('rental_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required class="input-dark">
            </div>
            <div class="mb-3">
              <label for="return_date" class="form-label">Tanggal Kembali</label>
              <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required class="input-dark">
            </div>
            <div class="mb-3">
              <label for="notes" class="form-label">Catatan (Opsional)</label>
              <textarea id="notes" name="notes" rows="3" class="textarea-dark">{{ old('notes') }}</textarea>
            </div>
            <div class="text-center">
              <button type="submit" class="btn-green" style="max-width: 200px; margin: 0 auto; display: inline-block;">
                <i class="bi bi-check-circle"></i> Buat Penyewaan
              </button>
            </div>
          </form>
        </section>
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
    </main>
  </div>
</div>
@endsection
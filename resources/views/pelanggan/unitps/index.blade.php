@extends('layouts.app')
@section('content')
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; height: 100vh; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); height: 100vh; overflow-y: auto; position: sticky; top: 0; }
  .dash-main{ flex:1; overflow-y: auto; padding: 1rem; }
  .page-hero{ text-align:center; padding:1rem; }
  .page-hero h2{ font-weight:800; margin:0; }
  .filter-row{ display:grid; grid-template-columns: 1fr 1fr 2fr auto; gap:1rem; margin:0 1rem 1rem; align-items:end; }
  .select-dark, .input-dark{ width:100%; background:#23284a; color:#eef1ff; border:1px solid #2f3561; border-radius:.6rem; padding:.55rem .75rem; }
  .btn-cta{ background:#2ecc71; border:none; color:#0e1a2f; font-weight:800; padding:.55rem 1rem; border-radius:.6rem; min-width:120px; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  table.dark{ width:100%; color:#e7e9ff; border-collapse:collapse; }
  table.dark th, table.dark td{ border:1px solid #2f3561; padding:.5rem .6rem; }
  table.dark thead th{ background:#23284a; font-weight:800; }
  .badge-ok{ background:#1a7a4f; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-warn{ background:#b8651f; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-danger{ background:#c0392b; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; }
  .badge-success{ background:#1e8449; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; font-weight:700; }
  .badge-warning{ background:#d68910; color:#fff; border-radius:999px; padding:.2rem .6rem; font-size:.85rem; font-weight:700; }
  .btn-detail{ background:#5b6bb8; color:#fff; border:none; padding:.3rem .6rem; border-radius:.4rem; text-decoration:none; }
  .btn-cta{ background:#1e8449; border:none; color:#fff; font-weight:800; padding:.55rem 1rem; border-radius:.6rem; cursor:pointer; }
  .btn-cta:hover{ background:#27ae60; }
  .btn-cta:disabled{ background:#7f8c8d; cursor:not-allowed; opacity:0.6; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ flex:0 0 auto; position:static; height: auto; } .dash-main{ height: auto; } .filter-row{ grid-template-columns:1fr; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="page-hero">
        <h2>Daftar Unit PlayStation</h2>
      </div>

      <form method="GET" action="{{ route('pelanggan.unitps.list') }}" class="filter-row">
        <div>
          <label class="mb-1 d-block fw-bold">Model</label>
          <select name="model" class="select-dark">
            <option value="">Semua Model</option>
            @foreach (['PS3','PS4','PS5'] as $opt)
              <option value="{{ $opt }}" @selected(request('model')===$opt)>{{ $opt }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="mb-1 d-block fw-bold">Merek</label>
          <select name="brand" class="select-dark">
            <option value="">Semua Merek</option>
            <option value="Sony" @selected(request('brand')==='Sony')>Sony</option>
          </select>
        </div>
        <div>
          <label class="mb-1 d-block fw-bold">Cari unit</label>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari unit PlayStation" class="input-dark" />
        </div>
        <div>
          <button class="btn-cta" type="submit" style="width:120px; padding:.65rem 1.5rem;">Cari</button>
        </div>
      </form>

      <div class="card-dark">
        <div class="table-responsive">
          <table class="dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Model/Merek</th>
                <th>Foto</th>
                <th>Stok</th>
                <th>Harga/Jam</th>
                <th>Kondisi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($units as $unit)
                <tr>
                  <td>{{ $unit->id }}</td>
                  <td>{{ $unit->nama }}</td>
                  <td>{{ $unit->model }}<br><small class="text-muted">{{ $unit->merek }}</small></td>
                  <td>
                    @if($unit->foto)
                      <img src="{{ asset('storage/' . $unit->foto) }}" alt="{{ $unit->nama }}" style="width:50px; height:50px; object-fit:cover;">
                    @else
                      <img src="https://placehold.co/50x50/49497A/FFFFFF?text=No+Image" alt="{{ $unit->nama }}" style="width:50px; height:50px; object-fit:cover;">
                    @endif
                  </td>
                  <td>
                    @php 
                      $stok = $unit->stok ?? 0;
                      $badgeClass = $stok > 5 ? 'badge-success' : ($stok > 0 ? 'badge-warning' : 'badge-danger');
                    @endphp
                    <span class="{{ $badgeClass }} d-block">{{ $stok }} Unit</span>
                  </td>
                  <td>Rp {{ number_format($unit->harga_per_jam, 0, ',', '.') }}</td>
                  <td>
                    @php $kondisi = strtolower($unit->kondisi ?? 'baik'); @endphp
                    <span class="{{ $kondisi === 'baik' ? 'badge-success' : ($kondisi === 'rusak' ? 'badge-danger' : 'badge-warning') }} d-block">{{ ucfirst($unit->kondisi) }}</span>
                  </td>
                  <td>
                    <div class="d-flex flex-column gap-2">
                      <a href="#" class="btn-detail">Detail</a>
                      <div class="d-flex gap-2 align-items-center">
                        <input type="number" class="input-dark" value="1" min="1" max="{{ $stok }}" 
                               style="width: 80px;" 
                               id="quantity_{{ $unit->id }}" 
                               {{ $stok <= 0 ? 'disabled' : '' }}>
                        <button type="button" class="btn-cta w-100 add-to-cart-btn" 
                                data-type="unitps" 
                                data-id="{{ $unit->id }}" 
                                data-price_type="per_jam"
                                data-stok="{{ $stok }}"
                                {{ $stok <= 0 ? 'disabled' : '' }}>
                          {{ $stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center">Tidak ada unit PlayStation tersedia.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ method_exists($units,'links') ? $units->withQueryString()->links() : '' }}
        </div>
      </div>
    </main>
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
          showFlashMessage('Jumlah tidak valid!', 'danger');
          return;
        }
        
        // Disable button to prevent multiple clicks
        this.disabled = true;
        const originalText = this.textContent;
        this.textContent = 'Memproses...';
        
        fetch('/pelanggan/cart/add', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
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
            showFlashMessage(data.message, 'danger');
          }
          
          // Restore button
          this.disabled = false;
          this.textContent = originalText;
        })
        .catch(error => {
          console.error('Error:', error);
          // Show error message
          showFlashMessage('Terjadi kesalahan saat menambahkan ke keranjang', 'danger');
          
          // Restore button
          this.disabled = false;
          this.textContent = originalText;
        });
      });
    });
  </script>
</div>
@endsection
@include('pelanggan.partials.nav')
<h1>Buat Penyewaan</h1>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
    <!-- Cart Items -->
    <div>
        <h2>Item yang Akan Disewa</h2>
        <div style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1rem;">
            @foreach($cartItems as $item)
            <div style="border-bottom: 1px solid #eee; padding: 1rem 0; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                <h4>{{ $item->name }}</h4>
                <p><strong>Jenis:</strong> {{ ucfirst($item->type) }}</p>
                <p><strong>Harga:</strong> Rp {{ number_format($item->price, 0, ',', '.') }} {{ $item->price_type == 'per_jam' ? 'per jam' : 'per hari' }}</p>
                <p><strong>Jumlah:</strong> {{ $item->quantity }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Rental Form -->
    <div>
        <h2>Detail Penyewaan</h2>
        <form method="POST" action="{{ route('pelanggan.rentals.store') }}" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 1.5rem;">
            @csrf
            
            <div style="margin-bottom: 1rem;">
                <label for="rental_date" style="display: block; margin-bottom: 0.5rem;"><strong>Tanggal Mulai Sewa:</strong></label>
                <input type="date" id="rental_date" name="rental_date" value="{{ old('rental_date', date('Y-m-d')) }}" required 
                       style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label for="return_date" style="display: block; margin-bottom: 0.5rem;"><strong>Tanggal Kembali:</strong></label>
                <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}" required 
                       style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label for="notes" style="display: block; margin-bottom: 0.5rem;"><strong>Catatan (Opsional):</strong></label>
                <textarea id="notes" name="notes" rows="3" 
                          style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 4px;">{{ old('notes') }}</textarea>
            </div>
            
            <div style="margin-top: 2rem;">
                <button type="submit" style="background: #28a745; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; width: 100%;">
                    Buat Penyewaan
                </button>
            </div>
        </form>
    </div>
</div>

<div style="margin-top: 2rem;">
    <a href="{{ route('pelanggan.cart.index') }}" style="background: #6c757d; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;">
        Kembali ke Keranjang
    </a>
</div>

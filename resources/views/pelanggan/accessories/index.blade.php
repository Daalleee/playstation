@include('pelanggan.partials.nav')
<h1>Aksesoris Tersedia</h1>

@if($accessories->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
        @foreach($accessories as $accessory)
        <div style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px;">
            @if($accessory->gambar)
                <img src="{{ asset('storage/'.$accessory->gambar) }}" alt="{{ $accessory->nama }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
            @else
                <div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                    No Image
                </div>
            @endif
            
            <h3 style="margin: 0.5rem 0;">{{ $accessory->nama }}</h3>
            <p><strong>Jenis:</strong> {{ $accessory->jenis }}</p>
            <p><strong>Harga/Hari:</strong> Rp {{ number_format($accessory->harga_per_hari, 0, ',', '.') }}</p>
            <p><strong>Stok:</strong> {{ $accessory->stok }} unit</p>
            @if($accessory->kondisi)
                <p><strong>Kondisi:</strong> {{ $accessory->kondisi }}</p>
            @endif
            
            <div style="margin-top: 1rem;">
                <form method="POST" action="{{ route('pelanggan.cart.add') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="accessory">
                    <input type="hidden" name="id" value="{{ $accessory->id }}">
                    <input type="hidden" name="name" value="{{ $accessory->nama }}">
                    <input type="hidden" name="price" value="{{ $accessory->harga_per_hari }}">
                    <input type="hidden" name="price_type" value="per_hari">
                    <button type="submit" style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    
    <div style="margin-top: 2rem;">
        {{ $accessories->links() }}
    </div>
@else
    <p>Tidak ada aksesoris yang tersedia saat ini.</p>
@endif

@include('pelanggan.partials.nav')
<h1>Unit PS Tersedia</h1>

@if($units->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
        @foreach($units as $unit)
        <div style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px;">
            @if($unit->foto)
                <img src="{{ asset('storage/'.$unit->foto) }}" alt="{{ $unit->nama }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
            @else
                <div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                    No Image
                </div>
            @endif
            
            <h3 style="margin: 0.5rem 0;">{{ $unit->nama }}</h3>
            <p><strong>Merek:</strong> {{ $unit->merek }}</p>
            <p><strong>Model:</strong> {{ $unit->model }}</p>
            <p><strong>Nomor Seri:</strong> {{ $unit->nomor_seri }}</p>
            <p><strong>Harga/Jam:</strong> Rp {{ number_format($unit->harga_per_jam, 0, ',', '.') }}</p>
            <p><strong>Stok:</strong> {{ $unit->stok }} unit</p>
            <p><strong>Status:</strong> {{ $unit->status }}</p>
            @if($unit->kondisi)
                <p><strong>Kondisi:</strong> {{ $unit->kondisi }}</p>
            @endif
            
            <div style="margin-top: 1rem;">
                <form method="POST" action="{{ route('pelanggan.cart.add') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="unitps">
                    <input type="hidden" name="id" value="{{ $unit->id }}">
                    <input type="hidden" name="name" value="{{ $unit->nama }}">
                    <input type="hidden" name="price" value="{{ $unit->harga_per_jam }}">
                    <input type="hidden" name="price_type" value="per_jam">
                    <button type="submit" style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    
    <div style="margin-top: 2rem;">
        {{ $units->links() }}
    </div>
@else
    <p>Tidak ada unit PS yang tersedia saat ini.</p>
@endif

@include('pelanggan.partials.nav')
<h1>Games Tersedia</h1>
<a href="{{ route('dashboard.pelanggan') }}" style="background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;margin-bottom:1rem;display:inline-block;">&larr; Kembali ke Dashboard</a>

@if($games->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
        @foreach($games as $game)
        <div style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px;">
            @if($game->gambar)
                <img src="{{ asset('storage/'.$game->gambar) }}" alt="{{ $game->judul }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
            @else
                <div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                    No Image
                </div>
            @endif
            
            <h3 style="margin: 0.5rem 0;">{{ $game->judul }}</h3>
            <p><strong>Platform:</strong> {{ $game->platform }}</p>
            <p><strong>Genre:</strong> {{ $game->genre }}</p>
            <p><strong>Harga/Hari:</strong> Rp {{ number_format($game->harga_per_hari, 0, ',', '.') }}</p>
            <p><strong>Stok:</strong> {{ $game->stok }} unit</p>
            @if($game->kondisi)
                <p><strong>Kondisi:</strong> {{ $game->kondisi }}</p>
            @endif
            
            <div style="margin-top: 1rem;">
                <form method="POST" action="{{ route('pelanggan.cart.add') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="type" value="game">
                    <input type="hidden" name="id" value="{{ $game->id }}">
                    <input type="hidden" name="name" value="{{ $game->judul }}">
                    <input type="hidden" name="price" value="{{ $game->harga_per_hari }}">
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
        {{ $games->links() }}
    </div>
@else
    <p>Tidak ada game yang tersedia saat ini.</p>
@endif

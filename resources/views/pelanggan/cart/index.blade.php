@include('pelanggan.partials.nav')
<h1>Keranjang Saya</h1>
<a href="{{ route('dashboard.pelanggan') }}" style="background:#6c757d;color:white;padding:0.5rem 1rem;text-decoration:none;border-radius:4px;margin-bottom:1rem;display:inline-block;">&larr; Kembali ke Dashboard</a>

@if(session('status'))
    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        {{ session('status') }}
    </div>
@endif

@if(count($cart) > 0)
    <div style="margin-top: 2rem;">
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th>Item</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr>
                    <td>
                        <strong>{{ $item->name }}</strong><br>
                        <small>{{ ucfirst($item->type) }} - {{ $item->price_type == 'per_jam' ? 'Per Jam' : 'Per Hari' }}</small>
                    </td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('pelanggan.cart.update') }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="cart_id" value="{{ $item->id }}">
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width: 60px; padding: 0.25rem;">
                            <button type="submit" style="background: #007bff; color: white; padding: 0.25rem 0.5rem; border: none; border-radius: 3px; cursor: pointer; margin-left: 0.5rem;">
                                Update
                            </button>
                        </form>
                    </td>
                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('pelanggan.cart.remove') }}" style="display: inline;" onsubmit="return confirm('Hapus item ini?')">
                            @csrf
                            <input type="hidden" name="cart_id" value="{{ $item->id }}">
                            <button type="submit" style="background: #dc3545; color: white; padding: 0.25rem 0.5rem; border: none; border-radius: 3px; cursor: pointer;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td colspan="3">Total</td>
                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        
        <div style="margin-top: 2rem;">
            <form method="POST" action="{{ route('pelanggan.cart.clear') }}" style="display: inline;" onsubmit="return confirm('Kosongkan keranjang?')">
                @csrf
                <button type="submit" style="background: #6c757d; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                    Kosongkan Keranjang
                </button>
            </form>
            
            <a href="{{ route('pelanggan.rentals.create') }}" style="background: #28a745; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; margin-left: 1rem;">
                Lanjut ke Penyewaan
            </a>
        </div>
    </div>
@else
    <div style="text-align: center; margin-top: 3rem;">
        <h3>Keranjang Kosong</h3>
        <p>Belum ada item di keranjang Anda.</p>
        <a href="{{ route('pelanggan.unitps.index') }}" style="background: #007bff; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px;">
            Mulai Berbelanja
        </a>
    </div>
@endif

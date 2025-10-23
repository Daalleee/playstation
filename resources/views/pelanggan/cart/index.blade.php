@extends('layouts.app')
@section('content')
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; border-radius:0; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); position:sticky; top:1rem; min-height:calc(100dvh - 2rem); }
  .dash-main{ flex:1; }
  .page-hero{ text-align:center; padding:1rem; }
  .page-hero h2{ font-weight:800; margin:0; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); }
  table.dark{ width:100%; color:#e7e9ff; border-collapse:collapse; }
  table.dark th, table.dark td{ border:1px solid #2f3561; padding:.5rem .6rem; }
  table.dark thead th{ background:#23284a; font-weight:800; }
  .btn-grey{ background:#6c757d; color:#fff; border:none; border-radius:.4rem; padding:.45rem .8rem; }
  .btn-green{ background:#28a745; color:#fff; border:none; border-radius:.4rem; padding:.45rem .8rem; text-decoration:none; }
  .btn-blue{ background:#007bff; color:#fff; border:none; border-radius:.3rem; padding:.25rem .5rem; }
  .btn-red{ background:#dc3545; color:#fff; border:none; border-radius:.3rem; padding:.25rem .5rem; }
  .input-qty{ width:70px; background:#23284a; color:#eef1ff; border:1px solid #2f3561; border-radius:.4rem; padding:.25rem .4rem; }
  
  /* Empty Cart Styles */
  .empty-cart-container{ 
    background: linear-gradient(135deg, #1f2446 0%, #2b3156 100%); 
    border-radius:1.5rem; 
    padding:4rem 2rem; 
    text-align:center; 
    box-shadow:0 1rem 3rem rgba(0,0,0,.3);
    margin:2rem 1rem;
  }
  .empty-cart-icon{ 
    font-size:6rem; 
    margin-bottom:1.5rem; 
    opacity:0.3;
    animation: float 3s ease-in-out infinite;
  }
  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
  }
  .empty-cart-title{ 
    font-size:2rem; 
    font-weight:800; 
    color:#e7e9ff; 
    margin-bottom:1rem;
  }
  .empty-cart-text{ 
    font-size:1.1rem; 
    color:#b8bfdd; 
    margin-bottom:2rem;
    line-height:1.6;
  }
  .cta-buttons{ 
    display:flex; 
    gap:1rem; 
    justify-content:center; 
    flex-wrap:wrap;
    margin-top:2rem;
  }
  .btn-cta-primary{ 
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    color:#fff; 
    border:none; 
    border-radius:.8rem; 
    padding:1rem 2.5rem; 
    font-size:1.1rem;
    font-weight:700;
    text-decoration:none;
    box-shadow:0 .5rem 1rem rgba(46, 204, 113, 0.3);
    transition: all 0.3s ease;
    display:inline-flex;
    align-items:center;
    gap:.5rem;
  }
  .btn-cta-primary:hover{ 
    transform: translateY(-3px);
    box-shadow:0 .8rem 1.5rem rgba(46, 204, 113, 0.4);
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
  }
  .btn-cta-secondary{ 
    background:rgba(255,255,255,0.1); 
    color:#e7e9ff; 
    border:2px solid rgba(255,255,255,0.2); 
    border-radius:.8rem; 
    padding:1rem 2rem; 
    font-size:1rem;
    font-weight:600;
    text-decoration:none;
    transition: all 0.3s ease;
    display:inline-flex;
    align-items:center;
    gap:.5rem;
  }
  .btn-cta-secondary:hover{ 
    background:rgba(255,255,255,0.15);
    border-color:rgba(255,255,255,0.3);
    transform: translateY(-2px);
  }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="page-hero">
        <h2>Keranjang Saya</h2>
      </div>

      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      @if(count($cart) > 0)
        <div class="card-dark">
          <div class="table-responsive">
            <table class="dark">
              <thead>
                <tr>
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
                    <form method="POST" action="{{ route('pelanggan.cart.update') }}">
                      @csrf
                      <input type="hidden" name="cart_id" value="{{ $item->id }}">
                      <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="input-qty">
                      <button type="submit" class="btn-blue ms-1">Update</button>
                    </form>
                  </td>
                  <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                  <td>
                    <form method="POST" action="{{ route('pelanggan.cart.remove') }}" onsubmit="return confirm('Hapus item ini?')">
                      @csrf
                      <input type="hidden" name="cart_id" value="{{ $item->id }}">
                      <button type="submit" class="btn-red">Hapus</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3">Total</th>
                  <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="mt-3 d-flex gap-2">
            <form method="POST" action="{{ route('pelanggan.cart.clear') }}" onsubmit="return confirm('Kosongkan keranjang?')">
              @csrf
              <button type="submit" class="btn-grey">Kosongkan Keranjang</button>
            </form>
            <a href="{{ route('pelanggan.rentals.create') }}" class="btn-green">Lanjut ke Penyewaan</a>
          </div>
        </div>
      @else
        <div class="empty-cart-container">
          <div class="empty-cart-icon">🛒</div>
          <h3 class="empty-cart-title">Keranjang Anda Masih Kosong!</h3>
          <p class="empty-cart-text">
            Yuk, mulai petualangan gaming Anda! 🎮<br>
            Pilih dari ratusan PlayStation, game seru, dan aksesoris keren yang siap menemani waktu luang Anda.
          </p>
          
          <div class="cta-buttons">
            <a href="{{ route('pelanggan.unitps.index') }}" class="btn-cta-primary">
              🎮 Sewa PlayStation Sekarang
            </a>
            <a href="{{ route('pelanggan.games.index') }}" class="btn-cta-secondary">
              🎯 Lihat Koleksi Game
            </a>
          </div>
        </div>
      @endif
    </main>
  </div>
</div>
@endsection

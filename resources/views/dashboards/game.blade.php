@extends('pelanggan.layout')

@section('pelanggan_content')

<style>
  /* Content-specific styles that complement the master layout */
  
  /* Hero Section */
  .dash-hero{ 
    text-align:center; 
    padding: 1rem 1rem 2rem; 
    animation: fadeInDown 0.8s ease-out;
  }
  
  @keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  .dash-hero h2{ 
    font-weight:800; 
    font-size: 2.5rem;
    background: linear-gradient(135deg, #60a5fa, #a78bfa, #34d399);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.75rem;
    animation: gradientShift 4s ease infinite;
    background-size: 200% 200%;
  }
  
  @keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
  }
  
  .dash-hero p{ 
    color: var(--text-muted); 
    margin-bottom:1.5rem; 
    font-size: 1.1rem;
  }
  
  /* Card Styling */
  .dash-card{ 
    background: rgba(30, 41, 59, 0.7); /* Matches master layout card-bg with opacity */
    backdrop-filter: blur(20px);
    border: 1px solid var(--card-border); 
    border-radius:1.25rem; 
    padding:1.25rem; 
    text-align:center; 
    height: 100%; 
    display: flex; 
    flex-direction: column;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }
  
  .dash-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #10b981);
    opacity: 0;
    transition: opacity 0.3s;
  }
  
  .dash-card:hover::before {
    opacity: 1;
  }
  
  .dash-card:hover{ 
    transform: translateY(-12px);
    box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15),
                0 0 0 1px rgba(59, 130, 246, 0.2);
    border-color: rgba(59, 130, 246, 0.4);
  }
  
  /* Card Frame */
  .dash-frame{ 
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); 
    border: 2px solid var(--card-border); 
    border-radius:1rem; 
    padding:0; 
    display:flex; 
    align-items:center; 
    justify-content:center; 
    height:220px; 
    overflow:hidden;
    position: relative;
    transition: transform 0.3s;
  }
  
  .dash-card:hover .dash-frame {
    transform: scale(1.05);
  }
  
  .card-content{ 
    flex: 1; 
    display: flex; 
    flex-direction: column; 
    justify-content: space-between; 
  }
  
  .dash-card img{ 
    width:100%; 
    height:100%; 
    object-fit:cover; 
    filter: drop-shadow(0 .75rem 1rem rgba(0,0,0,.4));
    transition: transform 0.4s;
  }
  
  .dash-card:hover img {
    transform: scale(1.1);
  }
  
  /* Price & Badge */
  .text-price{ 
    color: #10b981 !important; 
    font-weight: bold; 
    font-size: 1.25rem;
    text-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
  }
  
  .badge-stock {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-weight: 700;
    font-size: 0.9rem;
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
  }
  
  /* Buttons */
  .btn-cta{ 
    background: linear-gradient(135deg, #3b82f6, #8b5cf6); 
    color: #fff;
    border: none;
    padding: 0.875rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
  }
  
  .btn-cta::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }
  
  .btn-cta:active::after {
    width: 300px;
    height: 300px;
  }
  
  .btn-cta:hover{ 
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
    color: #fff;
  }
  
  /* Section Headers */
  .section-header {
    animation: fadeInUp 0.8s ease-out;
  }
  
  /* Card Grid Animation */
  .col {
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
  }
  
  .col:nth-child(1) { animation-delay: 0.1s; }
  .col:nth-child(2) { animation-delay: 0.2s; }
  .col:nth-child(3) { animation-delay: 0.3s; }
  .col:nth-child(4) { animation-delay: 0.4s; }
  .col:nth-child(5) { animation-delay: 0.5s; }
  .col:nth-child(6) { animation-delay: 0.6s; }
  .col:nth-child(7) { animation-delay: 0.7s; }
  .col:nth-child(8) { animation-delay: 0.8s; }
</style>

<div class="dash-hero">
  <h2>Sewa Game PlayStation</h2>
  <p>Temukan game favoritmu untuk pengalaman bermain terbaik!</p>
</div>

<!-- Games Section -->
<section class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4 section-header">
    <h3 class="text-white fw-bold m-0">
      <i class="bi bi-controller me-2 text-primary"></i>
      Game Tersedia
    </h3>
    <a href="{{ route('pelanggan.games.list') }}" class="btn btn-cta">
      <i class="bi bi-grid-3x3-gap me-2"></i>Lihat Semua
    </a>
  </div>
  
  @if($games->count() > 0)
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 px-2">
      @foreach($games as $game)
        <div class="col">
          <div class="dash-card">
            <div class="dash-frame">
              @if($game->gambar)
                <img src="{{ str_starts_with($game->gambar, 'http') ? $game->gambar : asset('storage/' . $game->gambar) }}" 
                     alt="{{ $game->judul }}" class="img-fluid">
              @else
                <img src="https://placehold.co/300x200/23284a/60a5fa?text={{ urlencode($game->judul) }}" alt="{{ $game->judul }}" class="img-fluid">
              @endif
            </div>
            <div class="card-content">
              <div class="p-2 flex-grow-1">
                <h5 class="mb-2 text-center text-white fw-bold">{{ $game->judul }}</h5>
                <div class="fw-bold text-price mb-2 text-center mt-2">
                  Rp {{ number_format($game->harga_per_hari, 0, ',', '.') }}/hari
                </div>
                <div class="text-center">
                  <span class="badge-stock">
                    <i class="bi bi-box-seam me-1"></i>Stok: {{ $game->stok }}
                  </span>
                </div>
              </div>
              <div class="pt-3 mt-auto">
                <a href="{{ route('pelanggan.rentals.create') }}?type=game&id={{ $game->id }}" class="btn btn-cta w-100">
                  <i class="bi bi-cart-plus me-2"></i>Sewa Sekarang
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="col-12 text-center py-5">
      <div class="text-muted">
        <i class="bi bi-inbox display-1 mb-3 d-block"></i>
        <p class="fs-5">Tidak ada game tersedia saat ini.</p>
      </div>
    </div>
  @endif
</section>

@endsection
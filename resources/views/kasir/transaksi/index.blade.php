@include('kasir.partials.nav')
<h1>Cari Transaksi Penyewaan</h1>
@if(session('status'))
    <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
        {{ session('status') }}
    </div>
@endif
<form method="GET" action="">
    <label>Nomor Transaksi (Rental ID):
        <input type="number" name="rental_id" value="{{ request('rental_id') }}" required>
    </label>
    <button type="submit">Cari</button>
</form>
@if($rental)
    <hr>
    <h2>Detail Transaksi #{{ $rental->id }}</h2>
    <p>Status: <b>{{ ucfirst($rental->status) }}</b></p>
    <a href="{{ route('kasir.transaksi.show', $rental) }}">Lihat Detail & Pengembalian</a>
@endif

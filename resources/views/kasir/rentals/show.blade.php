@include('kasir.partials.nav')
<h1>Detail Rental #{{ $rental->id }}</h1>

@if(session('status'))
    <div>{{ session('status') }}</div>
@endif

<p>Pelanggan: {{ optional($rental->customer)->name }}</p>
<p>Status: {{ $rental->status }}</p>
<p>Subtotal: {{ number_format($rental->subtotal,2) }}</p>
<p>Diskon: {{ number_format($rental->discount,2) }}</p>
<p>Total: {{ number_format($rental->total,2) }}</p>
<p>Dibayar: {{ number_format($rental->paid,2) }}</p>

<h3>Items</h3>
<ul>
@foreach($rental->items as $it)
    <li>{{ class_basename($it->rentable_type) }} #{{ $it->rentable_id }} x {{ $it->quantity }} = {{ number_format($it->total,2) }}</li>
@endforeach
</ul>

@if($rental->status==='ongoing')
<form method="POST" action="{{ route('kasir.rentals.return', $rental) }}" onsubmit="return confirm('Kembalikan rental?')">
    @csrf
    <button type="submit">Proses Pengembalian</button>
</form>
@endif

<h3>Pembayaran</h3>
<form method="POST" action="{{ route('kasir.rentals.payments.store', $rental) }}">
    @csrf
    <select name="method">
        <option value="cash">Cash</option>
        <option value="transfer">Transfer</option>
        <option value="ewallet">eWallet</option>
    </select>
    <input type="number" step="0.01" name="amount" placeholder="Jumlah" required>
    <input type="text" name="reference" placeholder="Referensi (opsional)">
    <button type="submit">Tambah Pembayaran</button>
    @error('amount')<div>{{ $message }}</div>@enderror
</form>


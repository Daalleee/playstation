@include('kasir.partials.nav')
<h1>Buat Rental</h1>
<form method="POST" action="{{ route('kasir.rentals.store') }}">
    @csrf
    <div>
        <label>Pelanggan (user_id)</label>
        <input type="number" name="user_id" value="{{ old('user_id') }}" required>
        @error('user_id')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Mulai</label>
        <input type="datetime-local" name="start_at" value="{{ old('start_at') }}" required>
        @error('start_at')<div>{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Jatuh Tempo</label>
        <input type="datetime-local" name="due_at" value="{{ old('due_at') }}">
        @error('due_at')<div>{{ $message }}</div>@enderror
    </div>

    <h3>Item</h3>
    <p>Masukkan item secara manual (sederhana). Format array:</p>
    <pre>items[0][type]=unit_ps|game|accessory, items[0][id]=ID, items[0][quantity]=1, items[0][price]=10000</pre>

    <div id="items">
        <div>
            <select name="items[0][type]">
                <option value="unit_ps">Unit PS</option>
                <option value="game">Game</option>
                <option value="accessory">Aksesoris</option>
            </select>
            <input type="number" name="items[0][id]" placeholder="ID" required>
            <input type="number" name="items[0][quantity]" placeholder="Qty" value="1" min="1" required>
            <input type="number" step="0.01" name="items[0][price]" placeholder="Harga" required>
        </div>
    </div>

    <div>
        <label>Diskon</label>
        <input type="number" step="0.01" name="discount" value="{{ old('discount',0) }}">
    </div>
    <div>
        <label>Dibayar Awal</label>
        <input type="number" step="0.01" name="paid" value="{{ old('paid',0) }}">
    </div>
    <button type="submit">Simpan</button>
</form>


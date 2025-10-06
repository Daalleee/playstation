<h1>Daftar Rental</h1>
<a href="{{ route('kasir.rentals.create') }}">Buat Rental</a>

@if(session('status'))
    <div>{{ session('status') }}</div>
@endif

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pelanggan</th>
            <th>Status</th>
            <th>Total</th>
            <th>Dibayar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rentals as $r)
        <tr>
            <td>#{{ $r->id }}</td>
            <td>{{ optional($r->customer)->name }}</td>
            <td>{{ $r->status }}</td>
            <td>{{ number_format($r->total, 2) }}</td>
            <td>{{ number_format($r->paid, 2) }}</td>
            <td><a href="{{ route('kasir.rentals.show', $r) }}">Detail</a></td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $rentals->links() }}


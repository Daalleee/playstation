@extends('layouts.app')
@section('content')
<h1>Status Produk</h1>

<h2>Unit PS</h2>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%;margin-bottom:2rem;">
    <thead>
        <tr><th>Nama</th><th>Merek</th><th>Model</th><th>Stok</th><th>Disewa</th><th>Sisa</th><th>Status Sewa</th></tr>
    </thead>
    <tbody>
        @foreach($unitps as $u)
        @php
            $jumlahDisewa = $u->rentalItems()->whereHas('rental', function($q){ $q->whereIn('status', ['ongoing','active']); })->sum('quantity');
            $stok = $u->stok ?? $u->stock;
            $sisa = $stok - $jumlahDisewa;
        @endphp
        <tr>
            <td>{{ $u->nama ?? $u->name }}</td>
            <td>{{ $u->merek ?? $u->brand }}</td>
            <td>{{ $u->model }}</td>
            <td>{{ $stok }}</td>
            <td>{{ $jumlahDisewa }}</td>
            <td>{{ $sisa }}</td>
            <td>
                @if($jumlahDisewa > 0)
                    <span style="color:orange;">Sebagian Disewa</span>
                @else
                    <span style="color:green;">Tersedia</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<h2>Games</h2>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%;margin-bottom:2rem;">
    <thead>
        <tr><th>Judul</th><th>Platform</th><th>Genre</th><th>Stok</th><th>Disewa</th><th>Sisa</th><th>Status Sewa</th></tr>
    </thead>
    <tbody>
        @foreach($games as $g)
        @php
            $jumlahDisewa = $g->rentalItems()->whereHas('rental', function($q){ $q->whereIn('status', ['ongoing','active']); })->sum('quantity');
            $stok = $g->stok ?? $g->stock;
            $sisa = $stok - $jumlahDisewa;
        @endphp
        <tr>
            <td>{{ $g->judul ?? $g->title }}</td>
            <td>{{ $g->platform }}</td>
            <td>{{ $g->genre }}</td>
            <td>{{ $stok }}</td>
            <td>{{ $jumlahDisewa }}</td>
            <td>{{ $sisa }}</td>
            <td>
                @if($jumlahDisewa > 0)
                    <span style="color:orange;">Sebagian Disewa</span>
                @else
                    <span style="color:green;">Tersedia</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<h2>Aksesoris</h2>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%;margin-bottom:2rem;">
    <thead>
        <tr><th>Nama</th><th>Jenis</th><th>Stok</th><th>Disewa</th><th>Sisa</th><th>Status Sewa</th></tr>
    </thead>
    <tbody>
        @foreach($accessories as $a)
        @php
            $jumlahDisewa = $a->rentalItems()->whereHas('rental', function($q){ $q->whereIn('status', ['ongoing','active']); })->sum('quantity');
            $stok = $a->stok ?? $a->stock;
            $sisa = $stok - $jumlahDisewa;
        @endphp
        <tr>
            <td>{{ $a->nama ?? $a->name }}</td>
            <td>{{ $a->jenis ?? $a->type }}</td>
            <td>{{ $stok }}</td>
            <td>{{ $jumlahDisewa }}</td>
            <td>{{ $sisa }}</td>
            <td>
                @if($jumlahDisewa > 0)
                    <span style="color:orange;">Sebagian Disewa</span>
                @else
                    <span style="color:green;">Tersedia</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

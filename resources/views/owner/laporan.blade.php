@extends('owner.layout')
@section('owner_content')
    <div class="card p-4 mb-4">
        <h1 class="mb-4">Laporan Transaksi</h1>
        <a href="{{ route('dashboard.pemilik') }}" class="btn btn-secondary mb-3" style="width: fit-content;">&larr; Kembali ke Beranda</a>
        
        <div class="mb-3">
            <strong>Filter berdasarkan rentang tanggal (Tgl Sewa):</strong>
        </div>
        
        <form method="GET" action="" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="dari" class="form-label">Dari Tanggal</label>
                    <input type="date" id="dari" name="dari" value="{{ request('dari') }}" class="form-control bg-dark text-light border-secondary">
                </div>
                <div class="col-md-3">
                    <label for="sampai" class="form-label">Sampai Tanggal</label>
                    <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}" class="form-control bg-dark text-light border-secondary">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('pemilik.laporan') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                    </a>
                </div>
            </div>
        </form>
        
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('pemilik.laporan.export', ['format' => 'xlsx', 'dari' => request('dari'), 'sampai' => request('sampai')]) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i>Download Excel
            </a>
            <a href="{{ route('pemilik.laporan.export', ['format' => 'csv', 'dari' => request('dari'), 'sampai' => request('sampai')]) }}" class="btn btn-secondary">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i>Download CSV
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="custom-table align-middle small">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Sewa</th>
                        <th>Tgl Kembali</th>
                        <th>Pelanggan</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($rentals) === 0)
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Tidak ada laporan.</td>
                    </tr>
                @endif
                @foreach ($rentals as $rental)
                    <tr>
                        <td class="text-nowrap">{{ $loop->iteration }}</td>
                        <td class="text-nowrap">{{ $rental->start_at ? $rental->start_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-nowrap">{{ $rental->due_at ? $rental->due_at->format('d/m/Y') : '-' }}</td>
                        <td class="text-nowrap">{{ $rental->customer ? $rental->customer->name : '-' }}</td>
                        <td class="text-nowrap">{{ $rental->customer ? $rental->customer->email : '-' }}</td>
                        <td class="text-nowrap">{{ $rental->customer ? $rental->customer->phone : '-' }}</td>
                        <td>{{ $rental->customer ? $rental->customer->address : '-' }}</td>
                        <td class="text-nowrap">Rp{{ number_format($rental->total,0,',','.') }}</td>
                        <td>
                            @if($rental->status == 'returned')
                                <span class="badge text-bg-success">Dikembalikan</span>
                            @elseif($rental->status == 'ongoing' || $rental->status == 'active')
                                <span class="badge text-bg-primary">Aktif</span>
                            @elseif($rental->status == 'pending')
                                <span class="badge text-bg-warning text-dark">Menunggu</span>
                            @elseif($rental->status == 'cancelled')
                                <span class="badge text-bg-danger">Dibatalkan</span>
                            @else
                                <span class="badge text-bg-secondary">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $rental->id }}">
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <style>
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            color: #eef0ff;
            background-color: #49497A;
        }
        .custom-table th {
            background-color: #2d3192;
            color: #dbe0ff;
            padding: 0.75rem;
            text-align: left;
            border: 1px solid rgba(255,255,255,.08);
        }
        .custom-table td {
            padding: 0.75rem;
            border: 1px solid rgba(255,255,255,.08);
            color: #eef0ff;
        }
        .custom-table tbody tr {
            background-color: #49497A !important;
        }
        .custom-table tbody tr:hover {
            background-color: #5a5a8a !important;
        }
        .custom-table .badge {
            font-size: 0.85em;
        }
        .form-control.bg-dark {
            background-color: #2d3192 !important;
            border: 1px solid #433D8B !important;
            color: #eef0ff !important;
        }
        .form-control.bg-dark:focus {
            background-color: #2d3192 !important;
            border: 1px solid #4f46e5 !important;
            color: #eef0ff !important;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25) !important;
        }
        .table-responsive {
            border-radius: 0.375rem;
            overflow-x: auto;
        }
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #433D8B;
            border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #2d3192;
        }
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    
    <!-- Modal untuk setiap transaksi -->
    @foreach ($rentals as $rental)
    <div class="modal fade" id="detailModal{{ $rental->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $rental->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $rental->id }}">Detail Transaksi #{{ $rental->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Transaksi</h6>
                            <table class="table table-sm table-dark">
                                <tr>
                                    <td>ID Transaksi</td>
                                    <td>{{ $rental->id }}</td>
                                </tr>
                                <tr>
                                    <td>Kode Transaksi</td>
                                    <td>{{ $rental->kode ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Sewa</td>
                                    <td>{{ $rental->start_at ? $rental->start_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Kembali</td>
                                    <td>{{ $rental->due_at ? $rental->due_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td>
                                        @if($rental->start_at && $rental->due_at)
                                            @php
                                                $start = \Carbon\Carbon::parse($rental->start_at);
                                                $end = \Carbon\Carbon::parse($rental->due_at);
                                                $diff = $start->diff($end);
                                                $totalHours = $diff->h + ($diff->days * 24);
                                                
                                                if ($totalHours >= 24) {
                                                    $days = floor($totalHours / 24);
                                                    $remainingHours = $totalHours % 24;
                                                    $dur = $days . ' Hari';
                                                    if ($remainingHours > 0) {
                                                        $dur .= ' ' . $remainingHours . ' Jam';
                                                    }
                                                } else {
                                                    $dur = $totalHours . ' Jam';
                                                }
                                            @endphp
                                            {{ $dur }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>Rp{{ number_format($rental->total ?? 0,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>
                                        @if($rental->status == 'returned')
                                            <span class="badge text-bg-success">Dikembalikan</span>
                                        @elseif($rental->status == 'ongoing' || $rental->status == 'active')
                                            <span class="badge text-bg-primary">Aktif</span>
                                        @elseif($rental->status == 'pending')
                                            <span class="badge text-bg-warning text-dark">Menunggu</span>
                                        @elseif($rental->status == 'cancelled')
                                            <span class="badge text-bg-danger">Dibatalkan</span>
                                        @else
                                            <span class="badge text-bg-secondary">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Pelanggan</h6>
                            <table class="table table-sm table-dark">
                                <tr>
                                    <td>Nama</td>
                                    <td>{{ $rental->customer ? $rental->customer->name : ($rental->nama_pelanggan ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $rental->customer ? $rental->customer->email : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>No. HP</td>
                                    <td>{{ $rental->customer ? $rental->customer->phone : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>{{ $rental->customer ? $rental->customer->address : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <h6 class="mt-3">Item Disewa</h6>
                    <div class="table-responsive">
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Nama/Judul</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rental->items as $item)
                                    <tr>
                                        <td>
                                          @if ($item->rentable_type === 'App\\Models\\UnitPS')
                                            Unit PS
                                          @elseif ($item->rentable_type === 'App\\Models\\Game')
                                            Game
                                          @elseif ($item->rentable_type === 'App\\Models\\Accessory')
                                            Aksesoris
                                          @else
                                            -
                                          @endif
                                        </td>
                                        <td>
                                          @if ($item->rentable)
                                            {{ $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? '-' }}
                                          @else
                                            -
                                          @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            @if($item->rentable)
                                                @if($item->rentable_type === 'App\\Models\\UnitPS')
                                                    Rp{{ number_format($item->rentable->harga_per_jam ?? 0,0,',','.') }}/jam
                                                @elseif($item->rentable_type === 'App\\Models\\Game')
                                                    Rp{{ number_format($item->rentable->harga_per_hari ?? 0,0,',','.') }}/hari
                                                @elseif($item->rentable_type === 'App\\Models\\Accessory')
                                                    Rp{{ number_format($item->rentable->harga_per_hari ?? 0,0,',','.') }}/hari
                                                @else
                                                    -
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>Rp{{ number_format($item->subtotal ?? 0,0,',','.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

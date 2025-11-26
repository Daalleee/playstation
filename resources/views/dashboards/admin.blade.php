@extends('admin.layout')

@section('admin_content')
<div class="container-fluid">
    <!-- Statistik Inventaris -->
    <div class="row g-4 mb-4">
        @foreach ($stats ?? [] as $row)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-white mb-3">{{ $row['name'] }}</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Stok</span>
                            <span class="fw-bold fs-5 text-white">{{ $row['total'] }}</span>
                        </div>
                        <div class="progress mb-3" style="height: 6px; background: rgba(255,255,255,0.1);">
                            @php
                                $percent = $row['total'] > 0 ? ($row['available'] / $row['total']) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2 rounded bg-success-subtle">
                                    <div class="small fw-bold">Tersedia</div>
                                    <div>{{ $row['available'] }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded bg-primary-subtle">
                                    <div class="small fw-bold">Disewa</div>
                                    <div>{{ $row['rented'] }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded bg-danger-subtle">
                                    <div class="small fw-bold">Rusak</div>
                                    <div>{{ $row['damaged'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Detail Unit PS -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white"><i class="bi bi-controller me-2 text-primary"></i>Detail Unit PS</h5>
            <a href="{{ route('admin.unitps.index') }}" class="btn btn-sm btn-outline-light rounded-pill">Kelola</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Model</th>
                        <th>Merek</th>
                        <th>Nomor Seri</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Kondisi</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unitps as $unit)
                        <tr>
                            <td class="fw-bold">{{ $unit['nama'] }}</td>
                            <td>{{ $unit['model'] }}</td>
                            <td>{{ $unit['merek'] }}</td>
                            <td><span class="badge bg-secondary-subtle font-monospace">{{ $unit['nomor_seri'] }}</span></td>
                            <td class="text-center">{{ $unit['stok'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-success-subtle">{{ $unit['kondisi_baik'] }} Baik</span>
                                @if($unit['kondisi_buruk'] > 0)
                                    <span class="badge bg-danger-subtle">{{ $unit['kondisi_buruk'] }} Rusak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($unit['disewa'] > 0)
                                    <span class="badge bg-primary-subtle">{{ $unit['disewa'] }} Disewa</span>
                                @else
                                    <span class="badge bg-secondary-subtle">Available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data unit PS</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Game -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white"><i class="bi bi-disc me-2 text-info"></i>Detail Game</h5>
            <a href="{{ route('admin.games.index') }}" class="btn btn-sm btn-outline-light rounded-pill">Kelola</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Platform</th>
                        <th>Genre</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Kondisi</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($games as $game)
                        <tr>
                            <td class="fw-bold">{{ $game['judul'] }}</td>
                            <td><span class="badge bg-secondary-subtle">{{ $game['platform'] }}</span></td>
                            <td>{{ $game['genre'] }}</td>
                            <td class="text-center">{{ $game['stok'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-success-subtle">{{ $game['kondisi_baik'] }} Baik</span>
                            </td>
                            <td class="text-center">
                                @if($game['disewa'] > 0)
                                    <span class="badge bg-primary-subtle">{{ $game['disewa'] }} Disewa</span>
                                @else
                                    <span class="badge bg-secondary-subtle">Available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada data game</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Aksesoris -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white"><i class="bi bi-headset me-2 text-warning"></i>Detail Aksesoris</h5>
            <a href="{{ route('admin.accessories.index') }}" class="btn btn-sm btn-outline-light rounded-pill">Kelola</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Kondisi</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accessories as $acc)
                        <tr>
                            <td class="fw-bold">{{ $acc['nama'] }}</td>
                            <td>{{ $acc['jenis'] }}</td>
                            <td class="text-center">{{ $acc['stok'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-success-subtle">{{ $acc['kondisi_baik'] }} Baik</span>
                            </td>
                            <td class="text-center">
                                @if($acc['disewa'] > 0)
                                    <span class="badge bg-primary-subtle">{{ $acc['disewa'] }} Disewa</span>
                                @else
                                    <span class="badge bg-secondary-subtle">Available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada data aksesoris</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

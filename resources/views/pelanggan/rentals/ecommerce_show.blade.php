@extends('layouts.ecommerce')

@section('title', 'Detail Penyewaan #' . $rental->id . ' - PlayStation Rental')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-receipt me-2"></i>Detail Penyewaan #{{ $rental->kode ?? $rental->id }}</h4>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    <div class="row g-4">
                        <!-- Rental Info -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-info-circle me-2"></i>Informasi Penyewaan</h5>
                                    
                                    <div class="mb-2">
                                        <strong>ID Rental:</strong>
                                        <span class="float-end">#{{ $rental->id }}</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Tanggal Sewa:</strong>
                                        <span class="float-end">{{ \Carbon\Carbon::parse($rental->start_at)->format('d M Y') }}</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Tanggal Kembali:</strong>
                                        <span class="float-end">{{ \Carbon\Carbon::parse($rental->due_at)->format('d M Y') }}</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Status:</strong>
                                        @php
                                            $statusBadge = match($rental->status) {
                                                'pending' => ['class' => 'warning', 'text' => 'Menunggu Pembayaran'],
                                                'sedang_disewa' => ['class' => 'success', 'text' => 'Sedang Disewa'],
                                                'menunggu_konfirmasi' => ['class' => 'info', 'text' => 'Menunggu Konfirmasi Kasir'],
                                                'selesai' => ['class' => 'success', 'text' => 'Selesai'],
                                                'cancelled' => ['class' => 'danger', 'text' => 'Dibatalkan'],
                                                default => ['class' => 'secondary', 'text' => ucfirst($rental->status)]
                                            };
                                        @endphp
                                        <span class="float-end">
                                            <span class="badge bg-{{ $statusBadge['class'] }}">{{ $statusBadge['text'] }}</span>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Total:</strong>
                                        <span class="float-end fw-bold">Rp {{ number_format($rental->total, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Dibayar:</strong>
                                        <span class="float-end">Rp {{ number_format($rental->paid ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong>Status Pembayaran:</strong>
                                        <span class="float-end">
                                            @if($rental->paid >= $rental->total)
                                                <span class="badge bg-success">✓ LUNAS</span>
                                            @elseif($rental->paid > 0)
                                                <span class="badge bg-warning">⚠ KURANG BAYAR</span>
                                            @else
                                                <span class="badge bg-danger">✗ BELUM LUNAS</span>
                                            @endif
                                        </span>
                                    </div>
                                    
                                    @if($rental->notes)
                                        <div class="mb-2">
                                            <strong>Catatan:</strong>
                                            <p class="text-muted mt-1">{{ $rental->notes }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="mb-0">
                                        <strong>Dibuat:</strong>
                                        <span class="float-end">{{ $rental->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rental Items -->
                        <div class="col-md-6">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-cart me-2"></i>Item yang Disewa</h5>
                                    
                                    @forelse($rental->items as $item)
                                        @php
                                            $itemName = 'Item Tidak Ditemukan';
                                            if($item->rentable) {
                                                $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';
                                            }
                                        @endphp
                                        <div class="border-bottom pb-3 mb-3">
                                            <h6 class="mb-1">{{ $itemName }}</h6>
                                            <p class="text-muted mb-1">
                                                <small><strong>Jenis:</strong> {{ class_basename($item->rentable_type) }}</small>
                                            </p>
                                            <p class="mb-1">
                                                <small><strong>Jumlah:</strong> {{ $item->quantity }}</small>
                                            </p>
                                            <p class="mb-0">
                                                <small><strong>Subtotal:</strong> Rp {{ number_format($item->total, 0, ',', '.') }}</small>
                                            </p>
                                        </div>
                                    @empty
                                        <p class="text-center text-muted">Tidak ada item</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payments -->
                    @if($rental->payments->count() > 0)
                        <div class="mt-4">
                            <h5><i class="bi bi-credit-card me-2"></i>Riwayat Pembayaran</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rental->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            <td>{{ ucfirst($payment->method ?? 'N/A') }}</td>
                                            <td>
                                                @php
                                                    $status = $payment->transaction_status ?? 'pending';
                                                @endphp
                                                @if(in_array($status, ['settlement', 'capture']))
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif($status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <a href="{{ route('pelanggan.rentals.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Riwayat
                        </a>

                        @if($rental->status === 'sedang_disewa')
                            <form method="POST" action="{{ route('pelanggan.rentals.return', $rental) }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan barang ini? Silakan pastikan barang dalam kondisi baik.')"
                                  class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-box-arrow-in-down me-1"></i>Kembalikan Barang
                                </button>
                            </form>
                        @endif

                        @if($rental->status === 'menunggu_konfirmasi')
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-1"></i>Pengembalian Anda sedang menunggu konfirmasi dari kasir.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
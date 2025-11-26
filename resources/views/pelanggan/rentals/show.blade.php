@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-receipt me-2 text-primary icon-hover"></i><span class="gradient-text">Detail Penyewaan #{{ $rental->id }}</span></h4>
                    <p class="mb-0 text-muted small">Informasi lengkap transaksi penyewaan Anda</p>
                </div>
                <div>
                    <a href="{{ route('pelanggan.rentals.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('status'))
        <div class="alert alert-success border-0 bg-success-subtle text-success mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4 d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif
    
    @if(session('warning'))
        <div class="alert alert-warning border-0 bg-warning-subtle text-warning mb-4 d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('warning') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Rental Information -->
        <div class="col-lg-6">
            <div class="card card-hover-lift h-100">
                <div class="card-body">
                    <h5 class="mb-4 fw-bold text-white"><i class="bi bi-info-circle me-2 text-info"></i>Informasi Penyewaan</h5>
                    
                    <div class="mb-3 pb-3 border-bottom border-secondary">
                        <div class="row">
                            <div class="col-5 text-muted">Kode Rental</div>
                            <div class="col-7 text-white fw-bold">{{ $rental->kode ?? '#'.$rental->id }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom border-secondary">
                        <div class="row">
                            <div class="col-5 text-muted"><i class="bi bi-calendar-event me-1"></i> Tanggal Sewa</div>
                            <div class="col-7 text-white">{{ \Carbon\Carbon::parse($rental->start_at)->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom border-secondary">
                        <div class="row">
                            <div class="col-5 text-muted"><i class="bi bi-calendar-x me-1"></i> Tanggal Kembali</div>
                            <div class="col-7 text-white">{{ \Carbon\Carbon::parse($rental->due_at)->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom border-secondary">
                        <div class="row">
                            <div class="col-5 text-muted"><i class="bi bi-hourglass me-1"></i> Durasi</div>
                            <div class="col-7 text-white">
                                {{ \Carbon\Carbon::parse($rental->start_at)->diffInDays(\Carbon\Carbon::parse($rental->due_at)) }} hari
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom border-secondary">
                        <div class="row">
                            <div class="col-5 text-muted"><i class="bi bi-tag me-1"></i> Status</div>
                            <div class="col-7">
                                @php
                                  $statusBadge = match($rental->status) {
                                    'pending' => ['class' => 'bg-warning-subtle', 'text' => 'Menunggu Pembayaran'],
                                    'sedang_disewa' => ['class' => 'bg-success-subtle', 'text' => 'Sedang Disewa'],
                                    'menunggu_konfirmasi' => ['class' => 'bg-info-subtle', 'text' => 'Menunggu Konfirmasi'],
                                    'selesai' => ['class' => 'bg-primary-subtle', 'text' => 'Selesai'],
                                    'cancelled' => ['class' => 'bg-danger-subtle', 'text' => 'Dibatalkan'],
                                    default => ['class' => 'bg-secondary-subtle', 'text' => ucfirst($rental->status)]
                                  };
                                @endphp
                                <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['text'] }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom border-secondary">
                        <div class="row">
                            <div class="col-5 text-muted"><i class="bi bi-cash me-1"></i> Total</div>
                            <div class="col-7 text-white fw-bold fs-5">Rp {{ number_format($rental->total, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom border-secondary">
                        <div class="row">
                            <div class="col-5 text-muted"><i class="bi bi-credit-card me-1"></i> Status Bayar</div>
                            <div class="col-7">
                                @if($rental->paid >= $rental->total)
                                    <span class="badge bg-success-subtle"><i class="bi bi-check-circle-fill me-1"></i> LUNAS</span>
                                @elseif($rental->paid > 0)
                                    <span class="badge bg-warning-subtle"><i class="bi bi-exclamation-circle-fill me-1"></i> KURANG BAYAR</span>
                                @else
                                    <span class="badge bg-danger-subtle"><i class="bi bi-x-circle-fill me-1"></i> BELUM LUNAS</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($rental->notes)
                    <div class="mb-3">
                        <div class="text-muted mb-2"><i class="bi bi-sticky me-1"></i> Catatan</div>
                        <div class="text-white bg-dark p-3 rounded border border-secondary">{{ $rental->notes }}</div>
                    </div>
                    @endif
                    
                    <!-- Actions -->
                    @if($rental->status === 'sedang_disewa')
                        <div class="mt-4">
                            <form method="POST" action="{{ route('pelanggan.rentals.return', $rental) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan barang ini? Silakan pastikan barang dalam kondisi baik.')">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100 fw-bold">
                                    <i class="bi bi-box-arrow-in-down me-2"></i> Kembalikan Barang
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    @if($rental->status === 'menunggu_konfirmasi')
                        <div class="alert alert-info border-0 bg-info-subtle text-info mt-4 mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i> Pengembalian Anda sedang menunggu konfirmasi dari kasir.
                        </div>
                    @endif

                    @if($rental->status === 'pending' && $rental->payments->count() > 0)
                        <div class="mt-4">
                            <button id="check-payment-btn" class="btn btn-primary w-100 fw-bold" data-order-id="{{ $rental->payments->first()->order_id }}">
                                <i class="bi bi-arrow-clockwise me-2"></i> Cek Status Pembayaran
                            </button>
                            <div class="text-muted small mt-2 text-center">
                                <i class="bi bi-info-circle me-1"></i> Klik jika Anda sudah membayar tapi status belum berubah.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Rental Items -->
        <div class="col-lg-6">
            <div class="card card-hover-lift h-100">
                <div class="card-body">
                    <h5 class="mb-4 fw-bold text-white"><i class="bi bi-box-seam me-2 text-success"></i>Item yang Disewa</h5>
                    
                    @forelse($rental->items as $item)
                        @php
                            $itemName = 'Item Tidak Ditemukan';
                            $itemImage = null;
                            $itemImageField = '';
                            
                            if($item->rentable) {
                                $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';
                                
                                // Get image field based on type
                                if (str_contains($item->rentable_type, 'UnitPS')) {
                                    $itemImageField = 'foto';
                                } else {
                                    $itemImageField = 'gambar';
                                }
                                
                                if(isset($item->rentable->$itemImageField)) {
                                    $itemImage = $item->rentable->$itemImageField;
                                }
                            }
                            
                            $itemType = class_basename($item->rentable_type);
                        @endphp
                        
                        <div class="d-flex align-items-start mb-3 pb-3 {{ !$loop->last ? 'border-bottom border-secondary' : '' }}">
                            <!-- Image -->
                            <div class="flex-shrink-0 me-3">
                                @if($itemImage)
                                    <img src="{{ str_starts_with($itemImage, 'http') ? $itemImage : asset('storage/' . $itemImage) }}" 
                                         alt="{{ $itemName }}" 
                                         class="rounded shadow-sm" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-dark rounded d-flex align-items-center justify-content-center text-muted border border-secondary" style="width: 80px; height: 80px;">
                                        <i class="bi bi-box-seam fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Details -->
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-white mb-2">{{ $itemName }}</h6>
                                <div class="text-muted small mb-1">
                                    <span class="badge bg-secondary-subtle">{{ $itemType }}</span>
                                </div>
                                <div class="text-muted small mb-1"><i class="bi bi-123 me-1"></i> Jumlah: {{ $item->quantity }}</div>
                                <div class="text-muted small mb-1"><i class="bi bi-tag me-1"></i> Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                <div class="text-white fw-bold mt-2"><i class="bi bi-cash me-1"></i> Subtotal: Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-box text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Tidak ada item</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    @if($rental->payments->count() > 0)
    <div class="card card-hover-lift mt-4">
        <div class="card-body">
            <h5 class="mb-4 fw-bold text-white"><i class="bi bi-clock-history me-2 text-warning"></i>Riwayat Pembayaran</h5>
            
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
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
                            <td class="text-white">{{ $payment->created_at->format('d M Y, H:i') }}</td>
                            <td class="text-white fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary-subtle">{{ ucfirst($payment->method ?? 'N/A') }}</span></td>
                            <td>
                                @php
                                    $status = $payment->transaction_status ?? 'pending';
                                @endphp
                                @if(in_array($status, ['settlement', 'capture']))
                                    <span class="badge bg-success-subtle"><i class="bi bi-check-circle me-1"></i> Lunas</span>
                                @elseif($status == 'pending')
                                    <span class="badge bg-warning-subtle"><i class="bi bi-hourglass-split me-1"></i> Menunggu</span>
                                @else
                                    <span class="badge bg-secondary-subtle">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkBtn = document.getElementById('check-payment-btn');
    if (checkBtn) {
        checkBtn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const originalText = this.innerHTML;
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memeriksa...';
            
            fetch(`/midtrans/status/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Status check:', data);
                    if (data.status && (data.status.transaction_status === 'settlement' || data.status.transaction_status === 'capture')) {
                        alert('Pembayaran berhasil dikonfirmasi! Halaman akan dimuat ulang.');
                        window.location.reload();
                    } else {
                        alert('Status pembayaran saat ini: ' + (data.status ? data.status.transaction_status : 'Belum tersedia'));
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memeriksa status.');
                    this.disabled = false;
                    this.innerHTML = originalText;
                });
        });
    }
});
</script>

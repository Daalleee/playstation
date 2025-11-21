@extends('layouts.ecommerce')

@section('title', 'Pembayaran Penyewaan - PlayStation Rental')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-credit-card-2-front me-2"></i>Pembayaran Penyewaan</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>Selesaikan pembayaran untuk melanjutkan penyewaan Anda
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="text-muted small">Order ID</div>
                                    <div class="fw-bold">{{ $orderId }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="text-muted small">Kode Rental</div>
                                    <div class="fw-bold">{{ $rental->kode }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <div class="text-muted small">Status</div>
                                    <div class="fw-bold text-warning">
                                        <i class="bi bi-clock-history"></i> Menunggu Pembayaran
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Detail Penyewaan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Tanggal Mulai:</strong></p>
                                    <p>{{ \Carbon\Carbon::parse($rental->start_at)->format('d M Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Tanggal Kembali:</strong></p>
                                    <p>{{ \Carbon\Carbon::parse($rental->due_at)->format('d M Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Durasi:</strong></p>
                                    <p>{{ \Carbon\Carbon::parse($rental->start_at)->diffInDays(\Carbon\Carbon::parse($rental->due_at)) }} Hari</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Catatan:</strong></p>
                                    <p>{{ $rental->notes ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            @foreach($rental->items as $item)
                                @php
                                    $itemName = 'Item';
                                    if($item->rentable) {
                                        $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Item';
                                    }
                                @endphp
                                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                    <div>
                                        <div>{{ $itemName }}</div>
                                        <small class="text-muted">Jumlah: x{{ $item->quantity }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div>Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="d-flex justify-content-between fw-bold fs-5 mt-3 pt-3 border-top">
                                <div>Total Pembayaran</div>
                                <div class="text-success">Rp {{ number_format($rental->total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Instruksi Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <ol class="mb-0">
                                <li>Klik tombol "Lanjutkan Pembayaran" di bawah ini</li>
                                <li>Pilih metode pembayaran yang Anda inginkan (Transfer Bank, E-Wallet, Kartu Kredit, dll)</li>
                                <li>Ikuti instruksi pembayaran yang muncul</li>
                                <li>Selesaikan pembayaran sebelum batas waktu yang ditentukan</li>
                                <li>Status penyewaan akan otomatis diperbarui setelah pembayaran berhasil</li>
                            </ol>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <button id="pay-button" class="btn btn-success btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Lanjutkan Pembayaran
                        </button>
                        
                        <div class="text-center">
                            <a href="{{ route('pelanggan.rentals.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Penyewaan
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-4 p-3 bg-light rounded">
                        <i class="bi bi-shield-check fs-4 text-success me-2"></i>
                        <span class="text-muted">Pembayaran aman dan terenkripsi dengan Midtrans</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
  const payButton = document.getElementById('pay-button');

  payButton.onclick = function () {
    // Disable button to prevent double click
    payButton.disabled = true;
    payButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses Pembayaran...';

    snap.pay('{{ $snapToken }}', {
      onSuccess: function(result){
        console.log('Payment success:', result);

        // Show success message in e-commerce style
        const overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9998;display:flex;align-items:center;justify-content:center;';
        
        const successMsg = document.createElement('div');
        successMsg.style.cssText = 'background:#2ecc71;color:#fff;padding:2rem 3rem;border-radius:1rem;text-align:center;box-shadow:0 1rem 3rem rgba(0,0,0,0.5);z-index:9999;';
        successMsg.innerHTML = '<i class="bi bi-check-circle fs-1" style="display:block;"></i><h3 class="mt-3 mb-2">Pembayaran Berhasil!</h3><p>Terima kasih atas pembayaran Anda.</p>';
        
        document.body.appendChild(overlay);
        document.body.appendChild(successMsg);

        setTimeout(function() {
          window.location.href = '{{ route("pelanggan.rentals.show", $rental) }}';
        }, 2000);
      },
      onPending: function(result){
        console.log('Payment pending:', result);

        // Show pending message in e-commerce style
        const overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9998;display:flex;align-items:center;justify-content:center;';
        
        const pendingMsg = document.createElement('div');
        pendingMsg.style.cssText = 'background:#f39c12;color:#fff;padding:2rem 3rem;border-radius:1rem;text-align:center;box-shadow:0 1rem 3rem rgba(0,0,0,0.5);z-index:9999;';
        pendingMsg.innerHTML = '<i class="bi bi-clock-history fs-1" style="display:block;"></i><h3 class="mt-3 mb-2">Pembayaran Sedang Diproses</h3><p>Silakan cek status pembayaran Anda.</p>';
        
        document.body.appendChild(overlay);
        document.body.appendChild(pendingMsg);

        setTimeout(function() {
          window.location.href = '{{ route("pelanggan.rentals.show", $rental) }}';
        }, 2000);
      },
      onError: function(result){
        console.error('Payment error:', result);

        // Show error message in e-commerce style
        const overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9998;display:flex;align-items:center;justify-content:center;';
        
        const errorMsg = document.createElement('div');
        errorMsg.style.cssText = 'background:#e74c3c;color:#fff;padding:2rem 3rem;border-radius:1rem;text-align:center;box-shadow:0 1rem 3rem rgba(0,0,0,0.5);z-index:9999;';
        errorMsg.innerHTML = '<i class="bi bi-x-circle fs-1" style="display:block;"></i><h3 class="mt-3 mb-2">Pembayaran Gagal</h3><p>Terjadi kesalahan. Silakan coba lagi.</p>';
        
        document.body.appendChild(overlay);
        document.body.appendChild(errorMsg);

        setTimeout(function() {
          document.body.removeChild(overlay);
          document.body.removeChild(errorMsg);
          payButton.disabled = false;
          payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Lanjutkan Pembayaran';
        }, 2000);
      },
      onClose: function(){
        console.log('Payment popup closed');
        payButton.disabled = false;
        payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Lanjutkan Pembayaran';
      }
    });
  };

  // Auto-trigger payment popup after 1.5 seconds
  // setTimeout(function() {
  //   payButton.click();
  // }, 1500);
</script>
@endsection
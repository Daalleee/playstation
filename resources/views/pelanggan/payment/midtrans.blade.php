@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
  .dash-dark{ background:#2b3156; color:#e7e9ff; min-height:100dvh; }
  .dash-layout{ display:flex; gap:1rem; height: 100vh; }
  .dash-sidebar{ flex:0 0 280px; background:#3a2a70; border-radius:1rem; padding:1.25rem 1rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); height: 100vh; overflow-y: auto; position: sticky; top: 0; }
  .dash-main{ flex:1; overflow-y: auto; padding: 1rem; }
  .page-hero{ text-align:center; padding:1rem; margin-bottom: 2rem; }
  .page-hero h2{ margin:0; font-weight:800; font-size: 2rem; }
  .page-hero p{ color:#cfd3ff; opacity:.9; margin-top: 0.5rem; }
  .card-dark{ background:#1f2446; border:none; border-radius:1rem; padding:2rem; box-shadow:0 1rem 2rem rgba(0,0,0,.25); margin-bottom: 1.5rem; }
  .order-info{ display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #2b3156; border-radius: 0.75rem; margin-bottom: 1.5rem; }
  .order-info .label{ color:#cfd3ff; opacity:.9; font-size: 0.875rem; }
  .order-info .value{ font-weight: 700; font-size: 1rem; color: #e7e9ff; }
  .order-summary{ background:#2b3156; border-radius:0.75rem; padding:1.5rem; margin-bottom: 1.5rem; }
  .order-summary h3{ margin:0 0 1rem 0; font-weight:700; font-size: 1.25rem; color: #e7e9ff; }
  .order-row{ display:flex; justify-content:space-between; padding:0.75rem 0; border-bottom:1px solid #2f3561; color:#cfd3ff; }
  .order-row:last-child{ border-bottom:none; }
  .order-row.total{ border-top:2px solid #2f3561; padding-top:1rem; margin-top:1rem; font-weight:700; font-size:1.25rem; color:#2ecc71; }
  .btn-pay{ background:#2ecc71; color:#0e1a2f; font-weight:800; border:none; border-radius:.75rem; padding:1rem 2rem; font-size:1.125rem; width:100%; cursor:pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
  .btn-pay:hover{ background:#28d66a; transform: translateY(-2px); box-shadow: 0 10px 25px rgba(46, 204, 113, 0.3); }
  .btn-pay:active{ background:#22c55e; transform: translateY(0); }
  .btn-pay:disabled{ opacity:0.6; cursor:not-allowed; }
  .btn-cancel{ background:#6c757d; color:#fff; font-weight:700; border:none; border-radius:.75rem; padding:0.75rem 1.5rem; font-size:1rem; cursor:pointer; transition: all 0.2s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
  .btn-cancel:hover{ background:#5a6268; transform: translateY(-2px); }
  .security-badge{ text-align:center; margin-top:1.5rem; padding: 1rem; background: #2b3156; border-radius: 0.75rem; }
  .security-badge i{ color:#2ecc71; margin-right:0.25rem; font-size: 1.25rem; }
  .security-badge span{ color:#cfd3ff; opacity:.9; font-size:0.875rem; }
  .payment-instructions{ background:#2b3156; border-radius:0.75rem; padding:1.5rem; margin-bottom: 1.5rem; }
  .payment-instructions h4{ margin:0 0 1rem 0; font-weight:600; font-size: 1rem; color: #e7e9ff; }
  .payment-instructions ul{ margin:0; padding-left: 1.5rem; }
  .payment-instructions li{ color:#cfd3ff; opacity:.9; margin-bottom: 0.5rem; line-height: 1.6; }
  .rental-details{ background:#2b3156; border-radius:0.75rem; padding:1.5rem; margin-bottom: 1.5rem; }
  .rental-details h4{ margin:0 0 1rem 0; font-weight:600; font-size: 1rem; color: #e7e9ff; }
  .rental-detail-row{ display: flex; justify-content: space-between; padding: 0.5rem 0; color:#cfd3ff; }
  .rental-detail-row .label{ opacity:.9; }
  .rental-detail-row .value{ font-weight: 600; color: #e7e9ff; }
  @media (max-width: 991.98px){ .dash-layout{ flex-direction:column; } .dash-sidebar{ flex:0 0 auto; position:static; height: auto; } .dash-main{ height: auto; } }
</style>

<div class="dash-dark p-3">
  <div class="dash-layout">
    @include('pelanggan.partials.sidebar')

    <main class="dash-main">
      <div class="page-hero">
        <h2><i class="bi bi-credit-card-2-front"></i> Pembayaran Penyewaan</h2>
        <p>Selesaikan pembayaran untuk melanjutkan penyewaan Anda</p>
      </div>

      <div class="card-dark" style="max-width: 800px; margin: 0 auto;">
        <div class="order-info">
          <div>
            <div class="label">Order ID</div>
            <div class="value">{{ $orderId }}</div>
          </div>
          <div>
            <div class="label">Kode Rental</div>
            <div class="value">{{ $rental->kode }}</div>
          </div>
          <div>
            <div class="label">Status</div>
            <div class="value" style="color: #f39c12;">
              <i class="bi bi-clock-history"></i> Menunggu Pembayaran
            </div>
          </div>
        </div>

        <div class="rental-details">
          <h4><i class="bi bi-calendar-check"></i> Detail Penyewaan</h4>
          <div class="rental-detail-row">
            <span class="label">Tanggal Mulai</span>
            <span class="value">{{ \Carbon\Carbon::parse($rental->start_at)->format('d M Y') }}</span>
          </div>
          <div class="rental-detail-row">
            <span class="label">Tanggal Kembali</span>
            <span class="value">{{ \Carbon\Carbon::parse($rental->due_at)->format('d M Y') }}</span>
          </div>
          <div class="rental-detail-row">
            <span class="label">Durasi</span>
            <span class="value">{{ \Carbon\Carbon::parse($rental->start_at)->diffInDays(\Carbon\Carbon::parse($rental->due_at)) }} Hari</span>
          </div>
        </div>

        <div class="order-summary">
          <h3><i class="bi bi-receipt"></i> Ringkasan Pesanan</h3>
          
          @foreach($rental->items as $item)
            @php
              $itemName = 'Item';
              if($item->rentable) {
                $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Item';
              }
            @endphp
            <div class="order-row">
              <span>{{ $itemName }} <span style="opacity: 0.7;">(x{{ $item->quantity }})</span></span>
              <span>Rp {{ number_format($item->total, 0, ',', '.') }}</span>
            </div>
          @endforeach
          
          <div class="order-row total">
            <span><i class="bi bi-cash-stack"></i> Total Pembayaran</span>
            <span>Rp {{ number_format($rental->total, 0, ',', '.') }}</span>
          </div>
        </div>

        <div class="payment-instructions">
          <h4><i class="bi bi-info-circle"></i> Instruksi Pembayaran</h4>
          <ul>
            <li>Klik tombol "Lanjutkan Pembayaran" di bawah ini</li>
            <li>Pilih metode pembayaran yang Anda inginkan (Transfer Bank, E-Wallet, Kartu Kredit, dll)</li>
            <li>Ikuti instruksi pembayaran yang muncul</li>
            <li>Selesaikan pembayaran sebelum batas waktu yang ditentukan</li>
            <li>Status penyewaan akan otomatis diperbarui setelah pembayaran berhasil</li>
          </ul>
        </div>

        <button id="pay-button" class="btn-pay">
          <i class="bi bi-credit-card"></i> Lanjutkan Pembayaran
        </button>

        <div style="text-align: center; margin-top: 1rem;">
          <a href="{{ route('pelanggan.rentals.index') }}" class="btn-cancel">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Penyewaan
          </a>
        </div>

        <div class="security-badge">
          <div>
            <i class="bi bi-shield-check-fill"></i>
            <span>Pembayaran aman dan terenkripsi dengan Midtrans</span>
          </div>
        </div>
      </div>
    </main>
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
        
        // Show success message
        const successMsg = document.createElement('div');
        successMsg.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#2ecc71;color:#fff;padding:2rem 3rem;border-radius:1rem;box-shadow:0 1rem 3rem rgba(0,0,0,0.5);z-index:9999;text-align:center;';
        successMsg.innerHTML = '<i class="bi bi-check-circle" style="font-size:3rem;"></i><h3 style="margin:1rem 0 0.5rem 0;">Pembayaran Berhasil!</h3><p style="margin:0;">Terima kasih atas pembayaran Anda.</p>';
        document.body.appendChild(successMsg);
        
        setTimeout(function() {
          window.location.href = '{{ route("pelanggan.rentals.show", $rental) }}';
        }, 2000);
      },
      onPending: function(result){
        console.log('Payment pending:', result);
        
        // Show pending message
        const pendingMsg = document.createElement('div');
        pendingMsg.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#f39c12;color:#fff;padding:2rem 3rem;border-radius:1rem;box-shadow:0 1rem 3rem rgba(0,0,0,0.5);z-index:9999;text-align:center;';
        pendingMsg.innerHTML = '<i class="bi bi-clock-history" style="font-size:3rem;"></i><h3 style="margin:1rem 0 0.5rem 0;">Pembayaran Sedang Diproses</h3><p style="margin:0;">Silakan cek status pembayaran Anda.</p>';
        document.body.appendChild(pendingMsg);
        
        setTimeout(function() {
          window.location.href = '{{ route("pelanggan.rentals.show", $rental) }}';
        }, 2000);
      },
      onError: function(result){
        console.error('Payment error:', result);
        
        // Show error message
        const errorMsg = document.createElement('div');
        errorMsg.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#e74c3c;color:#fff;padding:2rem 3rem;border-radius:1rem;box-shadow:0 1rem 3rem rgba(0,0,0,0.5);z-index:9999;text-align:center;';
        errorMsg.innerHTML = '<i class="bi bi-x-circle" style="font-size:3rem;"></i><h3 style="margin:1rem 0 0.5rem 0;">Pembayaran Gagal</h3><p style="margin:0;">Terjadi kesalahan. Silakan coba lagi.</p>';
        document.body.appendChild(errorMsg);
        
        setTimeout(function() {
          document.body.removeChild(errorMsg);
          payButton.disabled = false;
          payButton.innerHTML = '<i class="bi bi-credit-card"></i> Lanjutkan Pembayaran';
        }, 2000);
      },
      onClose: function(){
        console.log('Payment popup closed');
        payButton.disabled = false;
        payButton.innerHTML = '<i class="bi bi-credit-card"></i> Lanjutkan Pembayaran';
      }
    });
  };
  
  // Auto-trigger payment popup after 1.5 seconds
  setTimeout(function() {
    payButton.click();
  }, 1500);
</script>
@endsection

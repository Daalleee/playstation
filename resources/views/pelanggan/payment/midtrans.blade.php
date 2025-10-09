@extends('layouts.app')
@section('content')
<h1 class="h4 mb-3">Pembayaran Penyewaan #{{ $rental->id }}</h1>
<div class="card">
  <div class="card-body">
    <p>Total pembayaran: <strong>Rp {{ number_format($rental->total, 0, ',', '.') }}</strong></p>
    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
  </div>
</div>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
  document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}', {
      onSuccess: function(result){
        alert('Pembayaran berhasil!');
        window.location.href = '{{ route('pelanggan.rentals.show', $rental) }}';
      },
      onPending: function(result){
        alert('Pembayaran pending.');
        window.location.href = '{{ route('pelanggan.rentals.show', $rental) }}';
      },
      onError: function(result){
        alert('Terjadi kesalahan saat pembayaran');
      },
      onClose: function(){
        alert('Anda menutup popup tanpa menyelesaikan pembayaran');
      }
    });
  };
</script>
@endsection

@extends('layouts.tenant')

@section('title', 'Proses Pembayaran')
@section('page-title', 'Pembayaran Tagihan')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 p-6 mt-10">
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Ringkasan Tagihan</h2>
        <p class="text-xs text-gray-400 mt-1">Order ID: {{ $orderId }}</p>
    </div>

    <div class="space-y-4 border-b pb-4 mb-4">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Tipe Pembayaran:</span>
            <span class="font-semibold text-gray-800">{{ $transaksi->type }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Nama Penyewa:</span>
            <span class="font-semibold text-gray-800">{{ $transaksi->user->nama }}</span>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <span class="text-gray-600 font-medium">Total yang harus dibayar:</span>
        <span class="text-2xl font-black text-blue-600">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
    </div>

    <button id="pay-button" class="w-full py-3 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 cursor-pointer transition-all">
        BAYAR SEKARANG 
    </button>
</div>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        // 2. Panggil snap.pay dengan token yang dikirim dari controller
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                alert("Pembayaran sukses! Halaman akan diarahkan ulang.");
                window.location.href = "{{ route('tenant.dashboard') }}";
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda.");
                window.location.href = "{{ route('tenant.dashboard') }}";
            },
            onError: function(result){
                alert("Pembayaran gagal, silahkan coba lagi.");
            },
            onClose: function(){
                alert('Anda menutup popup sebelum menyelesaikan pembayaran.');
            }
        });
    });
</script>
@endsection
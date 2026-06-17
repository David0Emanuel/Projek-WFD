@extends('layouts.visitor')

@section('title', 'Selesaikan Pembayaran DP - KosInAja')

@section('content')
<div class="max-w-md mx-auto rounded-3xl border border-gray-100 bg-white p-6 shadow-sm shadow-gray-200 sm:p-8 mt-10">
    <div class="text-center mb-6">
        <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Langkah Terakhir</p>
        <h2 class="text-2xl font-bold text-gray-900 mt-1">Pembayaran DP</h2>
        <p class="text-xs text-gray-400 mt-2">Order ID: {{ $orderId }}</p>
    </div>

    <div class="space-y-4 border-b border-dashed border-gray-200 pb-6 mb-6">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Tipe Pembayaran:</span>
            <span class="font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded uppercase">{{ $transaksi->type }} Booking</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Kamar Pilihan:</span>
            <span class="font-bold text-gray-800">Kamar {{ $transaksi->kamar->nomor_kamar ?? '-' }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Nama Calon Penyewa:</span>
            <span class="font-bold text-gray-800">{{ $transaksi->user->nama ?? 'Nama Tidak Tersedia' }}</span>
        </div>
    </div>

    <div class="flex justify-between items-center mb-8">
        <span class="text-sm text-gray-600 font-bold uppercase">Total Pembayaran:</span>
        <span class="text-2xl font-black text-gray-900">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
    </div>

    <button id="pay-button" class="w-full rounded-2xl bg-blue-600 px-4 py-4 text-sm font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">
        BAYAR DP SEKARANG
    </button>
</div>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                alert("Pembayaran sukses! Selamat, Anda kini resmi menjadi Tenant.");
                window.location.href = "{{ route('home') }}";
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda.");
                window.location.href = "{{ route('home') }}";
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
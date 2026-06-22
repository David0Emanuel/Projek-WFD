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

<!-- client key yg emg dibuat untuk ekspos ke frontend -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        //fungsi window dipanggil,snaptoken dikirim dri controller 
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Sukses!',
                    text: 'Selamat, Anda kini resmi menjadi Tenant.',
                    confirmButtonColor: '#16a34a', // Warna Hijau
                    confirmButtonText: 'Lanjut'
                }).then((result) => {
                    // Pindah halaman SETELAH tombol ditekan
                    window.location.href = "{{ route('home') }}";
                });
            },
            onPending: function(result){
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran Anda.',
                    confirmButtonColor: '#2563eb', // Warna Biru
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Pindah halaman SETELAH tombol ditekan
                    window.location.href = "{{ route('home') }}";
                });
            },
            onError: function(result){
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan, silakan coba lagi.',
                    confirmButtonColor: '#dc2626', // Warna Merah
                    confirmButtonText: 'Tutup'
                });
            },
            onClose: function(){
                Swal.fire({
                    icon: 'warning',
                    title: 'Pembayaran Dibatalkan',
                    text: 'Anda menutup popup sebelum menyelesaikan pembayaran.',
                    confirmButtonColor: '#f59e0b', // Warna Kuning/Amber
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>
@endsection
@extends('layouts.tenant')

@section('title', 'Proses Pembayaran')
@section('page-title', 'Pembayaran Tagihan')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 p-6 mt-10">
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Ringkasan Tagihan</h2>
        <p class="text-xs text-gray-400 mt-1">Order ID: {{ $orderId }}</p>
    </div>

    <div class="space-y-4 border-b pb-4 mb-4 border-dashed border-gray-200">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Tipe Pembayaran:</span>
            <span class="font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded uppercase">{{ $transaksi->type }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Nama Penyewa:</span>
            <span class="font-semibold text-gray-800">{{ $transaksi->user->nama }}</span>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <span class="text-gray-600 font-medium text-sm uppercase">Total yang harus dibayar:</span>
        <span class="text-2xl font-black text-blue-600">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
    </div>

    <button id="pay-button" class="w-full py-4 bg-green-500 text-white font-bold rounded-2xl hover:bg-green-600 cursor-pointer transition-all hover:shadow-md">
        BAYAR SEKARANG 
    </button>
</div>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        // Panggil snap.pay dengan token yang dikirim dari controller
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Sukses!',
                    text: 'Tagihan Anda berhasil dibayar lunas.',
                    confirmButtonColor: '#16a34a', // Warna Hijau
                    confirmButtonText: 'Kembali ke Dashboard'
                }).then((result) => {
                    // Pindah halaman SETELAH tombol SweetAlert ditekan
                    window.location.href = "{{ route('tenant.dashboard') }}";
                });
            },
            onPending: function(result){
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan instruksi pembayaran Anda.',
                    confirmButtonColor: '#2563eb', // Warna Biru
                    confirmButtonText: 'Cek Dashboard'
                }).then((result) => {
                    window.location.href = "{{ route('tenant.dashboard') }}";
                });
            },
            onError: function(result){
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan pada sistem pembayaran, silakan coba lagi.',
                    confirmButtonColor: '#dc2626', // Warna Merah
                    confirmButtonText: 'Tutup'
                });
            },
            onClose: function(){
                Swal.fire({
                    icon: 'warning',
                    title: 'Pembayaran Dibatalkan',
                    text: 'Anda menutup layar sebelum menyelesaikan pembayaran.',
                    confirmButtonColor: '#f59e0b', // Warna Kuning
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>
@endsection
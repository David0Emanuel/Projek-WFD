@extends('layouts.tenant')

@section('title', 'Main Dashboard')
@section('page-title', 'Main Dashboard')

@section('content')

    <!-- Pengumuman dari Super Admin -->
    <section class="mb-6">
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-100 bg-blue-50 px-5 py-3">
                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                </svg>
                <h3 class="text-sm font-bold text-gray-700">Announcement dari Super Admin</h3>
            </div>

            <div class="p-5">
                <div class="space-y-4" id="announcement-list">
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="text-sm font-semibold text-gray-800">Jadwal Pemeliharaan Gedung</h4>
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">Info</span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Akan dilakukan pemeliharaan gedung pada tanggal 20-22 Juni 2025. Mohon kerjasamanya untuk menjaga kebersihan area sekitar kamar.
                        </p>
                        <p class="mt-2 text-xs text-gray-400">Diposting: 12 Juni 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Status Masa Sewa & Daftar Tagihan -->
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <!-- Card: Status Masa Sewa -->
        <div id="status-masa-sewa" class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-gray-100 bg-green-50 px-5 py-3">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-sm font-bold text-gray-700">Status Masa Sewa</h3>
            </div>

            <div class="flex flex-col items-center p-6">
                <!-- Angka sisa hari -->
                <div class="mb-4 flex h-32 w-32 flex-col items-center justify-center rounded-full border-4 border-green-400 bg-green-50">
                    <span class="text-4xl font-bold text-gray-800">18</span>
                    <span class="text-xs text-gray-500">Sisa Hari</span>
                </div>

                <!-- Tanggal jatuh tempo -->
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-center">
                    <p class="text-xs font-semibold uppercase text-green-600">Tanggal Jatuh Tempo</p>
                    <p class="mt-1 text-base font-bold text-green-700">18 Juni 2025</p>
                </div>
            </div>
        </div>

        <!-- Card: Daftar Tagihan -->
        <div id="daftar-tagihan" class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 bg-red-50 px-5 py-3">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                    <h3 class="text-sm font-bold text-gray-700">Daftar Tagihan</h3>
                </div>
                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-bold text-red-600">Belum Lunas</span>
            </div>

            <div class="p-5">
                <div class="space-y-3" id="tagihan-list">
                    <!-- Item: Biaya Sewa Bulanan -->
                    <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                        <span class="text-sm text-gray-700">Biaya Sewa Bulanan</span>
                        <span class="text-sm font-bold text-gray-800">Rp 99.999.999</span>
                    </div>

                    <!-- Item: Tagihan Listrik -->
                    <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                        <span class="text-sm text-gray-700">Tagihan Listrik</span>
                        <span class="text-sm font-bold text-gray-800">Rp 99.999.999</span>
                    </div>
                </div>

                <hr class="my-4 border-dashed border-gray-200">

                <!-- Total -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-600">Total Pembayaran</span>
                    <span class="text-lg font-bold text-gray-900">Rp 99.999.999</span>
                </div>

                <!-- Tombol Bayar -->
                <div class="mt-4">
                    <!-- KODE BARU (Tersambung ke Midtrans) -->
                    <!-- Catatan: Angka 1 di bawah ini adalah ID Transaksi dummy. Nanti ubah menjadi $tagihan->id jika sudah terkoneksi DB -->
                    <a href="{{ route('pembayaran.checkout', $transaksi?->id) }}" 
                        class="block text-center w-full rounded-lg bg-green-500 px-4 py-3 text-sm font-bold text-white hover:bg-green-600 transition-colors">
                        Bayar Via Payment Gateway
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

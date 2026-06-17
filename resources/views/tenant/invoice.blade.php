@extends('layouts.tenant')

@section('title', 'Invoice & Tagihan')
@section('page-title', 'Invoice & Tagihan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Kolom Kiri: Riwayat Transaksi -->
    <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex flex-col">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
            <h3 class="text-sm font-bold text-gray-700">Riwayat Transaksi</h3>
        </div>
        <div class="p-5 flex-1">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-gray-600 font-bold">
                            <th class="py-3 px-4">No Invoice</th>
                            <th class="py-3 px-4">Tipe Tagihan</th>
                            <th class="py-3 px-4">Tanggal Bayar</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150">
                        <tr onclick="openInvoiceReviewModal('INV/2026/01', '18/01/2026', 'Rp 99.999.999', '1050 kWh', '1250 kWh', '200 kWh')" class="hover:bg-gray-50 text-gray-700 cursor-pointer">
                            <td class="py-4 px-4 font-semibold">INV/2026/01</td>
                            <td class="py-4 px-4">Sewa + Listrik</td>
                            <td class="py-4 px-4 text-gray-500">18/01/2026</td>
                            <td class="py-4 px-4 font-bold">Rp 99.999.999</td>
                            <td class="py-4 px-4"><span class="px-2.5 py-0.5 rounded-full bg-green-100 text-green-700 font-bold text-[10px]">Lunas</span></td>
                        </tr>
                        <tr onclick="openInvoiceReviewModal('INV/2026/02', '18/02/2026', 'Rp 99.999.999', '1100 kWh', '1310 kWh', '210 kWh')" class="hover:bg-gray-50 text-gray-700 cursor-pointer">
                            <td class="py-4 px-4 font-semibold">INV/2026/02</td>
                            <td class="py-4 px-4">Sewa + Listrik</td>
                            <td class="py-4 px-4 text-gray-500">18/02/2026</td>
                            <td class="py-4 px-4 font-bold">Rp 99.999.999</td>
                            <td class="py-4 px-4"><span class="px-2.5 py-0.5 rounded-full bg-green-100 text-green-700 font-bold text-[10px]">Lunas</span></td>
                        </tr>
                        <tr onclick="openInvoiceReviewModal('INV/2026/03', '18/03/2026', 'Rp 99.999.999', '1200 kWh', '1400 kWh', '200 kWh')" class="hover:bg-gray-50 text-gray-700 cursor-pointer">
                            <td class="py-4 px-4 font-semibold">INV/2026/03</td>
                            <td class="py-4 px-4">Sewa + Listrik</td>
                            <td class="py-4 px-4 text-gray-500">18/03/2026</td>
                            <td class="py-4 px-4 font-bold">Rp 99.999.999</td>
                            <td class="py-4 px-4"><span class="px-2.5 py-0.5 rounded-full bg-green-100 text-green-700 font-bold text-[10px]">Lunas</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Tagihan Mendatang -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex flex-col">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
            <h3 class="text-sm font-bold text-gray-700">Tagihan Mendatang</h3>
            <p class="text-[10px] text-gray-400 font-bold mt-0.5">INV/2026/04</p>
        </div>
        <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
                <!-- Box Foto Meteran Listrik -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 mb-4 text-center">
                    <p class="text-xs font-bold text-gray-600 mb-2">Foto Meteran Listrik</p>
                    <div class="w-full h-44 rounded border border-gray-300 overflow-hidden bg-gray-250 flex items-center justify-center">
                        <img src="{{ asset('meteran.png') }}" alt="Meteran Listrik" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Detail Angka Meteran -->
                <div class="grid grid-cols-2 gap-4 text-center mb-4">
                    <div class="bg-gray-50 border border-gray-200 p-2.5 rounded-lg">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Meteran Awal</p>
                        <p class="text-sm font-bold text-gray-800">1250 kWh</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 p-2.5 rounded-lg">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Meteran Akhir</p>
                        <p class="text-sm font-bold text-gray-800">1450 kWh</p>
                    </div>
                </div>

                <!-- Info Perhitungan Pemakaian -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center mb-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Konsumsi Pemakaian</p>
                    <p class="text-xs text-gray-500 my-0.5">Selisih: 200 kWh x Rp 1.500/kWh</p>
                    <p class="text-base font-black text-gray-800">Rp 99.999.999</p>
                </div>
            </div>

            <!-- Tombol Bayar Tagihan Mendatang -->
            <div>
                <!-- KODE BARU (Tersambung ke Midtrans) -->
                <a href="{{ route('pembayaran.checkout', $tagihan->id) }}" 
                    class="block text-center w-full py-3 bg-green-500 hover:bg-green-600 border border-green-600 rounded-lg font-bold text-sm text-white transition-colors">
                    Bayar Via Payment Gateway
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@extends('layouts.admin')

@section('title', 'Tiket Komplain')
@section('page-title', 'Kelola Tiket Komplain Masuk')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    <!-- Card Komplain -->
    <div class="flex flex-col rounded-2xl border border-red-200 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 bg-red-50 px-6 py-3">
            <span class="text-sm font-bold text-red-800">Menunggu Penanganan</span>
            <span class="text-xs text-red-500 font-semibold">10 Menit yang lalu</span>
        </div>
        <div class="p-6 flex-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Lokasi Kendala</p>
            <h3 class="text-lg font-bold text-gray-900">Kamar 205 (Willy)</h3>
            
            <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm font-bold text-gray-800">AC Tidak Dingin / Bocor</p>
                <p class="mt-1 text-xs text-gray-600">Air menetes dari unit AC sejak semalam dan tidak dingin sama sekali.</p>
            </div>
        </div>
        <div class="border-t border-gray-100 bg-white p-6">
            <button class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700">Tunjuk Teknisi (Proses)</button>
        </div>
    </div>

    <!-- Card Komplain Sedang Diproses -->
    <div class="flex flex-col rounded-2xl border border-amber-200 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 bg-amber-50 px-6 py-3">
            <span class="text-sm font-bold text-amber-800">Sedang Dikerjakan Teknisi</span>
            <span class="text-xs text-amber-600 font-semibold">Sejak Kemarin</span>
        </div>
        <div class="p-6 flex-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Lokasi Kendala</p>
            <h3 class="text-lg font-bold text-gray-900">Kamar Mandi Luar Lt. 1</h3>
            
            <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm font-bold text-gray-800">Keran Air Patah</p>
                <p class="mt-1 text-xs text-gray-600">Keran wastafel patah sehingga air mengalir terus.</p>
            </div>
        </div>
        <div class="border-t border-gray-100 bg-white p-6">
            <button class="w-full rounded-xl bg-green-600 px-4 py-3 text-sm font-bold text-white hover:bg-green-700">Konfirmasi Selesai</button>
        </div>
    </div>

</div>
@endsection
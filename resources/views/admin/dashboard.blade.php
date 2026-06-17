@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Ringkasan Operasional')

@section('content')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Card Tingkat Okupansi -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Tingkat Okupansi</p>
        <p class="mt-2 text-3xl font-black text-blue-600">85%</p>
        <p class="mt-1 text-sm text-gray-500">17 dari 20 Kamar Terisi</p>
    </div>

    <!-- Card Menunggu Check-in -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Menunggu Check-in</p>
        <p class="mt-2 text-3xl font-black text-amber-500">2</p>
        <p class="mt-1 text-sm text-gray-500">Visitor telah membayar DP</p>
    </div>

    <!-- Card Tiket Komplain -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Tiket Komplain</p>
        <p class="mt-2 text-3xl font-black text-red-500">3</p>
        <p class="mt-1 text-sm text-gray-500">Menunggu penanganan</p>
    </div>

    <!-- Card Jadwal Survey -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Jadwal Survey Hari Ini</p>
        <p class="mt-2 text-3xl font-black text-green-600">4</p>
        <p class="mt-1 text-sm text-gray-500">Perlu didampingi admin</p>
    </div>
</div>
@endsection
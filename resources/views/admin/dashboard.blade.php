@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Ringkasan Operasional')

@section('content')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2">
    
    <div class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Tingkat Okupansi</p>
            <p class="mt-2 text-4xl font-black text-blue-600">{{ $occupancyRate ?? 0 }}%</p>
        </div>
        <p class="mt-3 text-sm font-medium text-gray-500">{{ $terisiCount ?? 0 }} dari {{ $totalKamar ?? 0 }} Kamar Terisi</p>
    </div>

    <a href="{{ route('admin.survey') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:border-amber-300 hover:shadow-md">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Menunggu Check-in</p>
            <p class="mt-2 text-4xl font-black text-amber-500 group-hover:text-amber-600">{{ $waitingCheckinCount ?? 0 }}</p>
        </div>
        <p class="mt-3 text-sm font-medium text-gray-500">Visitor telah membayar DP</p>
    </a>

    <a href="{{ route('admin.komplain') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:border-red-300 hover:shadow-md">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Tiket Komplain</p>
            <p class="mt-2 text-4xl font-black text-red-500 group-hover:text-red-600">{{ $pendingTiketCount ?? 0 }}</p>
        </div>
        <p class="mt-3 text-sm font-medium text-gray-500">Menunggu penanganan</p>
    </a>

    <a href="{{ route('admin.survey') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:border-green-300 hover:shadow-md">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Survey Hari Ini</p>
            <p class="mt-2 text-4xl font-black text-green-600 group-hover:text-green-700">{{ $todaySurveyCount ?? 0 }}</p>
        </div>
        <p class="mt-3 text-sm font-medium text-gray-500">Perlu didampingi admin</p>
    </a>

</div>
@endsection
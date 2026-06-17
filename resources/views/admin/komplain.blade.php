@extends('layouts.admin')

@section('title', 'Tiket Komplain')
@section('page-title', 'Kelola Tiket Komplain Masuk')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

    @forelse($komplains as $tiket)
    <div class="flex flex-col rounded-2xl border {{ strtolower($tiket->status) == 'pending' ? 'border-red-200 bg-white' : 'border-amber-200 bg-white' }} shadow-sm overflow-hidden transition-all hover:shadow-md">
        
        <div class="flex items-center justify-between border-b border-gray-100 {{ strtolower($tiket->status) == 'pending' ? 'bg-red-50' : 'bg-amber-50' }} px-6 py-3">
            <span class="text-sm font-bold {{ strtolower($tiket->status) == 'pending' ? 'text-red-800' : 'text-amber-800' }}">
                {{ strtolower($tiket->status) == 'pending' ? 'Menunggu Penanganan' : 'Sedang Dikerjakan' }}
            </span>
            <span class="text-xs font-semibold {{ strtolower($tiket->status) == 'pending' ? 'text-red-500' : 'text-amber-600' }}">
                {{ $tiket->created_at->diffForHumans() }}
            </span>
        </div>
        
        <div class="p-6 flex-1">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Lokasi Kendala</p>
            <h3 class="text-lg font-bold text-gray-900">Kamar {{ $tiket->kamar->nomor ?? 'Umum' }}</h3>
            
            <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm font-bold text-gray-800">{{ $tiket->judul ?? 'Kendala Fasilitas' }}</p>
                <p class="mt-1 text-xs text-gray-600">{{ $tiket->deskripsi ?? 'Tidak ada deskripsi keluhan yang diberikan.' }}</p>
            </div>
        </div>
        
        <div class="border-t border-gray-100 bg-white p-6">
            @if(strtolower($tiket->status) == 'pending')
                <button class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700">Tunjuk Teknisi (Proses)</button>
            @else
                <button class="w-full rounded-xl bg-green-600 px-4 py-3 text-sm font-bold text-white hover:bg-green-700">Konfirmasi Selesai</button>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm font-medium text-gray-500">
        Belum ada tiket komplain yang masuk saat ini.
    </div>
    @endforelse

</div>
@endsection
@extends('layouts.admin')

@section('title', 'Tiket Komplain')
@section('page-title', 'Kelola Tiket Komplain Masuk')

@section('content')
<div class="space-y-6">

    {{-- Alert Sukses (Muncul setelah tombol ditekan) --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 shadow-sm">
            <div class="flex items-center gap-3 text-green-800">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @forelse($komplains as $tiket)
        
        {{-- Kita simpan status dalam variabel agar kodenya lebih rapi --}}
        @php
            $status = strtolower($tiket->status);
        @endphp
        
        <div class="flex flex-col rounded-2xl border {{ $status == 'pending' ? 'border-red-200 bg-white' : ($status == 'proses' ? 'border-amber-200 bg-white' : 'border-green-200 bg-green-50') }} shadow-sm overflow-hidden transition-all hover:shadow-md">
            
            <div class="flex items-center justify-between border-b border-gray-100 {{ $status == 'pending' ? 'bg-red-50' : ($status == 'proses' ? 'bg-amber-50' : 'bg-green-100') }} px-6 py-3">
                <span class="text-sm font-bold {{ $status == 'pending' ? 'text-red-800' : ($status == 'proses' ? 'text-amber-800' : 'text-green-800') }}">
                    {{ $status == 'pending' ? 'Menunggu Penanganan' : ($status == 'proses' ? 'Sedang Dikerjakan' : 'Selesai / Ditutup') }}
                </span>
                <span class="text-xs font-semibold {{ $status == 'pending' ? 'text-red-500' : ($status == 'proses' ? 'text-amber-600' : 'text-green-600') }}">
                    {{ $tiket->created_at->diffForHumans() }}
                </span>
            </div>
            
            <div class="p-6 flex-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Lokasi Kendala</p>
                <h3 class="text-lg font-bold text-gray-900">Kamar {{ $tiket->kamar->nomor ?? 'Umum' }}</h3>
                
                <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="text-sm font-bold text-gray-800">{{ $tiket->judul ?? 'Kendala Fasilitas' }}</p>
                    <p class="mt-1 text-xs text-gray-600">{{ $tiket->deskripsi ?? 'Tidak ada deskripsi keluhan yang diberikan.' }}</p>
                </div>
            </div>
            
            <div class="border-t border-gray-100 bg-white p-6">
                {{-- Kondisi 1: Tombol Tunjuk Teknisi --}}
                @if($status == 'pending')
                    <form action="{{ route('admin.komplain.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tiket_id" value="{{ $tiket->id }}">
                        <input type="hidden" name="status_baru" value="Proses">
                        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-blue-700">
                            Tunjuk Teknisi (Proses)
                        </button>
                    </form>
                    
                {{-- Kondisi 2: Tombol Konfirmasi Selesai --}}
                @elseif($status == 'proses')
                    <form action="{{ route('admin.komplain.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tiket_id" value="{{ $tiket->id }}">
                        <input type="hidden" name="status_baru" value="Selesai">
                        <button type="submit" class="w-full rounded-xl bg-green-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-green-700">
                            Konfirmasi Selesai
                        </button>
                    </form>
                    
                {{-- Kondisi 3: Tiket Sudah Selesai (Tombol Mati) --}}
                @else
                    <button type="button" disabled class="w-full rounded-xl bg-gray-100 px-4 py-3 text-sm font-bold text-gray-400 cursor-not-allowed">
                        Pekerjaan Telah Selesai
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm font-medium text-gray-500">
            Belum ada tiket komplain yang masuk saat ini.
        </div>
        @endforelse
    </div>
</div>
@endsection
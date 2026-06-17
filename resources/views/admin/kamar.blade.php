@extends('layouts.admin')

@section('title', 'Manajemen Kamar')
@section('page-title', 'Kamar & Meteran')

@section('content')
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    
    <!-- Looping Data Kamar Dummy -->
    @for ($i = 101; $i <= 106; $i++)
    <div class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900">Kamar {{ $i }}</h3>
            @if ($i % 3 == 0)
                <span class="rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-red-600">Kosong</span>
            @else
                <span class="rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-green-700">Terisi</span>
            @endif
        </div>
        
        <div class="mt-4 space-y-3">
            @if ($i % 3 != 0)
                <div>
                    <p class="text-[10px] font-bold uppercase text-gray-500">Penghuni</p>
                    <p class="text-sm font-semibold text-gray-800">Budi Santoso</p>
                </div>
                <button class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-blue-700">
                    Input Meteran Listrik
                </button>
            @else
                <div>
                    <p class="text-[10px] font-bold uppercase text-gray-500">Status</p>
                    <p class="text-sm font-semibold text-gray-800">Siap Disewakan</p>
                </div>
                <button class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-100">
                    Ubah Status ke Maintenance
                </button>
            @endif
        </div>
    </div>
    @endfor

</div>
@endsection
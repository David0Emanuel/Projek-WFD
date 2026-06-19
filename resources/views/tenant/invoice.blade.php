@extends('layouts.tenant')

@section('title', 'Invoice & Tagihan')
@section('page-title', 'Invoice & Tagihan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

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
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150">
                        
                        {{-- LOOPING DATA TRANSAKSI DARI DATABASE --}}
                        @forelse($riwayat_transaksi as $riwayat)
                        <tr class="hover:bg-gray-50 text-gray-700">
                            {{-- Generate Nomor Invoice Otomatis --}}
                            <td class="py-4 px-4 font-semibold">INV/{{ $riwayat->created_at->format('Y/m') }}/{{ str_pad($riwayat->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-4 px-4">{{ $riwayat->type }}</td>
                            <td class="py-4 px-4 text-gray-500">{{ $riwayat->created_at->format('d/m/Y') }}</td>
                            <td class="py-4 px-4 font-bold">Rp {{ number_format($riwayat->total, 0, ',', '.') }}</td>
                            <td class="py-4 px-4">
                                @if(strtolower($riwayat->status_transaksi) === 'paid' || strtolower($riwayat->status_transaksi) === 'settlement')
                                    <span class="px-2.5 py-0.5 rounded-full bg-green-100 text-green-700 font-bold text-[10px]">Lunas</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-red-100 text-red-700 font-bold text-[10px]">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">Belum ada riwayat transaksi.</td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex flex-col">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
            <h3 class="text-sm font-bold text-gray-700">Tagihan Aktif</h3>
            @if($tagihan_aktif)
                <p class="text-[10px] text-gray-400 font-bold mt-0.5">INV/{{ $tagihan_aktif->created_at->format('Y/m') }}/{{ str_pad($tagihan_aktif->id, 3, '0', STR_PAD_LEFT) }}</p>
            @endif
        </div>
        
        <div class="p-5 flex-1 flex flex-col justify-between">
            @if($tagihan_aktif)
                <div>
                    @if($tagihan_aktif->foto_meteran)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 mb-4 text-center">
                        <p class="text-xs font-bold text-gray-600 mb-2">Foto Bukti Meteran</p>
                        <div class="w-full h-44 rounded border border-gray-300 overflow-hidden bg-gray-250 flex items-center justify-center">
                            {{-- Tampilkan foto meteran asli dari storage --}}
                            <img src="{{ asset('storage/' . $tagihan_aktif->foto_meteran) }}" alt="Meteran Listrik" class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endif

                    <div class="bg-gray-50 border border-gray-200 p-2.5 rounded-lg text-center mb-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Angka Meteran Listrik Saat Ini</p>
                        <p class="text-sm font-bold text-gray-800">{{ $tagihan_aktif->angka_meteran ?? '0' }} kWh</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center mb-4">
                        <p class="text-[10px] font-bold text-blue-400 uppercase">Total Tagihan (Sewa + Listrik)</p>
                        <p class="text-2xl font-black text-blue-800 mt-1">Rp {{ number_format($tagihan_aktif->total, 0, ',', '.') }}</p>
                        <p class="text-xs text-blue-500 mt-1">Jatuh Tempo: {{ \Carbon\Carbon::parse($tagihan_aktif->expired_at)->format('d M Y') }}</p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('pembayaran.checkout', $tagihan_aktif->id) }}" 
                        class="block text-center w-full py-3 bg-green-500 hover:bg-green-600 border border-green-600 rounded-lg font-bold text-sm text-white transition-colors">
                        Bayar Sekarang
                    </a>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center">
                    <div class="rounded-full bg-green-100 p-3 mb-3 text-green-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-800">Hore! Tidak Ada Tagihan</p>
                    <p class="text-xs text-gray-500 mt-1">Semua tagihan Anda sudah lunas terbayar.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
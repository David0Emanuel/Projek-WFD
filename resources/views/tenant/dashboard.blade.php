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
                    {{-- LOOPING DATA PENGUMUMAN DARI DATABASE --}}
                    @forelse($pengumumans as $pengumuman)
                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-sm font-semibold text-gray-800">{{ $pengumuman->judul }}</h4>
                                @if(is_null($pengumuman->kos_id))
                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-medium text-blue-700">Global</span>
                                @else
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-medium text-green-700">Cabang Anda</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">
                                {!! nl2br(e($pengumuman->isi)) !!}
                            </p>
                            <p class="mt-2 text-xs text-gray-400">Diposting: {{ $pengumuman->created_at->format('d M Y') }}</p>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">Belum ada pengumuman saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Status Masa Sewa & Daftar Tagihan -->
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <!-- Card: Status Masa Sewa -->
        <div class="flex flex-col items-center p-6">
                @php
                    $user = auth()->user();
                    // Cek apakah admin sudah menginput tanggal selesai sewa
                    $tanggalSelesai = $user->tanggal_selesaiSewa ? \Carbon\Carbon::parse($user->tanggal_selesaiSewa) : null;
                    
                    // Hitung selisih hari dari hari ini ke tanggal selesai
                    $sisaHari = $tanggalSelesai ? (int) round(now()->diffInDays($tanggalSelesai, false)) : 0;
                    
                    // Tentukan warna (Merah jika sisa hari <= 5 atau sudah lewat/minus)
                    $isWarning = $sisaHari <= 5;
                @endphp

                @if($tanggalSelesai)
                    <div class="mb-4 flex h-32 w-32 flex-col items-center justify-center rounded-full border-4 {{ $isWarning ? 'border-red-400 bg-red-50' : 'border-green-400 bg-green-50' }}">
                        <span class="text-4xl font-bold {{ $isWarning ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $sisaHari < 0 ? 0 : $sisaHari }}
                        </span>
                        <span class="text-xs {{ $isWarning ? 'text-red-500' : 'text-gray-500' }}">Sisa Hari</span>
                    </div>

                    <div class="rounded-lg border {{ $isWarning ? 'border-red-200 bg-red-50' : 'border-green-200 bg-green-50' }} px-4 py-2 text-center">
                        <p class="text-[10px] font-semibold uppercase {{ $isWarning ? 'text-red-600' : 'text-green-600' }}">Tanggal Jatuh Tempo</p>
                        <p class="mt-1 text-base font-bold {{ $isWarning ? 'text-red-700' : 'text-green-700' }}">
                            {{ $tanggalSelesai->translatedFormat('d F Y') }}
                        </p>
                        @if($sisaHari < 0)
                            <p class="text-[10px] text-red-500 mt-1 font-bold">Masa Sewa Telah Habis!</p>
                        @endif
                    </div>
                @else
                    <div class="mb-4 flex h-32 w-32 flex-col items-center justify-center rounded-full border-4 border-gray-300 bg-gray-50">
                        <span class="text-4xl font-bold text-gray-400">-</span>
                        <span class="text-xs text-gray-500">Sisa Hari</span>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-center">
                        <p class="text-[10px] font-semibold uppercase text-gray-500">Tanggal Jatuh Tempo</p>
                        <p class="mt-1 text-sm font-bold text-gray-600">Belum Ditentukan</p>
                    </div>
                @endif
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
                @if($transaksi)
                    <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-bold text-red-600">Belum Lunas</span>
                @else
                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-bold text-green-700">Semua Lunas</span>
                @endif
            </div>

            <div class="p-5">
                
                {{-- CEK APAKAH ADA TAGIHAN --}}
                @if($transaksi)
                    <div class="space-y-3" id="tagihan-list">
                        <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                                <span class="text-sm text-gray-700">Biaya Sewa (Kamar {{ $transaksi->kamar->nomor ?? '' }})</span>
                                <span class="text-sm font-bold text-gray-800">
                                    Rp {{ number_format($transaksi->kamar->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                                <span class="text-sm text-gray-700">Tagihan Listrik</span>
                                <span class="text-sm font-bold text-gray-800">
                                    {{-- Listrik = Total Tagihan dikurangi Harga Sewa Kamar --}}
                                    Rp {{ number_format($transaksi->total - ($transaksi->kamar->harga ?? 0), 0, ',', '.') }}
                                </span>
                            </div>
                @else
                    {{-- JIKA TIDAK ADA TAGIHAN --}}
                    <div class="py-8 text-center">
                        <span class="text-gray-400 text-sm font-medium">Hore! Tidak ada tagihan bulan ini.</span>
                    </div>
                @endif

                <div class="mt-4">
                    @if($transaksi)
                        <a href="{{ route('pembayaran.checkout', $transaksi->id) }}" 
                            class="block text-center w-full rounded-lg bg-green-500 px-4 py-3 text-sm font-bold text-white hover:bg-green-600 transition-colors">
                            Bayar Via Payment Gateway
                        </a>
                    @else
                        <button disabled 
                            class="block text-center w-full rounded-lg bg-gray-300 px-4 py-3 text-sm font-bold text-gray-500 cursor-not-allowed">
                            Belum Ada Tagihan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection

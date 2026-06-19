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
    <section class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_1.5fr]">

        <div class="flex flex-col items-center p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
            @php
                // Logika Penentuan Sisa Hari Tenggat 1 Bulan
                $mulaiSewa = $user->tanggal_mulaiSewa ? \Carbon\Carbon::parse($user->tanggal_mulaiSewa)->startOfDay() : null;
                
                if ($mulaiSewa) {
                    $hariIni = now()->startOfDay();
                    $tglSewa = $mulaiSewa->day;
                    
                    // Tentukan bulan batas akhir (Jika hari ini sudah lewat tgl sewa, batasnya adalah tgl tsb bulan depan)
                    if ($hariIni->day >= $tglSewa) {
                        $batasBayar = \Carbon\Carbon::createFromDate($hariIni->year, $hariIni->month, $tglSewa)->addMonthNoOverflow()->startOfDay();
                    } else {
                        $batasBayar = \Carbon\Carbon::createFromDate($hariIni->year, $hariIni->month, $tglSewa)->startOfDay();
                    }
                    
                    // Hitung Sisa Hari (Rentang 30 hari ke 0)
                    $sisaHari = (int) $hariIni->diffInDays($batasBayar, false);
                    $isWarning = $sisaHari <= 7; // Peringatan jika sisa waktu tinggal seminggu
                }
            @endphp

            @if($mulaiSewa)
                <div class="mb-4 flex h-36 w-36 flex-col items-center justify-center rounded-full border-4 {{ $isWarning ? 'border-amber-400 bg-amber-50' : 'border-blue-400 bg-blue-50' }}">
                    <span class="text-5xl font-black {{ $isWarning ? 'text-amber-600' : 'text-gray-800' }}">
                        {{ $sisaHari }}
                    </span>
                    <span class="text-xs font-bold uppercase tracking-wider {{ $isWarning ? 'text-amber-600' : 'text-gray-500' }}">Sisa Hari</span>
                </div>

                <div class="w-full rounded-lg border {{ $isWarning ? 'border-amber-200 bg-amber-50' : 'border-gray-100 bg-gray-50' }} px-4 py-3 text-center">
                    <p class="text-[10px] font-bold uppercase tracking-wider {{ $isWarning ? 'text-amber-600' : 'text-gray-500' }}">Batas Pembayaran Sewa</p>
                    <p class="mt-1 text-base font-black {{ $isWarning ? 'text-amber-700' : 'text-gray-800' }}">
                        {{ $batasBayar->translatedFormat('d F Y') }}
                    </p>
                    @if($isWarning)
                        <p class="text-[10px] text-amber-700 mt-2 font-bold bg-amber-100 py-1 rounded">Segera lunasi tagihan bulan ini!</p>
                    @else
                        <p class="text-[10px] text-gray-500 mt-2 font-medium">Periode sewa aman (Siklus 1 Bulan)</p>
                    @endif
                </div>
            @else
                <div class="mb-4 flex h-36 w-36 flex-col items-center justify-center rounded-full border-4 border-gray-300 bg-gray-50">
                    <span class="text-4xl font-bold text-gray-400">-</span>
                </div>
                <div class="w-full text-center">
                    <p class="text-sm font-bold text-gray-600">Siklus Sewa Belum Aktif</p>
                </div>
            @endif
        </div>

        <div id="daftar-tagihan" class="rounded-lg border border-gray-200 bg-white shadow-sm flex flex-col">
            <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-5 py-4">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                    <h3 class="text-sm font-bold text-gray-800">Tagihan Belum Dibayar</h3>
                </div>
                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-700">{{ $tagihans->count() }} Tagihan</span>
            </div>

            <div class="p-5 flex-1 overflow-y-auto max-h-[350px]">
                @if($tagihans->count() > 0)
                    <div class="space-y-4">
                        @foreach($tagihans as $tagihan)
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm transition-all hover:border-blue-200">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="text-sm font-bold text-gray-900">{{ $tagihan->type }}</h4>
                                        @if($tagihan->type == 'Sewa Bulanan')
                                            <span class="rounded bg-indigo-100 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-indigo-700">Bulanan</span>
                                        @else
                                            <span class="rounded bg-amber-100 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-amber-700">Fleksibel</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">Dikeluarkan: {{ $tagihan->created_at->format('d M Y') }}</p>
                                    @if($tagihan->angka_meteran)
                                        <p class="text-xs font-semibold text-gray-700 mt-1">Meteran: {{ $tagihan->angka_meteran }} kWh</p>
                                    @endif
                                </div>

                                <div class="flex w-full sm:w-auto items-center sm:flex-col gap-3 sm:gap-2">
                                    <p class="flex-1 sm:flex-none text-lg font-black text-gray-900">Rp {{ number_format($tagihan->total, 0, ',', '.') }}</p>
                                    <a href="{{ route('pembayaran.checkout', $tagihan->id) }}" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-bold text-white transition-colors hover:bg-blue-700 text-center">
                                        Bayar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex h-full flex-col items-center justify-center text-center py-10">
                        <div class="mb-3 rounded-full bg-green-100 p-3">
                            <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800">Semua Tagihan Lunas!</h4>
                        <p class="text-xs text-gray-500 mt-1">Kamu tidak memiliki tagihan sewa atau listrik bulan ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

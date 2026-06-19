@extends('layouts.tenant')

@section('title', 'Main Dashboard')
@section('page-title', 'Main Dashboard')

@section('content')
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200 flex items-center gap-2 shadow-sm">
            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-200 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span class="font-bold">Gagal menyimpan data:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                            <p class="text-sm text-gray-600">{!! nl2br(e($pengumuman->isi)) !!}</p>
                            <p class="mt-2 text-xs text-gray-400">Diposting: {{ $pengumuman->created_at->format('d M Y') }}</p>
                        </div>
                    @empty
                        <div class="text-center py-4"><p class="text-sm text-gray-500">Belum ada pengumuman saat ini.</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_1.5fr]">
        <div class="flex flex-col gap-6">
            <div class="flex flex-col items-center p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                @php
                    $mulaiSewa = $user->tanggal_mulaiSewa ? \Carbon\Carbon::parse($user->tanggal_mulaiSewa)->startOfDay() : null;
                    if ($mulaiSewa) {
                        $hariIni = now()->startOfDay();
                        $tglSewa = $mulaiSewa->day;
                        if ($hariIni->day >= $tglSewa) {
                            $batasBayar = \Carbon\Carbon::createFromDate($hariIni->year, $hariIni->month, $tglSewa)->addMonthNoOverflow()->startOfDay();
                        } else {
                            $batasBayar = \Carbon\Carbon::createFromDate($hariIni->year, $hariIni->month, $tglSewa)->startOfDay();
                        }
                        $sisaHari = (int) $hariIni->diffInDays($batasBayar, false);
                        $isWarning = $sisaHari <= 7; 
                    }
                @endphp

                @if($mulaiSewa)
                    <div class="mb-4 flex h-36 w-36 flex-col items-center justify-center rounded-full border-4 {{ $isWarning ? 'border-amber-400 bg-amber-50' : 'border-blue-400 bg-blue-50' }}">
                        <span class="text-5xl font-black {{ $isWarning ? 'text-amber-600' : 'text-gray-800' }}">{{ $sisaHari }}</span>
                        <span class="text-xs font-bold uppercase tracking-wider {{ $isWarning ? 'text-amber-600' : 'text-gray-500' }}">Sisa Hari</span>
                    </div>
                    <div class="w-full rounded-lg border {{ $isWarning ? 'border-amber-200 bg-amber-50' : 'border-gray-100 bg-gray-50' }} px-4 py-3 text-center mb-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider {{ $isWarning ? 'text-amber-600' : 'text-gray-500' }}">Batas Pembayaran Sewa</p>
                        <p class="mt-1 text-base font-black {{ $isWarning ? 'text-amber-700' : 'text-gray-800' }}">{{ $batasBayar->translatedFormat('d F Y') }}</p>
                    </div>

                    <button onclick="toggleModal('modalKeluar')" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50 hover:text-red-600 flex justify-center items-center gap-2 shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Atur Rencana Keluar Kos
                    </button>
                    
                    @if($user->tanggal_selesaiSewa)
                        <div class="mt-3 text-center bg-green-50 border border-green-100 rounded px-3 py-1">
                            <p class="text-[11px] text-green-700 font-bold">✓ Keluar Terjadwal: {{ \Carbon\Carbon::parse($user->tanggal_selesaiSewa)->translatedFormat('d M Y') }}</p>
                        </div>
                    @endif
                @else
                    <div class="mb-4 flex h-36 w-36 flex-col items-center justify-center rounded-full border-4 border-gray-300 bg-gray-50">
                        <span class="text-4xl font-bold text-gray-400">-</span>
                    </div>
                    <div class="w-full text-center">
                        <p class="text-sm font-bold text-gray-600">Siklus Sewa Belum Aktif</p>
                    </div>
                @endif
            </div>
        </div>

        <div id="daftar-tagihan" class="rounded-lg border border-gray-200 bg-white shadow-sm flex flex-col">
            <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-5 py-4">
                <div class="flex items-center gap-3">
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
                                    <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $tagihan->type }}</h4>
                                    <p class="text-xs text-gray-500">Dikeluarkan: {{ $tagihan->created_at->format('d M Y') }}</p>
                                    @if($tagihan->angka_meteran)
                                        <p class="text-xs font-semibold text-gray-700 mt-1">Meteran: {{ $tagihan->angka_meteran }} kWh</p>
                                    @endif
                                </div>
                                <div class="flex w-full sm:w-auto items-center sm:flex-col gap-3 sm:gap-2">
                                    <p class="flex-1 sm:flex-none text-lg font-black text-gray-900">Rp {{ number_format($tagihan->total, 0, ',', '.') }}</p>
                                    <a href="{{ route('pembayaran.checkout', $tagihan->id ?? 1) }}" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-bold text-white transition-colors hover:bg-blue-700 text-center">
                                        Bayar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex h-full flex-col items-center justify-center text-center py-10">
                        <div class="mb-3 rounded-full bg-green-100 p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800">Semua Tagihan Lunas!</h4>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div id="modalKeluar" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md animate-fade-in">
                
                <form action="{{ route('tenant.request-keluar') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Rencana Keluar Kos</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Tentukan tanggal Anda berencana keluar.</p>
                                    
                                    <label for="tanggal_selesaiSewa" class="block text-[11px] font-bold uppercase text-gray-600 mb-1">Pilih Tanggal Keluar</label>
                                    <input type="date" name="tanggal_selesaiSewa" id="tanggal_selesaiSewa" required value="{{ $user->tanggal_selesaiSewa }}"
                                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Simpan Tanggal
                        </button>
                        <button type="button" onclick="toggleModal('modalKeluar')" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if(modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
    </script>
@endsection
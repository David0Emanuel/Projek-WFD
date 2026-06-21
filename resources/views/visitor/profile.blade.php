@extends('layouts.visitor')

@section('title', 'Profile Visitor - KosInAja')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.5fr_1fr]">
    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6">
            
            <div class="flex flex-col items-start gap-4 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Profile Visitor</p>
                    <h1 class="mt-1 text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->nama ?? Auth::user()->username }}</h1>
                </div>
                <div class="w-full sm:w-auto rounded-full bg-blue-100 px-4 py-1.5 text-center text-xs font-bold text-blue-700">
                    Role : Visitor
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase text-gray-500">Cabang Tersedia</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">{{ $branchesCount ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase text-gray-500">Kamar Kosong</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">{{ $availableRoomsCount ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase text-gray-500">Status Akun</p>
                    <p class="mt-2 text-lg font-bold capitalize text-gray-800">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <div class="rounded-lg border border-blue-100 bg-blue-50 p-6">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <h2 class="text-base font-bold text-blue-800">Alur Booking</h2>
                </div>
                <ol class="space-y-2 text-sm text-blue-900">
                    <li class="flex gap-2"><span class="font-bold">1.</span> Lihat daftar cabang dan informasi kamar di halaman beranda.</li>
                    <li class="flex gap-2"><span class="font-bold">2.</span> Pilih kamar yang tersedia kemudian booking.</li>
                    <li class="flex gap-2"><span class="font-bold">3.</span> Setelah booking berhasil, role Anda akan otomatis menjadi tenant.</li>
                </ol>
            </div>
            
        </div>
    </section>

    <section class="h-fit rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center gap-2 border-b border-gray-100 pb-4">
            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <h2 class="text-base font-bold text-gray-800">Informasi Akun</h2>
        </div>
        
        <div class="space-y-3">
            <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                <p class="text-xs font-semibold uppercase text-gray-500">Username</p>
                <p class="mt-1 text-sm font-bold text-gray-800">{{ Auth::user()->username }}</p>
            </div>
            
            <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                <p class="text-xs font-semibold uppercase text-gray-500">Nomor WhatsApp</p>
                <p class="mt-1 text-sm font-bold text-gray-800">{{ Auth::user()->no_wa }}</p>
            </div>
            
            @if(Auth::user()->email)
                <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase text-gray-500">Email</p>
                    <p class="mt-1 text-sm font-bold text-gray-800">{{ Auth::user()->email }}</p>
                </div>
            @endif
        </div>
    </section>
</div>

{{-- BAGIAN BARU: RIWAYAT TRANSAKSI / TAGIHAN --}}
<div class="mt-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    <div class="mb-4 flex items-center gap-2 border-b border-gray-100 pb-4">
        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h2 class="text-base font-bold text-gray-800">Riwayat Tagihan & Booking</h2>
    </div>

    <div class="space-y-4">
        @forelse(isset($transaksis) ? $transaksis : [] as $trx)
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center rounded-lg border border-gray-100 bg-gray-50 p-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase">{{ $trx->type }} Booking</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Order ID: {{ $trx->id }} | Tgl: {{ $trx->created_at->format('d M Y') }}</p>
                </div>
                
                <div class="mt-4 sm:mt-0 flex flex-col items-start sm:items-end gap-2">
                    @if(strtolower($trx->status_transaksi) == 'unpaid' || strtolower($trx->status_transaksi) == 'pending')
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">Belum Dibayar</span>
                        
                        {{-- INI ADALAH TOMBOL SAKTI UNTUK MELANJUTKAN PEMBAYARAN --}}
                        <a href="{{ route('pembayaran.checkout', $trx->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white transition-colors hover:bg-blue-700 shadow-sm cursor-pointer">
                            Lanjutkan Pembayaran
                        </a>

                    @elseif(strtolower($trx->status_transaksi) == 'paid' || strtolower($trx->status_transaksi) == 'settlement')
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">Lunas</span>
                        <p class="text-[10px] text-gray-500">Menunggu Check-in Admin</p>
                    @else
                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">{{ ucfirst($trx->status_transaksi) }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-6 text-sm text-gray-500 italic border-2 border-dashed border-gray-200 rounded-xl">
                Anda belum memiliki riwayat tagihan booking.
            </div>
        @endforelse
    </div>
</div>
@endsection
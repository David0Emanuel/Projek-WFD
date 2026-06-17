@extends('layouts.visitor')

@section('title', 'Profile Visitor - KosInAja')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
    <!-- Section Kiri: Header & Statistik -->
    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6">
            
            <!-- Header Profile -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 pb-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Profile Visitor</p>
                    <h1 class="mt-1 text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->nama ?? Auth::user()->username }}</h1>
                </div>
                <div class="rounded-full bg-blue-100 px-4 py-1.5 text-xs font-bold text-blue-700">
                    Role : Visitor
                </div>
            </div>

            <!-- Card Statistik -->
            <!-- <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase text-gray-500">Cabang Tersedia</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">{{ $branchesCount }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase text-gray-500">Kamar Kosong</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">{{ $availableRoomsCount }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-5">
                    <p class="text-xs font-semibold uppercase text-gray-500">Status Akun</p>
                    <p class="mt-2 text-lg font-bold capitalize text-gray-800">{{ Auth::user()->role }}</p>
                </div>
            </div> -->

            <!-- Info Alur Booking -->
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

    <!-- Section Kanan: Info Akun -->
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
@endsection
@extends('layouts.visitor')

@section('title', 'Detail Kos - PuluBoys')

@section('content')
<div class="space-y-6">
    
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-sm font-medium text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-gray-900 transition-colors">Beranda</a>
        <span>/</span>
        <a href="{{ route('visitor.branches') }}" class="hover:text-gray-900 transition-colors">Daftar Cabang</a>
        <span>/</span>
        <span class="text-gray-800">{{ $branch->nama ?? 'Nama Kos' }}</span>
    </nav>

    <!-- Header Kos -->
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900">{{ $branch->nama ?? 'Nama Kos' }}</h1>
        <p class="mt-2 text-sm text-gray-600">
            <svg class="mr-1 inline-block h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
            {{ $branch->alamat ?? 'Alamat Kos' }}
        </p>
    </div>

    <!-- Layout Utama: Kiri (Konten) & Kanan (Sidebar Transaksi) -->
    <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">

        <!-- ================= KOLOM KIRI (KONTEN UTAMA) ================= -->
        <div class="space-y-8">
            
            <!-- Foto Utama Cabang -->
            <div class="aspect-video w-full overflow-hidden rounded-2xl border border-gray-200 bg-gray-100 shadow-sm">
                @if(isset($branch->foto) && $branch->foto)
                    <img src="{{ asset('storage/' . $branch->foto) }}" alt="Foto {{ $branch->nama }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full w-full flex-col items-center justify-center text-gray-400">
                        <svg class="mb-2 h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">Belum ada foto cabang</span>
                    </div>
                @endif
            </div>

            <!-- Tipe Kamar Tersedia (Cards) -->
            <div>
                <h2 class="mb-4 text-xl font-bold text-gray-900">Tipe Kamar Tersedia</h2>
                
                @php
                    // Mengelompokkan kamar yang kosong berdasarkan tipe (single/double)
                    $availableRoomsByType = isset($branch) ? $branch->kamars->where('status', 'Kosong')->groupBy('tipe_kamar') : collect();
                @endphp

                <div class="grid gap-6 sm:grid-cols-2">
                    @forelse($availableRoomsByType as $tipe => $kamars)
                        @php
                            // Mengambil 1 perwakilan kamar dari grup tersebut untuk mewakili harga & foto
                            $contohKamar = $kamars->first(); 
                        @endphp
                        
                        <article class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                            <!-- Foto Tipe Kamar -->
                            <div class="aspect-[4/3] w-full bg-gray-100 border-b border-gray-100">
                                @if(isset($contohKamar->foto) && $contohKamar->foto)
                                    <img src="{{ asset('storage/' . $contohKamar->foto) }}" alt="Kamar {{ $tipe }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-gray-300">
                                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Info Tipe Kamar -->
                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-2 flex items-start justify-between gap-2">
                                    <h3 class="text-lg font-bold text-gray-900">Kamar <span class="uppercase text-blue-600">{{ $tipe }}</span></h3>
                                    <span class="shrink-0 rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-green-700">Tersedia {{ $kamars->count() }}</span>
                                </div>
                                <p class="text-xl font-black text-gray-900">Rp {{ number_format($contohKamar->harga, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">/ bulan</span></p>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm font-medium text-gray-500">
                            Mohon maaf, saat ini tidak ada kamar yang kosong di cabang ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tentang Kamar & Spesifikasi (Grid) -->
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Spesifikasi Kamar</h2>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        <li class="flex items-center gap-3"><span class="text-blue-500">📏</span> Ukuran 3x4 Meter</li>
                        <li class="flex items-center gap-3"><span class="text-blue-500">🛏️</span> Kasur Springbed Lengkap</li>
                        <li class="flex items-center gap-3"><span class="text-blue-500">🚿</span> Kamar Mandi Dalam</li>
                        <li class="flex items-center gap-3"><span class="text-blue-500">⚡</span> Listrik Token (Terpisah)</li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Fasilitas Gratis</h2>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        <li class="flex items-center gap-3"><span class="text-green-500">✓</span> WiFi Kecepatan Tinggi</li>
                        <li class="flex items-center gap-3"><span class="text-green-500">✓</span> Air Minum (Galon)</li>
                        <li class="flex items-center gap-3"><span class="text-green-500">✓</span> Parkir Motor Luas</li>
                        <li class="flex items-center gap-3"><span class="text-green-500">✓</span> Dapur Bersama</li>
                    </ul>
                </div>
            </div>
            
        </div>

        <!-- ================= KOLOM KANAN (SIDEBAR BOOKING) ================= -->
        <div>
            <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <!-- Harga Termurah -->
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Mulai Dari</p>
                <div class="mt-1 flex items-end gap-1">
                    <span class="text-3xl font-black text-gray-900">
                        Rp {{ isset($branch) && $branch->kamars->count() > 0 ? number_format($branch->kamars->min('harga'), 0, ',', '.') : '0' }}
                    </span>
                    <span class="mb-1 text-sm font-medium text-gray-500">/ bulan</span>
                </div>

                <hr class="my-6 border-dashed border-gray-200">

                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Status Kamar Info -->
                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Status Kamar</label>
                        @if(isset($branch) && $branch->kamars->where('status', 'Kosong')->count() > 0)
                            <div class="flex items-center justify-between rounded-xl border border-green-200 bg-green-50 px-4 py-3">
                                <span class="text-sm font-bold text-green-700">Tersedia</span>
                                <span class="text-xs font-bold text-green-600">{{ $branch->kamars->where('status', 'Kosong')->count() }} Kamar Kosong</span>
                            </div>
                        @else
                            <div class="flex items-center justify-between rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                                <span class="text-sm font-bold text-red-700">Penuh</span>
                            </div>
                        @endif
                    </div>

                    <!-- Pilih Kamar Dropdown -->
                    @if(isset($branch) && $branch->kamars->where('status', 'Kosong')->count() > 0)
                    <div>
                        <label for="kamar_id" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Pilih Kamar</label>
                        <!-- Dropdown menampilkan detail kamar (Nomor Kamar, Tipe, Harga) sesuai data dari backend -->
                        <select id="kamar_id" name="kamar_id" required
                                class="w-full cursor-pointer rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                            <option value="" disabled selected>-- Pilih Kamar Tersedia --</option>
                            @foreach($branch->kamars->where('status', 'Kosong') as $kamar)
                                <option value="{{ $kamar->id }}">
                                    Kamar {{ $kamar->nomor_kamar }} ({{ ucfirst($kamar->tipe_kamar) }}) - Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Rencana Masuk -->
                    <div>
                        <label for="tanggal_masuk" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Rencana Masuk</label>
                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" required
                               class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    </div>

                    <hr class="my-6 border-dashed border-gray-200">

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full cursor-pointer rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">
                            Ajukan Booking
                        </button>
                        
                        <a href="https://wa.me/{{ config('puluboys.wa_admin', '6281111111111') }}" target="_blank" 
                           class="flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3.5 text-sm font-bold text-gray-700 transition-all hover:bg-gray-50 hover:shadow-sm">
                           <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
                            </svg>
                            Tanya Admin
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
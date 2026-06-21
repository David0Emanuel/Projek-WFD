@extends('layouts.visitor')

@section('title', 'Beranda - KosInAja')

@section('content')
<div class="space-y-12 lg:space-y-16">
    

    <section class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm shadow-gray-200 sm:p-10 lg:p-12">
        <div class="max-w-3xl space-y-4">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600 sm:text-sm">Selamat datang di KosInAja</p>
            <h1 class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl md:text-5xl">Cari kos nyaman & sesuai budget di dekat kamu.</h1>
            <p class="text-sm leading-relaxed text-gray-600 sm:text-base">Jelajahi daftar cabang, cek total kamar, dan lihat sisa kamar tersedia sebelum booking. Login atau daftar untuk menyelesaikan pemesanan.</p>
            
            <div class="mt-8 flex flex-wrap gap-3 sm:gap-4">
                <a href="{{ route('visitor.branches') }}" class="rounded-full bg-blue-600 px-6 py-4 text-center font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">Lihat Cabang Kos</a>
                <a href="#cara-pesan" class="rounded-full border border-gray-200 bg-white px-6 py-4 text-center font-bold text-gray-700 transition-all hover:bg-gray-50 hover:shadow-sm">Cara Pesan</a>
            </div>
        </div>
    </section>

    <section class="space-y-6">
        <div class="flex items-end justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Kabar Cabang</h2>
                <p class="mt-2 text-sm text-gray-600">Daftar cabang kos dan ketersediaan kamar terbaru.</p>
            </div>
            @if ($branches->count() > 3)
                <a href="{{ route('visitor.branches') }}" class="hidden text-sm font-bold text-blue-600 transition-colors hover:text-blue-800 sm:block">
                    Lihat Semua Cabang &rarr;
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($branches->take(3) as $branch)
                <article class="group flex flex-col rounded-3xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-1 hover:border-blue-300 hover:shadow-md sm:p-6">
                    <h3 class="text-lg font-bold text-gray-900 transition-colors group-hover:text-blue-600">{{ $branch->nama }}</h3>
                    <p class="mt-1 text-sm text-gray-500 line-clamp-1">{{ $branch->alamat ?? 'Alamat belum tersedia' }}</p>
                    
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-gray-50 p-3 text-center sm:p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Total Kamar</p>
                            <p class="mt-1 text-xl font-black text-gray-900">{{ $branch->kamars_count }}</p>
                        </div>
                        <div class="rounded-2xl bg-green-50 p-3 text-center sm:p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-green-600">Sisa Kosong</p>
                            {{-- Catatan: Menggunakan variabel kamar_kosong_count dari Controller sebelumnya --}}
                            <p class="mt-1 text-xl font-black text-green-700">{{ $branch->available_kamar_count ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="mt-auto pt-6">
                        <a href="{{ route('visitor.branch.show', $branch->id) }}" class="block w-full rounded-2xl bg-blue-600 px-4 py-2.5 text-center text-sm font-bold text-white transition-colors hover:bg-blue-700">
                            Detail Kamar
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm font-medium text-gray-500">
                    Belum ada cabang kos yang tersedia saat ini.
                </div>
            @endforelse
        </div>

        @if ($branches->count() > 3)
            <div class="mt-6 sm:hidden">
                <a href="{{ route('visitor.branches') }}" class="block w-full rounded-2xl border border-gray-200 bg-white px-4 py-3.5 text-center text-sm font-bold text-blue-600 shadow-sm transition-all hover:border-blue-200 hover:bg-blue-50">
                    Lihat Semua Cabang &rarr;
                </a>
            </div>
        @endif
    </section>

    <section id="cara-pesan" class="scroll-mt-24 rounded-3xl border border-gray-100 bg-white p-6 shadow-sm shadow-gray-200 sm:p-8 lg:p-10">
        <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Alur Nyewa Kos</h2>
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $steps = [
                    ['Lihat Daftar Cabang', 'Telusuri cabang kos, total kamar, dan ketersediaan tanpa login.'],
                    ['Pilih Kamar', 'Pilih kamar dari cabang yang tersedia dan lihat rincian fasilitasnya.'],
                    ['Login atau Daftar', 'Jika ingin booking, masuk dulu ke akun atau buat akun baru.'],
                    ['Booking & Selesai', 'Lakukan booking segera, dan status Anda akan menjadi tenant.']
                ];
            @endphp
            @foreach($steps as $index => $step)
            <article class="relative overflow-hidden rounded-3xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-blue-200 hover:bg-white hover:shadow-sm">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                    {{ $index + 1 }}
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ $step[0] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $step[1] }}</p>
            </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
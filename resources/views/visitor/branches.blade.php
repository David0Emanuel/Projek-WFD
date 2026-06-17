@extends('layouts.visitor')

@section('title', 'Daftar Cabang')

@section('content')
<section class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm shadow-gray-200 sm:p-8 lg:p-10">
    <!-- Header Page -->
    <div class="flex flex-col gap-5 border-b border-gray-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Daftar Cabang</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">Cabang kos yang tersedia</h1>
            <p class="mt-2 text-sm text-gray-600 sm:text-base">Lihat cabang kos, total kamar, dan berapa kamar kosong yang tersisa.</p>
        </div>
        @guest
            <a href="{{ route('login') }}" class="inline-flex shrink-0 items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">
                Login untuk Booking
            </a>
        @endguest
    </div>

    <!-- Grid Branches -->
    <div class="mt-8 grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
        @forelse ($branches as $branch)
            <article class="group flex flex-col overflow-hidden rounded-3xl border border-gray-200 bg-gray-50 transition-all hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                <div class="flex flex-col gap-4 border-b border-gray-200/60 p-6">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $branch->nama }}</h2>
                        <!-- Badge -->
                        @if(($branch->available_kamar_count ?? 0) > 0)
                            <span class="shrink-0 rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-green-700">Tersedia</span>
                        @else
                            <span class="shrink-0 rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-red-600">Penuh</span>
                        @endif
                    </div>
                    <p class="text-sm leading-relaxed text-gray-600 line-clamp-2">{{ $branch->alamat ?? 'Alamat belum terisi' }}</p>
                </div>
                
                <div class="grid grid-cols-2 divide-x divide-gray-200/60 bg-white p-2 border-b border-gray-200/60">
                    <div class="p-4 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Kamar</p>
                        <p class="mt-1 text-xl font-black text-gray-900">{{ $branch->kamars_count ?? 0 }}</p>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-green-600">Sisa Kosong</p>
                        <p class="mt-1 text-xl font-black text-green-700">{{ $branch->available_kamar_count ?? 0 }}</p>
                    </div>
                </div>

                <!-- Tombol Detail Kamar -->
                <div class="bg-white p-4">
                    <a href="{{ route('visitor.branch.show', $branch->id) }}" class="block w-full rounded-2xl bg-blue-600 px-4 py-3 text-center text-sm font-bold text-white transition-colors hover:bg-blue-700">
                        Lihat Detail & Kamar
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-gray-50 p-10 text-center text-gray-500">
                <p class="text-base font-medium">Belum ada cabang kos yang terdaftar.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
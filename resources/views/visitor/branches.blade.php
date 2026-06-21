@extends('layouts.visitor')

@section('title', 'Daftar Cabang - KosInAja')

@section('content')
<section class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm shadow-gray-200 sm:p-8 lg:p-10">
    <div class="flex flex-col gap-5 border-b border-gray-100 pb-6 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Daftar Cabang</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">Cabang kos yang tersedia</h1>
            <p class="mt-2 text-sm text-gray-600 sm:text-base">Lihat cabang kos, total kamar, dan berapa kamar kosong yang tersisa.</p>
        </div>
        @guest
            <a href="{{ route('login') }}" class="inline-flex w-full sm:w-auto shrink-0 items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">
                Login untuk Booking
            </a>
        @endguest
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($branches as $branch)
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
</section>
@endsection
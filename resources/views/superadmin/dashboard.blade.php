@extends('layouts.superadmin') 
@section('title', 'Super Admin Dashboard')
@section('page-title', 'Super Admin Dashboard')

@section('content')

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <section class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        
        <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mr-4 rounded-full bg-blue-100 p-3 text-blue-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Total Pendapatan</p>
                <p class="text-lg font-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mr-4 rounded-full bg-green-100 p-3 text-green-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Total Cabang</p>
                <p class="text-lg font-bold text-gray-800">{{ $totalCabang }} Properti</p>
            </div>
        </div>

        <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mr-4 rounded-full bg-purple-100 p-3 text-purple-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Total Penghuni</p>
                <p class="text-lg font-bold text-gray-800">{{ $totalPenghuni }} Orang</p>
            </div>
        </div>

        <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mr-4 rounded-full bg-red-100 p-3 text-red-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Keluhan Aktif</p>
                <p class="text-lg font-bold text-gray-800">{{ $totalKomplain }} Menunggu</p>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                <h2 class="text-base font-bold text-gray-800">List Pengajuan Survey</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 min-w-[600px]">
                    <thead class="border-b border-gray-200 bg-white text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-4">Pendaftar Survey</th>
                            <th class="px-6 py-4">Cabang Tujuan</th>
                            <th class="px-6 py-4">Jadwal Diajukan</th>
                            <th class="px-6 py-4 text-center">Status Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($surveys as $survey)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $survey->user->nama ?? 'Visitor Terhapus' }}</div>
                                <div class="text-xs text-gray-500">{{ $survey->user->no_wa ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $survey->kos->nama ?? 'KosInAja' }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($survey->waktu_survey)->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(strtolower($survey->status) == 'pending')
                                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-650/20">
                                        Pending (Menunggu Admin)
                                    </span>
                                @elseif(strtolower($survey->status) == 'approved')
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        Approved oleh Cabang
                                    </span>
                                @elseif(strtolower($survey->status) == 'selesai')
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-650/20">
                                        Selesai ✔
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                        {{ ucfirst($survey->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-sm text-gray-500 text-center py-6">Belum ada riwayat data survey yang masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-amber-50 px-5 py-3">
                <h3 class="text-sm font-bold text-gray-700">Kirim Pengumuman & WA Blast</h3>
            </div>
            <div class="p-5">
                <form action="{{ route('superadmin.pengumuman.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Judul Pengumuman</label>
                        <input type="text" name="judul" required class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Contoh: Jadwal Maintenance">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Tujuan Cabang</label>
                        <select name="kos_id" required class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="all">Semua Cabang (Global)</option>
                            @foreach($cabangList as $cabang)
                                <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Isi Pesan</label>
                        <textarea name="isi" rows="4" required class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Tulis pesan untuk tenant..."></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-gray-800 px-4 py-2 text-sm font-bold text-white hover:bg-gray-900 cursor-pointer transition-colors">
                        Kirim Sekarang
                    </button>
                </form>
            </div>
        </div>

    </section>

@endsection
@extends('layouts.admin')

@section('title', 'Survey & Check-In')
@section('page-title', 'Kelola Survey & Check-In')

@section('content')
<div class="space-y-8">

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 shadow-sm">
            <div class="flex items-center gap-3 text-green-800">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
            <h2 class="text-base font-bold text-gray-800">Menunggu Check-In (Booking Lunas)</h2>
        </div>
        <div class="p-6 space-y-4">
            @forelse($checkins as $kamar)
            @php
                $transaksiLunas = \App\Models\Transaksi::where('kamar_id', $kamar->id)
                                    ->whereIn('type', ['DP', 'DP Booking'])
                                    ->where('status_transaksi', 'Paid')
                                    ->exists();
            @endphp

            <div class="flex flex-col items-start justify-between gap-4 rounded-xl border border-blue-100 bg-blue-50 p-4 sm:flex-row sm:items-center transition-all hover:border-blue-200">
                <div>
                    <p class="text-sm font-bold text-gray-900">Kamar {{ $kamar->nomor }} - {{ $kamar->user->nama ?? 'Menunggu Data' }}</p>
                    <p class="text-xs text-gray-600">
                        @if($transaksiLunas)
                            <span class="font-bold text-blue-700">
                                {{ $kamar->transaksis->first()?->user?->nama ?? 'Visitor' }}
                            </span> telah mengamankan kamar. Silakan hubungi untuk proses check-in.
                        @else
                            <span class="text-red-500 font-bold">Status: Belum Lunas DP</span>
                        @endif
                    </p>
                </div>
                
                @if($transaksiLunas)
                    <form action="{{ route('admin.kamar.checkin') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
                        <button type="submit" class="w-full sm:w-auto rounded-lg bg-green-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-green-700">
                            Selesaikan Check-In
                        </button>
                    </form>
                @else
                    <button type="button" disabled class="w-full sm:w-auto rounded-lg bg-gray-300 px-5 py-2.5 text-sm font-bold text-white cursor-not-allowed">
                        Belum Lunas DP
                    </button>
                @endif
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">Tidak ada visitor yang menunggu check-in saat ini.</p>
            @endforelse
        </div>
    </div>
    
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
            <h2 class="text-base font-bold text-gray-800">Jadwal Pengajuan Survey</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 min-w-[600px]">
                <thead class="border-b border-gray-200 bg-white text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Pendaftar Survey</th>
                        <th class="px-6 py-4">Cabang Tujuan</th>
                        <th class="px-6 py-4">Jadwal Diajukan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($surveys as $survey)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $survey->user->nama ?? 'Visitor' }}</div>
                            <div class="text-xs text-gray-500">{{ $survey->user->no_wa ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $survey->kos->nama ?? 'KosInAja' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($survey->waktu_survey)->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                
                                @if(strtolower($survey->status) == 'selesai')
                                    <button type="button" disabled 
                                            class="rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 text-xs font-bold text-gray-400 cursor-not-allowed">
                                        Reschedule
                                    </button>
                                    <button type="button" disabled 
                                            class="rounded-lg bg-gray-400 px-3 py-2 text-xs font-bold text-white cursor-not-allowed">
                                        Selesai ✔
                                    </button>

                                @else
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $survey->user->no_wa ?? '') }}" 
                                    target="_blank"
                                    class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50">
                                    Reschedule
                                    </a>

                                    @if(strtolower($survey->status) == 'pending')
                                        <form action="{{ route('admin.survey.approve', $survey->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">
                                                Approve
                                            </button>
                                        </form>

                                    @elseif(strtolower($survey->status) == 'approved')
                                        <button type="button" disabled 
                                                class="rounded-lg bg-blue-400 px-3 py-2 text-xs font-bold text-white cursor-not-allowed">
                                            Approved
                                        </button>
                                        <form action="{{ route('admin.survey.complete', $survey->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white hover:bg-green-700">
                                                Selesai
                                            </button>
                                        </form>
                                    @endif

                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
@extends('layouts.admin')

@section('title', 'Survey & Check-In')
@section('page-title', 'Kelola Survey & Check-In')

@section('content')
<div class="space-y-8">

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
            <h2 class="text-base font-bold text-gray-800">Menunggu Check-In (Booking Lunas)</h2>
        </div>
        <div class="p-6 space-y-4">
            @forelse($checkins as $kamar)
            <div class="flex flex-col items-start justify-between gap-4 rounded-xl border border-blue-100 bg-blue-50 p-4 sm:flex-row sm:items-center transition-all hover:border-blue-200">
                <div>
                    <p class="text-sm font-bold text-gray-900">Kamar {{ $kamar->nomor }} - {{ $kamar->user->nama ?? 'Menunggu Data' }}</p>
                    <p class="text-xs text-gray-600">Visitor ini telah mengamankan kamar. Silakan hubungi untuk proses check-in dan serah terima kunci.</p>
                </div>
                
                <form action="#" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
                    <button type="submit" class="w-full sm:w-auto rounded-lg bg-green-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-green-700">
                        Selesaikan Check-In
                    </button>
                </form>
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
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ $survey->no_wa ?? 'Visitor' }}
                        </td>
                        <td class="px-6 py-4">{{ $survey->kos->nama ?? 'KosInAja' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($survey->waktu_survey)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50">Reschedule</button>
                                <button class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">Approve</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada pengajuan survey baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
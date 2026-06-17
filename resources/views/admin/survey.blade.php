@extends('layouts.admin')

@section('title', 'Survey & Check-In')
@section('page-title', 'Kelola Survey & Check-In')

@section('content')
<div class="space-y-8">

    <!-- Blok 1: Validasi Check In -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
            <h2 class="text-base font-bold text-gray-800">Validasi Check-In & Serah Terima Kunci</h2>
        </div>
        <div class="p-6">
            <div class="flex flex-col items-start justify-between gap-4 rounded-xl border border-blue-100 bg-blue-50 p-4 sm:flex-row sm:items-center">
                <div>
                    <p class="text-sm font-bold text-gray-900">Kamar 102 - Albert Wijaya</p>
                    <p class="text-xs text-gray-600">Telah membayar DP. Jadwal masuk: Hari ini.</p>
                </div>
                <button class="rounded-lg bg-green-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-green-700">
                    Konfirmasi Check-In
                </button>
            </div>
        </div>
    </div>

    <!-- Blok 2: Log Pengajuan Survey -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
            <h2 class="text-base font-bold text-gray-800">Log Pengajuan Survey</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="border-b border-gray-200 bg-white text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Visitor</th>
                        <th class="px-6 py-4">Kamar Diminati</th>
                        <th class="px-6 py-4">Jadwal Diajukan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-bold text-gray-900">David Lukianto <br><span class="text-xs font-normal text-gray-500">08123456789</span></td>
                        <td class="px-6 py-4">Single Room</td>
                        <td class="px-6 py-4">Besok, 14:00 WIB</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50">Reschedule</button>
                                <button class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">Approve</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
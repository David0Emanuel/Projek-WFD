@extends('layouts.superadmin')
@section('title', 'Data Penghuni')
@section('page-title', 'Data Penghuni')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Total <span class="font-bold text-gray-800">{{ $penghuni->total() }}</span> penghuni aktif</p>
</div>

<div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3">#</th>
                <th class="px-5 py-3">Nama</th>
                <th class="px-5 py-3">No. WhatsApp</th>
                <th class="px-5 py-3">Kamar</th>
                <th class="px-5 py-3">Cabang</th>
                <th class="px-5 py-3">Masa Sewa Berakhir</th>
                <th class="px-5 py-3 text-center">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($penghuni as $i => $user)
            @php
                $sisaHari = $user->tanggal_selesaiSewa
                    ? now()->diffInDays($user->tanggal_selesaiSewa, false)
                    : null;
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-gray-400">{{ $penghuni->firstItem() + $i }}</td>
                <td class="px-5 py-3 font-semibold text-gray-800">{{ $user->nama }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $user->no_wa ?? '-' }}</td>
                <td class="px-5 py-3">
                    @if($user->kamar)
                        <span class="font-medium text-gray-800">Kamar {{ $user->kamar->nomor }}</span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-500">
                    {{ $user->kamar?->kos?->nama ?? '-' }}
                </td>
                <td class="px-5 py-3">
                    @if($user->tanggal_selesaiSewa)
                        {{ \Carbon\Carbon::parse($user->tanggal_selesaiSewa)->format('d M Y') }}
                        @if($sisaHari !== null && $sisaHari <= 7 && $sisaHari >= 0)
                            <span class="ml-1 text-xs font-bold text-red-500">({{ $sisaHari }} hari lagi)</span>
                        @endif
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-center">
                    @if($sisaHari === null)
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-[10px] font-bold text-gray-500">Tidak Diketahui</span>
                    @elseif($sisaHari < 0)
                        <span class="rounded-full bg-red-100 px-2 py-1 text-[10px] font-bold text-red-700">Expired</span>
                    @elseif($sisaHari <= 7)
                        <span class="rounded-full bg-yellow-100 px-2 py-1 text-[10px] font-bold text-yellow-700">Hampir Habis</span>
                    @else
                        <span class="rounded-full bg-green-100 px-2 py-1 text-[10px] font-bold text-green-700">Aktif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-10 text-center text-gray-400">Belum ada penghuni terdaftar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $penghuni->links() }}
    </div>
</div>

@endsection
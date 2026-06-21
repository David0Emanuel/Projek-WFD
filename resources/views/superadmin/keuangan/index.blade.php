@extends('layouts.superadmin')
@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')

<!-- dashboard cards -->
<section class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase">Bulan Ini</p>
            <p class="text-lg font-bold text-gray-800">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <div class="mr-4 rounded-full bg-purple-100 p-3 text-purple-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase">Total Transaksi</p>
            <p class="text-lg font-bold text-gray-800">{{ number_format($totalTransaksi) }} Transaksi</p>
        </div>
    </div>
</section>

<!-- grafik -->
<section class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-700 mb-4">Pendapatan 6 Bulan Terakhir</h3>
        <canvas id="chartPendapatan" height="100"></canvas>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-700 mb-4">Pendapatan per Cabang</h3>
        <canvas id="chartCabang" height="180"></canvas>
        <div class="mt-4 space-y-1" id="legend-cabang"></div>
    </div>
</section>

<!-- filter n tabel transaksi -->
<div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-slate-50 px-5 py-3">
        <h3 class="text-sm font-bold text-gray-700">Riwayat Transaksi</h3>
        <form method="GET" action="{{ route('superadmin.keuangan.index') }}" class="flex flex-wrap gap-2">
            {{-- Dropdown Tipe --}}
            <select name="type" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">Semua Tipe</option>
                <option value="dp" {{ request('type') == 'dp' ? 'selected' : '' }}>DP Booking</option>
                <option value="tagihan" {{ request('type') == 'tagihan' ? 'selected' : '' }}>Tagihan Bulanan</option>
            </select>

            {{-- Dropdown Status --}}
            <select name="status" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
            </select>

            {{-- Dropdown Bulan --}}
            <select name="bulan" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">Semua Bulan</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <button type="submit" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-700 cursor-pointer">
                Filter
            </button>
            
            @if(request()->hasAny(['type', 'status', 'bulan']))
                <a href="{{ route('superadmin.keuangan.index') }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3">No. Invoice</th>
                    <th class="px-5 py-3">Penghuni</th>
                    <th class="px-5 py-3">Kamar / Cabang</th>
                    <th class="px-5 py-3">Tipe</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transaksi as $trx)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">INV/{{ $trx->created_at->format('Y') }}/{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $trx->user?->nama ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-500">
                        Kamar {{ $trx->kamar?->nomor ?? '-' }}
                        <span class="text-gray-400">/ {{ $trx->kamar?->kos?->nama ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-3">
                        @if(in_array(strtolower($trx->type), ['dp', 'dp booking']))
                            <span class="rounded-full bg-purple-100 px-2 py-1 text-[10px] font-bold text-purple-700">DP Booking</span>
                        @else
                            <span class="rounded-full bg-blue-100 px-2 py-1 text-[10px] font-bold text-blue-700">Tagihan Bulanan</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right font-bold text-gray-800">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if(strtolower($trx->status_transaksi) === 'paid')
                            <span class="rounded-full bg-green-100 px-2 py-1 text-[10px] font-bold text-green-700">Lunas</span>
                        @else
                            <span class="rounded-full bg-red-100 px-2 py-1 text-[10px] font-bold text-red-700">Belum Lunas</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $trx->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">Tidak ada transaksi ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $transaksi->links() }}
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // LINE CHART: Pendapatan 6 Bulan
    const ctxLine = document.getElementById('chartPendapatan').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($bulanData) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#2563eb',
                pointRadius: 4,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + (val / 1000000).toFixed(1) + 'jt'
                    }
                }
            }
        }
    });

    // DONUT CHART: Per Cabang
    const cabangData   = {!! json_encode($pendapatanPerCabang) !!};
    const cabangLabels = cabangData.map(c => c.nama);
    const cabangValues = cabangData.map(c => c.total);
    const colors       = ['#2563eb', '#16a34a', '#d97706', '#dc2626', '#7c3aed', '#0891b2'];

    const ctxDonut = document.getElementById('chartCabang').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: cabangLabels,
            datasets: [{
                data: cabangValues,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.label + ': Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // Legend manual
    const legendEl = document.getElementById('legend-cabang');
    cabangLabels.forEach((label, i) => {
        legendEl.innerHTML += `
            <div class="flex items-center gap-2 text-xs text-gray-600">
                <span class="h-2.5 w-2.5 rounded-full flex-shrink-0" style="background:${colors[i]}"></span>
                <span class="truncate">${label}</span>
            </div>`;
    });
</script>
@endpush
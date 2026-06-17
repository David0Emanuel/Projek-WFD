<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kos;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Data untuk grafik — pendapatan 6 bulan terakhir
        $bulanLabels = [];
        $bulanData   = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $bulanLabels[] = $bulan->translatedFormat('M Y');
            $bulanData[]   = Transaksi::where('status_transaksi', 'paid')
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->sum('total');
        }

        // Statistik ringkasan
        $totalPendapatan   = Transaksi::where('status_transaksi', 'paid')->sum('total');
        $pendapatanBulanIni = Transaksi::where('status_transaksi', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $totalTransaksi    = Transaksi::where('status_transaksi', 'paid')->count();

        // Tabel transaksi dengan filter
        $query = Transaksi::with(['user', 'kamar.kos'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan)
                  ->whereYear('created_at', now()->year);
        }

        $transaksi = $query->paginate(15)->withQueryString();

        // Pendapatan per cabang untuk grafik donut
        $pendapatanPerCabang = Kos::withSum(['transaksi as total_pendapatan' => function ($q) {
                $q->where('status_transaksi', 'paid');
            }], 'total')
            ->get()
            ->map(fn($k) => [
                'nama'  => $k->nama,
                'total' => $k->total_pendapatan ?? 0,
            ]);

        return view('superadmin.keuangan.index', compact(
            'bulanLabels', 'bulanData',
            'totalPendapatan', 'pendapatanBulanIni', 'totalTransaksi',
            'transaksi', 'pendapatanPerCabang'
        ));
    }
}
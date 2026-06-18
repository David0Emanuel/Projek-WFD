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
            $bulanData[]   = Transaksi::whereIn('status_transaksi', ['paid', 'Paid'])
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->sum('total');
        }

        // Statistik ringkasan pendapatan
        $totalPendapatan   = Transaksi::whereIn('status_transaksi', ['paid', 'Paid'])->sum('total');
        $pendapatanBulanIni = Transaksi::whereIn('status_transaksi', ['paid', 'Paid'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $totalTransaksi    = Transaksi::whereIn('status_transaksi', ['paid', 'Paid'])->count();

        // Tabel transaksi dengan filter fleksibel
        $query = Transaksi::with(['user', 'kamar.kos'])->latest();

        if ($request->filled('status')) {
            $query->where('status_transaksi', 'LIKE', $request->status);
        }
        
        if ($request->filled('type')) {
            if (strtolower($request->type) === 'dp') {
                $query->where(function($q) {
                    $q->where('type', 'DP')->orWhere('type', 'DP Booking');
                });
            } else {
                $query->where('type', 'LIKE', $request->type);
            }
        }
        
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan)
                  ->whereYear('created_at', now()->year);
        }

        $transaksi = $query->paginate(15)->withQueryString();

        // Pendapatan per cabang untuk grafik donut
        $pendapatanPerCabang = Kos::withSum(['kamars as total_pendapatan' => function ($q) {
                // Catatan: Model Kos berelasi ke Kamar, Kamar berelasi ke Transaksi
                // Menggunakan subquery relasi melintasi kamars
                $q->whereHas('transaksis', function($sub) {
                    $sub->whereIn('status_transaksi', ['paid', 'Paid']);
                })->join('transaksis', 'kamars.id', '=', 'transaksis.kamar_id');
            }], 'transaksis.total')
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
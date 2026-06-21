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

        $totalPendapatan   = Transaksi::whereIn('status_transaksi', ['paid', 'Paid'])->sum('total');
        $pendapatanBulanIni = Transaksi::whereIn('status_transaksi', ['paid', 'Paid'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $totalTransaksi    = Transaksi::whereIn('status_transaksi', ['paid', 'Paid'])->count();

        $query = Transaksi::with(['user', 'kamar.kos'])->latest();

        // 1. Filter Status (Mengantisipasi perbedaan huruf besar/kecil di DB)
        if ($request->filled('status')) {
            $status = strtolower($request->status);
            if ($status === 'paid') {
                $query->whereIn('status_transaksi', ['paid', 'Paid', 'PAID']);
            } else {
                $query->whereIn('status_transaksi', ['unpaid', 'Unpaid', 'UNPAID', 'pending', 'Pending']);
            }
        }
        
        // 2. Filter Tipe 
        if ($request->filled('type')) {
            $type = strtolower($request->type);
            if ($type === 'dp') {
                $query->whereIn('type', ['DP', 'DP Booking', 'dp']);
            } elseif ($type === 'tagihan') {
                $query->whereIn('type', ['Bulanan', 'bulanan', 'Tagihan', 'tagihan', 'Tagihan Bulanan']);
            }
        }
        
        // 3. Filter Bulan 
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
            $query->whereYear('created_at', now()->year);
        }

        $transaksi = $query->paginate(15)->withQueryString();

        // Pendapatan per cabang untuk grafik donut
        $pendapatanPerCabang = Kos::withSum(['kamars as total_pendapatan' => function ($q) {
                // Catatan: Model Kos berelasi ke Kamar, Kamar berelasi ke Transaksi
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
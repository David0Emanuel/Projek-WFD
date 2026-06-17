<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\MaintenanceTicketController;
use Illuminate\Pagination\LengthAwarePaginator;

Route::get('/', [VisitorController::class, 'index'])->name('home');
Route::get('/daftar-cabang', [VisitorController::class, 'branches'])->name('visitor.branches');
Route::get('/daftar-cabang/{id}', [VisitorController::class, 'show'])->name('visitor.branch.show');

// --- AUTH ROUTES (DIBUKA SEMENTARA) ---
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    return redirect()->back();
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    return redirect()->back();
});

Route::post('/logout', function () {
    return redirect()->route('home');
})->name('logout');


// =====================================================================
// --- PROTECTED ROUTES (SEMUA MIDDLEWARE/KUNCI DIMATIKAN SEMENTARA) ---
// =====================================================================

// 1. VISITOR
Route::prefix('visitor')->name('visitor.')->group(function () {
    Route::get('/profile', [VisitorController::class, 'profile'])->name('profile');
});

// 2. TENANT
Route::prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', function () {
        return view('tenant.dashboard'); 
    })->name('dashboard');

    Route::get('/maintenance', [MaintenanceTicketController::class, 'index'])->name('maintenance');
    Route::post('/maintenance', [MaintenanceTicketController::class, 'store'])->name('maintenance.store');
});

// 3. ADMIN CABANG
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('dashboard');
});

// 4. SUPER ADMIN
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('superadmin.dashboard'); 
    })->name('dashboard');
});


// --- TRANSAKSI ROUTES (MIDDLEWARE DIMATIKAN SEMENTARA) ---
// Route untuk melihat daftar transaksi
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

// Route untuk submit form tagihan bulanan + upload meteran (Oleh Admin)
Route::post('/transaksi/bulanan', [TransaksiController::class, 'storeBulanan'])->name('transaksi.bulanan');

// Route untuk submit form booking DP (Oleh Visitor)
Route::post('/transaksi/booking', [TransaksiController::class, 'storeBooking'])->name('transaksi.booking');

Route::get('/pembayaran/{id}', [PaymentController::class, 'checkout'])->name('pembayaran.checkout');


// =====================================================================
// ===== PREVIEW SUPERADMIN TANPA LOGIN (BAWAAN DAVID) =====
// =====================================================================
Route::prefix('test-superadmin')->name('superadmin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    Route::post('/cabang', function () {
        return redirect()->back()->with('success', 'Preview: Cabang berhasil ditambahkan');
    })->name('cabang.store');

    // Dummy edit cabang
    Route::put('/cabang/{id}', function ($id) {
        return redirect()->back()->with('success', 'Preview: Cabang berhasil diupdate');
    })->name('cabang.update');

    // Dummy hapus cabang
    Route::delete('/cabang/{id}', function ($id) {
        return redirect()->back()->with('success', 'Preview: Cabang berhasil dihapus');
    })->name('cabang.destroy');

    // ================= CABANG =================
    Route::get('/cabang', function () {
        $data = collect([
            (object)[
                'id' => 1,
                'nama' => 'PuluBoys Siwalankerto',
                'alamat' => 'Surabaya',
                'kamar_count' => 20,
                'kamar_kosong_count' => 5,
                'kamar_terisi_count' => 15,
            ]
        ]);

        $cabang = new LengthAwarePaginator($data, 1, 10);
        return view('superadmin.cabang.index', compact('cabang'));
    })->name('cabang.index');

    // ================= PENGHUNI =================
    Route::get('/penghuni', function () {
        $data = collect([
            (object)[
                'nama' => 'Willy',
                'no_wa' => '08123456789',
                'tanggal_selesaiSewa' => now()->addDays(10),
                'kamar' => (object)[
                    'nomor' => 'A01',
                    'kos' => (object)[
                        'nama' => 'PuluBoys Siwalankerto'
                    ]
                ]
            ]
        ]);

        $penghuni = new LengthAwarePaginator($data, 1, 10);
        return view('superadmin.penghuni.index', compact('penghuni'));
    })->name('penghuni.index');

    // ================= KEUANGAN =================
    Route::get('/keuangan', function () {
        $totalPendapatan = 50000000;
        $pendapatanBulanIni = 8500000;
        $totalTransaksi = 120;

        $transaksi = new LengthAwarePaginator(
            collect([
                (object)[
                    'id' => 1,
                    'created_at' => now(),
                    'type' => 'dp',
                    'total' => 500000,
                    'status_transaksi' => 'paid',
                    'user' => (object)[
                        'nama' => 'Budi'
                    ],
                    'kamar' => (object)[
                        'nomor' => 'A01',
                        'kos' => (object)[
                            'nama' => 'PuluBoys Siwalankerto'
                        ]
                    ]
                ]
            ]),
            1,
            10
        );

        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
        $bulanData = [5000000, 7000000, 6000000, 8000000, 9000000, 10000000];

        $pendapatanPerCabang = [
            (object)['nama' => 'Siwalankerto', 'total' => 25000000],
            (object)['nama' => 'Ketintang', 'total' => 15000000]
        ];

        return view('superadmin.keuangan.index', compact(
            'totalPendapatan', 'pendapatanBulanIni', 'totalTransaksi',
            'transaksi', 'bulanLabels', 'bulanData', 'pendapatanPerCabang'
        ));
    })->name('keuangan.index');
});
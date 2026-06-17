<?php

use Illuminate\Support\Facades\Route;
use App\Models\Kos;
use Illuminate\Http\Request;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\MaintenanceTicketController;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Transaksi;
use App\Http\Controllers\WebhookController;

Route::get('/', [VisitorController::class, 'index'])->name('home');
Route::get('/daftar-cabang', [VisitorController::class, 'branches'])->name('visitor.branches');

Route::post('/daftar-cabang/survey', [VisitorController::class, 'storeSurvey'])->name('visitor.survey.store');
Route::post('/daftar-cabang/booking', [VisitorController::class, 'storeBooking'])->name('visitor.booking.store');

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
        
       $transaksi = Transaksi::where('type', 'Bulanan')->latest()->first();

        return view('tenant.dashboard', compact('transaksi'));
    })->name('dashboard');

    // 👇 Rute invoice ditambahkan di sini agar sidebar milik David tidak error 👇
    Route::get('/invoice', function () {

       $transaksi = Transaksi::where('type', 'Bulanan')->latest()->first();
        return view('tenant.invoice', compact('transaksi'));

    })->name('invoice');

    Route::get('/maintenance', [MaintenanceTicketController::class, 'index'])->name('maintenance');
    Route::post('/maintenance', [MaintenanceTicketController::class, 'store'])->name('maintenance.store');
});

// 3. ADMIN CABANG
Route::prefix('admin')->name('admin.')->group(function () {
    
    // 1. Dashboard / Ringkasan
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 2. Manajemen Kamar & Meteran
    Route::get('/kamar', function () {
        return view('admin.kamar');
    })->name('kamar');

    // 3. Survey & Check-In
    Route::get('/survey', function () {
        return view('admin.survey');
    })->name('survey');

    // 4. Komplain / Maintenance
    Route::get('/komplain', function () {
        return view('admin.komplain');
    })->name('komplain');
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

    // tambah cabang baru
    Route::post('/cabang', function (Request $request) {
        // 1. Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        // 2. Simpan ke database
        Kos::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
        ]);

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Cabang baru berhasil ditambahkan ke dalam sistem.');
    })->name('cabang.store');

    // menampilkan daftar cabang
    Route::get('/cabang', function () {
        
        $cabang = Kos::withCount([
            'kamars',
            
            'kamars as kamar_kosong_count' => function ($query) {
                $query->where('status', 'Kosong');
            },
            
            'kamars as kamar_terisi_count' => function ($query) {
                $query->whereIn('status', ['Terisi', 'Booking']); 
            }
        ])
        ->orderBy('created_at', 'desc') // Mengurutkan dari yang terbaru
        ->paginate(10); // Menampilkan 10 data per halaman

        return view('superadmin.cabang.index', compact('cabang'));
        
    })->name('cabang.index');

    // edit cabang
    Route::put('/cabang/{id}', function ($id) {
        return redirect()->back()->with('success', 'Preview: Cabang berhasil diupdate');
    })->name('cabang.update');

    // hapus cabang
    Route::delete('/cabang/{id}', function ($id) {
        return redirect()->back()->with('success', 'Preview: Cabang berhasil dihapus');
    })->name('cabang.destroy');

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



    Route::get('/pengaturan', function () {
        return view('superadmin.pengaturan.index');
    })->name('pengaturan.index');

Route::post('/webhook/midtrans', [WebhookController::class, 'handlePayment'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    
});
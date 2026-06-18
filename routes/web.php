<?php

use Illuminate\Support\Facades\Route;
use App\Models\Kos;
use Illuminate\Http\Request;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\MaintenanceTicketController;
use App\Http\Controllers\KamarController;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Transaksi;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CabangController;
use App\Http\Controllers\SuperAdmin\PenghuniController;
use App\Http\Controllers\SuperAdmin\KeuanganController;

Route::get('/', [VisitorController::class, 'index'])->name('home');
Route::get('/daftar-cabang', [VisitorController::class, 'branches'])->name('visitor.branches');

Route::post('/daftar-cabang/survey', [VisitorController::class, 'storeSurvey'])->name('visitor.survey.store');
Route::post('/daftar-cabang/booking', [VisitorController::class, 'storeBooking'])->name('visitor.booking.store');

Route::get('/daftar-cabang/{id}', [VisitorController::class, 'show'])->name('visitor.branch.show');

// // --- AUTH ROUTES (DIBUKA SEMENTARA) ---
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
// 0-----------------------------------------------------



/// - NYALAIN INI DULU KALAU MAU NYOBA LOGIN/REGISTER, SUPERADMIN. DAN JANGAN LUPA MATIKAN,
///KODE LINE 28 - 48 DAN 144 -150, SERTA KODE YANG ADA TULISAN BAWAAN DAVID!!!
//--- AUTH ROUTES ---
// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
// Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// // =====================================================================
// // --- RUTE OPERASIONAL SUPER ADMIN (TERHUBUNG KE DATABASE) ---
// // =====================================================================
// Route::prefix('superadmin')->name('superadmin.')->group(function () {
    
//     // Dasbor Utama
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::post('/survey/{id}/status', [DashboardController::class, 'updateSurveyStatus'])->name('survey.status');

//     Route::post('/pengumuman', [DashboardController::class, 'storePengumuman'])->name('pengumuman.store');

//     // Manajemen Cabang (CRUD Resmi)
//     Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');
//     Route::post('/cabang', [CabangController::class, 'store'])->name('cabang.store');
//     Route::put('/cabang/{cabang}', [CabangController::class, 'update'])->name('cabang.update');
//     Route::delete('/cabang/{cabang}', [CabangController::class, 'destroy'])->name('cabang.destroy');

//     // Data Penghuni
//     Route::get('/penghuni', [PenghuniController::class, 'index'])->name('penghuni.index');
//     Route::get('/penghuni/{id}', [PenghuniController::class, 'show'])->name('penghuni.show');

//     // Laporan Keuangan
//     Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');

//     // Pengaturan Global
//     Route::get('/pengaturan', function () {
//         return view('superadmin.pengaturan.index');
//     })->name('pengaturan.index');


// });
//---------------------------------------------------------------------------------------------------




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
    Route::get('/dashboard', [KamarController::class, 'index'])->name('dashboard');

    // 2. Manajemen Kamar & Meteran
    Route::get('/kamar', [KamarController::class, 'kamar'])->name('kamar');

    // 3. Survey & Check-In
    Route::get('/survey', [KamarController::class, 'survey'])->name('survey');

    // 4. Komplain / Maintenance
    Route::get('/komplain', [KamarController::class, 'komplain'])->name('komplain');

    Route::post('/kamar/meteran', [KamarController::class, 'storeMeteran'])->name('kamar.meteran');
    Route::post('/kamar/maintenance', [KamarController::class, 'updateMaintenance'])->name('kamar.maintenance');
    Route::post('/kamar/kosong', [KamarController::class, 'markAsKosong'])->name('kamar.kosong');
});


//BACAAAA
//Kalau  mau nyoba super admin matiin matiin dulu yang bawah ini, trs baru login / daftar akun dulu
// // 4. SUPER ADMIN
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

// --- TRANSAKSI & WEBHOOK GLOBAL ---
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi/bulanan', [TransaksiController::class, 'storeBulanan'])->name('transaksi.bulanan');
Route::post('/transaksi/booking', [TransaksiController::class, 'storeBooking'])->name('transaksi.booking');
Route::get('/pembayaran/{id}', [PaymentController::class, 'checkout'])->name('pembayaran.checkout');

Route::post('/webhook/midtrans', [WebhookController::class, 'handlePayment'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    
});
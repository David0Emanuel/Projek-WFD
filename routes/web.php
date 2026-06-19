<?php

use Illuminate\Support\Facades\Route;

// --- Controllers ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\MaintenanceTicketController;
use App\Http\Controllers\KamarController;

// --- Super Admin Controllers ---
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CabangController;
use App\Http\Controllers\SuperAdmin\PenghuniController;
use App\Http\Controllers\SuperAdmin\KeuanganController;

// Models (Hanya untuk keperluan rute Tenant sementara)
use App\Models\Transaksi;


// =====================================================================
// 1. VISITOR ROUTES (PUBLIC)
// =====================================================================
Route::get('/', [VisitorController::class, 'index'])->name('home');
Route::get('/daftar-cabang', [VisitorController::class, 'branches'])->name('visitor.branches');
Route::post('/daftar-cabang/survey', [VisitorController::class, 'storeSurvey'])->name('visitor.survey.store');
Route::post('/daftar-cabang/booking', [VisitorController::class, 'storeBooking'])->name('visitor.booking.store');
Route::get('/daftar-cabang/{id}', [VisitorController::class, 'show'])->name('visitor.branch.show');

Route::prefix('visitor')->name('visitor.')->group(function () {
    Route::get('/profile', [VisitorController::class, 'profile'])->name('profile');
});


// =====================================================================
// 2. AUTHENTICATION ROUTES (TERHUBUNG DATABASE)
// =====================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =====================================================================
// 3. SUPER ADMIN ROUTES (TANPA MIDDLEWARE SEMENTARA)
// =====================================================================
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    
    // Dasbor Utama & Pengumuman
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/survey/{id}/status', [DashboardController::class, 'updateSurveyStatus'])->name('survey.status');
    Route::post('/pengumuman', [DashboardController::class, 'storePengumuman'])->name('pengumuman.store');

    // Manajemen Cabang (CRUD Resmi)
    Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');
    Route::post('/cabang', [CabangController::class, 'store'])->name('cabang.store');
    Route::put('/cabang/{cabang}', [CabangController::class, 'update'])->name('cabang.update');
    Route::delete('/cabang/{cabang}', [CabangController::class, 'destroy'])->name('cabang.destroy');

    // Data Penghuni
    Route::get('/penghuni', [PenghuniController::class, 'index'])->name('penghuni.index');
    Route::get('/penghuni/{id}', [PenghuniController::class, 'show'])->name('penghuni.show');

    // Laporan Keuangan
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');

    // Pengaturan Global
    Route::get('/pengaturan', function () {
        return view('superadmin.pengaturan.index');
    })->name('pengaturan.index');
});


// =====================================================================
// 4. ADMIN CABANG ROUTES (TANPA MIDDLEWARE SEMENTARA)
// =====================================================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [KamarController::class, 'index'])->name('dashboard');
    Route::get('/kamar', [KamarController::class, 'kamar'])->name('kamar');
    Route::get('/survey', [KamarController::class, 'survey'])->name('survey');
    Route::get('/komplain', [KamarController::class, 'komplain'])->name('komplain');

    Route::post('/kamar/meteran', [KamarController::class, 'storeMeteran'])->name('kamar.meteran');
    Route::post('/kamar/maintenance', [KamarController::class, 'updateMaintenance'])->name('kamar.maintenance');
    Route::post('/kamar/kosong', [KamarController::class, 'markAsKosong'])->name('kamar.kosong');
    Route::post('/kamar/checkin', [KamarController::class, 'checkin'])->name('kamar.checkin');
});


// =====================================================================
// 5. TENANT ROUTES (TANPA MIDDLEWARE SEMENTARA)
// =====================================================================
Route::prefix('tenant')->name('tenant.')->group(function () {
    // Rute Dashboard Utama Tenant
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // 1. Ambil 1 tagihan terbaru yang belum dibayar
        $transaksi = \App\Models\Transaksi::where('user_id', $user->id)
                        ->where('status_transaksi', 'Unpaid')
                        ->latest()
                        ->first();
        
        // 2. Cek lokasi cabang (kos_id) tenant saat ini
        $kosId = $user->kamar ? $user->kamar->kos_id : $user->kos_id;

        // 3. Ambil pengumuman (Gabungan Pengumuman Global & Pengumuman Cabang)
        $pengumumans = \App\Models\Pengumuman::whereNull('kos_id')
                        ->when($kosId, function($query) use ($kosId) {
                            $query->orWhere('kos_id', $kosId);
                        })
                        ->latest()
                        ->take(5) // Ambil 5 pengumuman terbaru
                        ->get();
        
        return view('tenant.dashboard', compact('transaksi', 'pengumumans'));
    })->name('dashboard');

    // Rute Halaman Invoice & Riwayat Tagihan
    Route::get('/invoice', function () {
        // Ambil Tagihan Aktif (Belum Lunas) untuk kolom kanan
        $tagihan_aktif = Transaksi::where('user_id', auth()->id())
                            ->where('status_transaksi', 'Unpaid')
                            ->latest()
                            ->first();

        // Ambil SELURUH riwayat transaksi (Lunas & Belum Lunas) untuk tabel kiri
        $riwayat_transaksi = Transaksi::where('user_id', auth()->id())
                            ->latest()
                            ->get();

        return view('tenant.invoice', compact('tagihan_aktif', 'riwayat_transaksi'));
    })->name('invoice');

    Route::get('/maintenance', [App\Http\Controllers\MaintenanceTicketController::class, 'index'])->name('maintenance');
    Route::post('/maintenance', [App\Http\Controllers\MaintenanceTicketController::class, 'store'])->name('maintenance.store');
});


// =====================================================================
// 6. TRANSAKSI & WEBHOOK GLOBAL
// =====================================================================
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi/bulanan', [TransaksiController::class, 'storeBulanan'])->name('transaksi.bulanan');
Route::post('/transaksi/booking', [TransaksiController::class, 'storeBooking'])->name('transaksi.booking');
Route::get('/pembayaran/{id}', [PaymentController::class, 'checkout'])->name('pembayaran.checkout');

// Webhook untuk Midtrans Payment Gateway
Route::post('/webhook/midtrans', [WebhookController::class, 'handlePayment'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
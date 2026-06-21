<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\MaintenanceTicketController;
use App\Http\Controllers\KamarController;

use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CabangController;
use App\Http\Controllers\SuperAdmin\PenghuniController;
use App\Http\Controllers\SuperAdmin\KeuanganController;

use App\Models\Transaksi;


// 1. VISITOR
Route::get('/', [VisitorController::class, 'index'])->name('home');
Route::get('/daftar-cabang', [VisitorController::class, 'branches'])->name('visitor.branches');
Route::post('/daftar-cabang/survey', [VisitorController::class, 'storeSurvey'])->name('visitor.survey.store');
Route::post('/daftar-cabang/booking', [VisitorController::class, 'storeBooking'])->name('visitor.booking.store');
Route::get('/daftar-cabang/{id}', [VisitorController::class, 'show'])->name('visitor.branch.show');

// Rute khusus Visitor yang sudah login
Route::middleware(['auth', 'role:visitor'])->prefix('visitor')->name('visitor.')->group(function () {
    Route::get('/profile', [VisitorController::class, 'profile'])->name('profile');
});


// 2. AUTHENTICATION (TERHUBUNG DATABASE)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// 3. SUPER ADMIN (MIDDLEWARE)
Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    
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

    // Rute Notifikasi Pembayaran & Survey
    Route::post('/notifications/mark-as-read', function() {
        \App\Models\AdminLog::whereIn('tipe', ['pembayaran', 'survey'])
                            ->where('is_read', false)
                            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    })->name('notifications.clear'); 

    Route::get('/log-aktivitas', function() {
        $logs = \App\Models\AdminLog::where('tipe', 'login')
                                    ->latest()
                                    ->take(15)
                                    ->get()
                                    ->map(function($log) {
                                        return [
                                            'pesan' => $log->pesan,
                                            'waktu' => $log->created_at->diffForHumans()
                                        ];
                                    });
        return response()->json($logs);
    })->name('log-aktivitas'); 

});


// 4. ADMIN CABANG(MIDDLEWARE)
Route::middleware(['auth', 'role:admin_cabang'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [KamarController::class, 'index'])->name('dashboard');
    Route::get('/kamar', [KamarController::class, 'kamar'])->name('kamar');
    Route::get('/survey', [KamarController::class, 'survey'])->name('survey');
    Route::get('/komplain', [KamarController::class, 'komplain'])->name('komplain');

    Route::post('/kamar/meteran', [KamarController::class, 'storeMeteran'])->name('kamar.meteran');
    Route::post('/kamar/maintenance', [KamarController::class, 'updateMaintenance'])->name('kamar.maintenance');
    Route::post('/kamar/kosong', [KamarController::class, 'markAsKosong'])->name('kamar.kosong');
    Route::post('/kamar/checkin', [KamarController::class, 'checkin'])->name('kamar.checkin');
    Route::post('/kamar/update-detail', [\App\Http\Controllers\KamarController::class, 'updateDetail'])->name('kamar.update-detail');
    Route::post('/komplain/update', [KamarController::class, 'updateStatusKomplain'])->name('komplain.update');
    Route::post('/survey/approve/{id}', [KamarController::class, 'approveSurvey'])->name('survey.approve');
    Route::post('/survey/complete/{id}', [KamarController::class, 'completeSurvey'])->name('survey.complete');
});


// 5. TENANT (MIDDLEWARE)
Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    // Rute Dashboard Utama Tenant
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // 1. Ambil SEMUA tagihan yang belum dibayar
        $tagihans = \App\Models\Transaksi::where('user_id', $user->id)
                        ->where('status_transaksi', 'Unpaid')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // 2. Cek lokasi cabang (kos_id) tenant saat ini
        $kosId = $user->kamar ? $user->kamar->kos_id : $user->kos_id;

        // 3. Ambil pengumuman (Gabungan Pengumuman Global & Pengumuman Cabang)
        $pengumumans = \App\Models\Pengumuman::whereNull('kos_id')
                        ->when($kosId, function($query) use ($kosId) {
                            $query->orWhere('kos_id', $kosId);
                        })
                        ->latest()
                        ->take(5)
                        ->get();
        
        return view('tenant.dashboard', compact('tagihans', 'pengumumans', 'user'));
    })->name('dashboard');

    // Rute Halaman Invoice & Riwayat Tagihan
    Route::get('/invoice', function () {
        $tagihan_aktif = Transaksi::where('user_id', auth()->id())
                            ->where('status_transaksi', 'Unpaid')
                            ->latest()
                            ->first();

        $riwayat_transaksi = Transaksi::where('user_id', auth()->id())
                            ->latest()
                            ->get();

        return view('tenant.invoice', compact('tagihan_aktif', 'riwayat_transaksi'));
    })->name('invoice');

    Route::get('/maintenance', [App\Http\Controllers\MaintenanceTicketController::class, 'index'])->name('maintenance');
    Route::post('/maintenance', [App\Http\Controllers\MaintenanceTicketController::class, 'store'])->name('maintenance.store');
    Route::delete('/maintenance/{id}', [App\Http\Controllers\MaintenanceTicketController::class, 'delete'])->name('maintenance.delete');
    
    // Rute Request Keluar (Sudah dimasukkan ke dalam grup tenant)
    Route::post('/request-keluar', [\App\Http\Controllers\DashboardController::class, 'requestKeluar'])->name('request-keluar');
});


// 6. TRANSAKSI & WEBHOOK GLOBAL
// Rute Transaksi (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/bulanan', [TransaksiController::class, 'storeBulanan'])->name('transaksi.bulanan');
    Route::post('/transaksi/booking', [TransaksiController::class, 'storeBooking'])->name('transaksi.booking');
    //pintu masuk/endpoint yg memicu PaymentController yang sudah saya jelaskan sebelumnya.
    Route::get('/pembayaran/{id}', [PaymentController::class, 'checkout'])->name('pembayaran.checkout');
});

// Webhook untuk Midtrans Payment Gateway (TETAP PUBLIC)
Route::post('/webhook/midtrans', [WebhookController::class, 'handlePayment'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
//karena laravel itu otomati ngcek csrf token dri pihak luar
//sedangkan request pembayaran kan dri pihak luar / server midtrans,dimana ga ada token csrf
//kalo ga pakai withoutmiddleware, request dari midtrans bakal ditolak sama laravel.

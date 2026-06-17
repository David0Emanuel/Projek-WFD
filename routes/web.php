<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\MaintenanceTicketController;

Route::get('/', [VisitorController::class, 'index'])->name('home');
Route::get('/daftar-cabang', [VisitorController::class, 'branches'])->name('visitor.branches');
Route::get('/daftar-cabang/{id}', [VisitorController::class, 'show'])->name('visitor.branch.show');

// --- AUTH ROUTES ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- PROTECTED ROUTES BERDASARKAN ROLE ---
Route::middleware(['auth'])->group(function () {

    // 1. VISITOR
    Route::middleware(['role:visitor'])->prefix('visitor')->name('visitor.')->group(function () {
        Route::get('/profile', [VisitorController::class, 'profile'])->name('profile');
    });

    // 2. TENANT
    Route::middleware(['role:tenant'])->prefix('tenant')->name('tenant.')->group(function () {
        // Ini menyambung ke view yang sudah David buat di screenshot
        Route::get('/dashboard', function () {
            return view('tenant.dashboard'); 
        })->name('dashboard');

        Route::get('/maintenance', [MaintenanceTicketController::class, 'index'])->name('maintenance');
        Route::post('/maintenance', [MaintenanceTicketController::class, 'store'])->name('maintenance.store');
    });

    // 3. ADMIN CABANG
    Route::middleware(['role:admin_cabang'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // Minta David buatkan view ini
        })->name('dashboard');
    });

    // 4. SUPER ADMIN
    Route::middleware(['role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('superadmin.dashboard'); // Minta David buatkan view this
        })->name('dashboard');
    });

});

// --- TRANSAKSI ROUTES ---
// Route untuk melihat daftar transaksi
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

// Route untuk submit form tagihan bulanan + upload meteran (Oleh Admin)
Route::post('/transaksi/bulanan', [TransaksiController::class, 'storeBulanan'])->name('transaksi.bulanan');

// Route untuk submit form booking DP (Oleh Visitor)
Route::post('/transaksi/booking', [TransaksiController::class, 'storeBooking'])->name('transaksi.booking')->middleware('auth');

Route::get('/pembayaran/{id}', [PaymentController::class, 'checkout'])->name('pembayaran.checkout')->middleware('auth');


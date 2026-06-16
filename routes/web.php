<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\MaintenanceTicketController;

// Tenant Dashboard Route
Route::get('/tenant/dashboard', function () {
    return view('tenant.dashboard');
})->name('tenant.dashboard');

// Tenant Invoice & Tagihan Route
Route::get('/tenant/invoice', function () {
    return view('tenant.invoice');
})->name('tenant.invoice');

// Tenant Maintenance Ticket Routes
Route::get('/tenant/maintenance', [MaintenanceTicketController::class, 'index'])->name('tenant.maintenance');
Route::post('/tenant/maintenance', [MaintenanceTicketController::class, 'store'])->name('tenant.maintenance.store');


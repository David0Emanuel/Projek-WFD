<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\WebhookController;

// URL yang akan dipanggil oleh Midtrans/Payment Gateway: https://domain-anda.com/api/payment/webhook
Route::post('/payment/webhook', [WebhookController::class, 'handlePayment']);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// URL yang akan dipanggil oleh Midtrans/Payment Gateway: https://domain-anda.com/api/midtrans-notification
Route::post('/midtrans-notification', [WebhookController::class, 'handlePayment']);
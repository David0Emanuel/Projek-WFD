<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handlePayment(Request $request)
    {
        // 1. Ambil data dari payload webhook
        $payload = $request->all();
        Log::info('Webhook Payload Diterima:', $payload); // Simpan ke log untuk debugging

        $orderId = $payload['order_id']; // Sesuai dengan id transaksi di database
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $signatureKey = $payload['signature_key'];
        $transactionStatus = $payload['transaction_status'];

        // 2. Verifikasi Signature Key (KEAMANAN SANGAT PENTING)
        // Rumus Midtrans: hash SHA512(order_id + status_code + gross_amount + server_key)
        $serverKey = env('MIDTRANS_SERVER_KEY'); // Pastikan Anda set ini di file .env
        $mySignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $mySignature) {
            // Jika signature tidak cocok, tolak request (kemungkinan hacker/request palsu)
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // 3. Cari Data Transaksi di Database
        $transaksi = Transaksi::find($orderId);
        
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Jika transaksi sudah lunas sebelumnya, abaikan saja untuk menghindari double action
        if ($transaksi->status_transaksi === 'Paid') {
            return response()->json(['message' => 'Transaksi sudah lunas sebelumnya'], 200);
        }

        // 4. Logika Update Status berdasarkan Response Payment Gateway
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            // --- PEMBAYARAN BERHASIL (LUNAS) ---
            $transaksi->update(['status_transaksi' => 'Paid']);
            
            // Catatan: Menurut proposal, visitor akan menjadi tenant setelah check-in & dikonfirmasi admin,
            // jadi saat lunas DP, biarkan status kamar tetap 'Booking'.
            
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            // --- PEMBAYARAN GAGAL / KEDALUWARSA ---
            $transaksi->update(['status_transaksi' => 'Expired']);
            
            // Jika ini adalah DP Booking, kembalikan status kamar menjadi 'Kosong' kembali
            if ($transaksi->type === 'DP Booking') {
                $kamar = Kamar::find($transaksi->kamar_id);
                if ($kamar) {
                    $kamar->update(['status' => 'Kosong']);
                }
            }
            
        } elseif ($transactionStatus == 'pending') {
            // --- MENUNGGU PEMBAYARAN ---
            $transaksi->update(['status_transaksi' => 'Unpaid']);
        }

        // Beri respon 200 OK agar payment gateway tahu webhook berhasil diterima
        return response()->json(['message' => 'Webhook berhasil diproses']);
    }
}
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
        // 1. Ambil payload dari Midtrans
        $payload = $request->all();
        $orderIdLengkap = $payload['order_id']; // Berisi format 'INV-1'
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $signatureKey = $payload['signature_key'];
        $transactionStatus = $payload['transaction_status'];

        Log::info("Webhook masuk untuk Order: {$orderIdLengkap}. Status: {$transactionStatus}");

        // 2. Verifikasi Signature Key (Keamanan)
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $mySignature = hash('sha512', $orderIdLengkap . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $mySignature) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // 3. Buang teks 'INV-' untuk mendapatkan ID angka asli ke tabel transaksi
        $transaksiId = str_replace('INV-', '', $orderIdLengkap);
        
        // Cari Data Transaksi di Database
        $transaksi = Transaksi::find($transaksiId);
        
        if (!$transaksi) {
            Log::error("Transaksi dengan ID Asli {$transaksiId} tidak ditemukan!");
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Jika transaksi sudah lunas sebelumnya, abaikan (mencegah double-update)
        if ($transaksi->status_transaksi === 'Paid') {
            return response()->json(['message' => 'Transaksi sudah lunas sebelumnya'], 200);
        }

        // 4. Logika Update Status sesuai hasil Midtrans
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            // PEMBAYARAN BERHASIL LUNAS
            $transaksi->update(['status_transaksi' => 'Paid']);
            Log::info("Transaksi {$transaksiId} BERHASIL DIBAYAR.");
            
            // TODO: Sisipkan fungsi FonnteService di sini untuk WA otomatis
            
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            // PEMBAYARAN GAGAL/KADALUARSA
            $transaksi->update(['status_transaksi' => 'Expired']);
            Log::info("Transaksi {$transaksiId} GAGAL/KADALUARSA.");
            
            // Jika ini DP Booking yang hangus, bebaskan kembali kamarnya
            if ($transaksi->type === 'DP Booking') {
                $kamar = Kamar::find($transaksi->kamar_id);
                if ($kamar) {
                    $kamar->update(['status' => 'Kosong']);
                    Log::info("Kamar ID {$transaksi->kamar_id} kembali Kosong.");
                }
            }
        } elseif ($transactionStatus == 'pending') {
            // MENUNGGU PEMBAYARAN
            $transaksi->update(['status_transaksi' => 'Unpaid']);
        }

        return response()->json(['message' => 'Webhook berhasil diproses']);
    }
}
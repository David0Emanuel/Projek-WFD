<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use App\Models\Kamar;

class WebhookController extends Controller
{
    public function handlePayment(Request $request)
    {
        // 1. Ambil payload dari request Midtrans
        $payload = $request->all();
        $orderIdLengkap = $payload['order_id'] ?? ''; 
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';

        Log::info("Webhook masuk untuk Order: {$orderIdLengkap}. Status: {$transactionStatus}");

        // 2. Verifikasi Keamanan Signature
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $mySignature = hash('sha512', $orderIdLengkap . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $mySignature) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // 3. Buang awalan 'INV-' agar mendapatkan ID murni
        $transaksiId = str_replace('INV-', '', $orderIdLengkap);
        
        $transaksi = Transaksi::find($transaksiId);
        
        if (!$transaksi) {
            Log::error("Transaksi {$transaksiId} tidak ditemukan!");
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Jika transaksi sudah lunas sebelumnya, abaikan (mencegah double-update)
        if ($transaksi->status_transaksi === 'Paid') {
            return response()->json(['message' => 'Transaksi sudah lunas sebelumnya'], 200);
        }

        // 4. Eksekusi Perubahan Database
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            
            // Mengubah status_transaksi menjadi Paid
            $transaksi->update(['status_transaksi' => 'Paid']);
            Log::info("Transaksi {$transaksiId} BERHASIL DIBAYAR.");
            
            // TODO: Sisipkan fungsi FonnteService di sini untuk mengirim nota ke WA
            
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            
            // Mengubah status_transaksi menjadi Expired
            $transaksi->update(['status_transaksi' => 'Expired']);
            Log::info("Transaksi {$transaksiId} GAGAL/KADALUARSA.");
            
            // Jika ini DP Booking yang hangus, ubah status kamar menjadi 'Kosong' kembali
            if ($transaksi->type === 'DP Booking') {
                $kamar = Kamar::find($transaksi->kamar_id);
                if ($kamar) {
                    $kamar->update(['status' => 'Kosong']);
                    Log::info("Kamar ID {$transaksi->kamar_id} kembali Kosong.");
                }
            }
            
        } elseif ($transactionStatus == 'pending') {
            $transaksi->update(['status_transaksi' => 'Unpaid']);
        }

        return response()->json(['message' => 'Webhook berhasil diproses']);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use App\Models\Kamar;
use Illuminate\Support\Facades\Http;

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

        // 3. Ambil ID murni dari format 'INV-1-1718608476'
        $pecah = explode('-', $orderIdLengkap);// Memecah string berdasarkan tanda strip '-'
        $transaksiId = $pecah[1];// Mengambil bagian tengah, yaitu angka '1'
        
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


            // LOGIKA KHUSUS JIKA INI ADALAH PEMBAYARAN DP BOOKING
            if (strtoupper($transaksi->type) == 'DP') {
        
                // Ubah role user dari 'visitor' menjadi 'tenant' sesuai instruksi UI
                $transaksi->user->update([
                            'role' => 'tenant',
                            'kamar_id' => $transaksi->kamar_id,
                            'kos_id' => Kamar::find($transaksi->kamar_id)->kos_id ?? null, 
                            'tanggal_mulaiSewa' => now()->toDateString(),
                        ]);
        
                // Ubah status kamar menjadi 'Terisi'
                $transaksi->kamar->update(['status' => 'Terisi']);
            }

            // Panggil fungsi WhatsApp Fonnte
            $this->sendWhatsAppNotification($transaksi);
            
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

    private function sendWhatsAppNotification($transaksi){
        $token = env('FONNTE_TOKEN');
        $noWa = $transaksi->user->no_wa;
        // Mengganti angka 0 di depan dengan 62
        $target = (substr($noWa, 0, 1) == '0') ? '62' . substr($noWa, 1) : $noWa; // Pastikan nomor WA user tersimpan di DB

        // Teks dinamis: Beda tipe tagihan, beda teks WA-nya!
        if (strtoupper($transaksi->type) == 'DP') {
            $message = "Selamat! Pembayaran DP Booking kamar Anda sebesar Rp " . number_format($transaksi->total, 0, ',', '.') . " BERHASIL. Akun Anda kini resmi ditingkatkan menjadi Tenant di KosInAja.";
        } else {
            $message = "Halo {$transaksi->user->nama}, pembayaran Anda untuk tagihan {$transaksi->type} sebesar Rp " . number_format($transaksi->total, 0, ',', '.') . " telah BERHASIL diterima. Terima kasih!";
        }


        // Http::withOptions([
        //     'curl' => [
        //         CURLOPT_CAINFO => 'C:/laragon/etc/ssl/cacert.pem',
        //     ]
        // ])->withHeaders([
        //     'Authorization' => $token
        // ])->post('https://api.fonnte.com/send', [
        //     'target' => $target,
        //     'message' => $message,
        // ]);



        Http::withHeaders([
    'Authorization' => env('FONNTE_TOKEN')
])->post('https://api.fonnte.com/send', [
    'target' => $target,
    'message' => $message,
]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService;
use App\Models\Transaksi; // Import model transaksi

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    //  menerima ID Transaksi
    public function checkout($id)
    {
        // 1. Ambil data asli dari database beserta data penyewanya (relasi user)
        $transaksi = Transaksi::with('user')->findOrFail($id);

        // 2. Siapkan variabel untuk Midtrans
        // Gunakan prefix 'INV-' digabung dengan ID transaksi asli agar unik
        $orderId = 'INV-' . $transaksi->id; 
        $totalBayar = $transaksi->total; // Mengambil kolom total dari tabel transaksi
        $typePembayaran = $transaksi->type; // Bernilai 'Bulanan' atau 'DP Booking'

        // Mengambil data dari tabel users
        $customerDetails = [
            'nama'  => $transaksi->user->nama,
            'email' => $transaksi->user->email,
            'no_wa' => $transaksi->user->no_wa,
        ];

        // 3. Panggil fungsi service Midtrans
        $snapToken = $this->midtransService->createSnapToken(
            $orderId, 
            $totalBayar, 
            $customerDetails, 
            $typePembayaran
        );

        // 4. Arahkan ke halaman UI pembayaran buatan David
        return view('tenant.pembayaran', compact('snapToken', 'totalBayar', 'orderId', 'transaksi'));
    }
}
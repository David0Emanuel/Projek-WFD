<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService; 
use App\Models\Transaksi; 

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function checkout($id)
    {
        // 1. Ambil data transaksi beserta data penyewa (relasi user)
        $transaksi = Transaksi::with('user')->findOrFail($id);

        // 2. Siapkan data untuk dikirim ke Midtrans
        $orderId = 'INV-' . $transaksi->id . '-' . time();// tambah waktu agar selalu unik
        $totalBayar = $transaksi->total; // Menggunakan kolom total
        $typePembayaran = $transaksi->type; // Bernilai 'DP Booking' atau 'Bulanan'

        // Mengambil data spesifik dari model User
        $customerDetails = [
            'nama'  => $transaksi->user->nama,
            'email' => $transaksi->user->email,
            'no_wa' => $transaksi->user->no_wa,
        ];

        // 3. Panggil service Midtrans untuk menghasilkan token
        $snapToken = $this->midtransService->createSnapToken(
            $orderId, 
            $totalBayar, 
            $customerDetails, 
            $typePembayaran
        );

        // 4. Arahkan ke halaman UI (Tugas David)
        return view('components.tenant.pembayaran', compact('snapToken', 'totalBayar', 'orderId', 'transaksi'));
    }
}
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
        //  Ambil data transaksi beserta data penyewa  dalam satu query 
        $transaksi = Transaksi::with('user')->findOrFail($id);

        // Siapin data untuk dikirim ke Midtrans
        $orderId = 'INV-' . $transaksi->id . '-' . time();// tambah waktu agar selalu unik
        $totalBayar = $transaksi->total; // Menggunakan kolom total
        $typePembayaran = $transaksi->type; // Bernilai 'DP Booking' atau 'Bulanan'

        // mengambil data spesifik dari model User
        $customerDetails = [
            'nama'  => $transaksi->user->nama,
            'email' => $transaksi->user->email,
            'no_wa' => $transaksi->user->no_wa,
        ];

        // Panggil service Midtrans untuk menghasilkan token
        //controller hanya perlu panggil service tersebut dan service yg bakal mengembalikan snap token
        $snapToken = $this->midtransService->createSnapToken(
            $orderId, 
            $totalBayar, 
            $customerDetails, 
            $typePembayaran
        );

        // Arahkan ke halaman UI 
        if (strtoupper($transaksi->type) == 'DP') {
            // Jika ini pembayaran DP Booking, buka layout Visitor
            return view('visitor.pembayaran', compact('snapToken', 'totalBayar', 'orderId', 'transaksi'));
        } else {
            // Jika ini pembayaran Bulanan biasa, buka layout Tenant
            return view('components.tenant.pembayaran', compact('snapToken', 'totalBayar', 'orderId', 'transaksi'));
        }
       
    }
}
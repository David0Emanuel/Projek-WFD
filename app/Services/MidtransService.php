<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Konfigurasi dasar Midtrans mengambil dari .env
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        //array kosong untuk menambal bug PHP 8
        $curlOptions = [
            CURLOPT_HTTPHEADER => [], 
        ];


        // kalo aplikasi berjalan di local (seperti Laragon), matikan SSL.
        // kalo jalan  hosting, nyalakan SSL.
        if (app()->environment('local')) {
            $curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
            $curlOptions[CURLOPT_SSL_VERIFYPEER] = 0;
        }

        \Midtrans\Config::$curlOptions = $curlOptions;
    }

    /**
     * Membuat Snap Token untuk pembayaran
     * * @param string $orderId ID unik transaksi (misal: INV-20260616-001)
     * @param int $grossAmount Total nominal pembayaran
     * @param array $customerDetails Data penyewa (nama, email, no_wa)
     * @param string $paymentType Jenis pembayaran ('dp' atau 'bulanan')
     * @return string
     */
    public function createSnapToken($orderId, $grossAmount, $customerDetails, $paymentType = 'bulanan')
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $customerDetails['nama'],
                'email' => $customerDetails['email'],
                'phone' => $customerDetails['no_wa'],
            ],
        ];

        // Sesuai proposal: Jika jenisnya DP (Booking), beri countdown timer 2 jam
        if ($paymentType === 'dp') {
            $params['custom_expiry'] = [
                'expiry_duration' => 2,
                'unit' => 'hour'
            ];
        }

        // Generate dan kembalikan snap token
        return Snap::getSnapToken($params);
    }
}
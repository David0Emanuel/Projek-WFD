<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Kamar;
use Carbon\Carbon;

class GenerateTagihanSewa extends Command
{
    // Nama perintah untuk dijalankan di terminal
    protected $signature = 'tagihan:generate-sewa';
    protected $description = 'Generate tagihan sewa bulanan otomatis untuk tenant sesuai tanggal masuk';

    public function handle()
    {
        $hariIni = Carbon::now()->startOfDay();

        // Cari semua user dengan role 'tenant' yang punya kamar_id
        $tenants = User::where('role', 'tenant')->whereNotNull('kamar_id')->get();

        foreach ($tenants as $tenant) {
            $tglMulaiSewa = Carbon::parse($tenant->tanggal_mulaiSewa);

            // Jika tanggal hari ini SAMA dengan tanggal mulai sewa (misal sama-sama tgl 26)
            if ($hariIni->day == $tglMulaiSewa->day) {
                
                $kamar = Kamar::find($tenant->kamar_id);
                
                // Pastikan kamar ada, lalu buat tagihan otomatis
                if ($kamar) {
                    Transaksi::create([
                        'user_id' => $tenant->id,
                        'kamar_id' => $kamar->id,
                        'total' => $kamar->harga, // Harga sewa kamar asli
                        'type' => 'Sewa Bulanan',
                        'status_transaksi' => 'Unpaid',
                        'angka_meteran' => null,
                        'foto_meteran' => null,
                        // Batas waktu bayar tepat 1 bulan ke depan
                        'expired_at' => $hariIni->copy()->addMonthNoOverflow()->endOfDay()
                    ]);
                    
                    $this->info("Tagihan sewa untuk {$tenant->nama} (Kamar {$kamar->nomor}) berhasil dibuat.");
                }
            }
        }
        $this->info('Proses generate tagihan selesai.');
    }
}
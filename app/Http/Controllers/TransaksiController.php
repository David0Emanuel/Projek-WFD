<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kamar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Menampilkan daftar transaksi/invoice (Bisa untuk Admin/Tenant)
     */
    public function index()
    {
        // Mengambil data transaksi beserta relasi user dan kamar
        $transaksis = Transaksi::with(['user', 'kamar'])->latest()->get();
        
        // return view('transaksi.index', compact('transaksis')); // Jika pakai blade
        return response()->json($transaksis); // Jika pakai API/JSON
    }

    /**
     * Logika untuk Admin men-generate Invoice Tagihan Bulanan & Upload Meteran
     */
    public function storeBulanan(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'kamar_id'      => 'required|exists:kamars,id',
            'total'         => 'required|numeric|min:0',
            'angka_meteran' => 'required|integer|min:0',
            'foto_meteran'  => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // 2. Logika Upload Foto Bukti Meteran
        $fotoPath = null;
        if ($request->hasFile('foto_meteran')) {
            // Simpan foto ke folder 'storage/app/public/bukti_meteran'
            // Jangan lupa jalankan: php artisan storage:link
            $file = $request->file('foto_meteran');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $fotoPath = $file->storeAs('bukti_meteran', $namaFile, 'public');
        }

        // 3. Simpan Data Transaksi ke Database
        $transaksi = Transaksi::create([
            'user_id'          => $request->user_id,
            'kamar_id'         => $request->kamar_id,
            'total'            => $request->total,
            'status_transaksi' => 'Unpaid', // Default status awal
            'type'             => 'Bulanan', // Tipe transaksi bulanan
            'angka_meteran'    => $request->angka_meteran,
            'foto_meteran'     => $fotoPath,
            'expired_at'       => Carbon::now()->addDays(7), // Tenggat waktu bayar 7 hari
        ]);

        // return redirect()->back()->with('success', 'Invoice bulanan berhasil dibuat!');
        return response()->json([
            'message' => 'Invoice bulanan berhasil di-generate!',
            'data'    => $transaksi
        ], 201);
    }

    /**
     * Logika untuk Visitor melakukan Booking DP (Tanpa Meteran)
     */
    public function storeBooking(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'total'    => 'required|numeric|min:0',
        ]);

        $user = auth()->user();

        // Cek apakah kamar masih kosong
        $kamar = Kamar::findOrFail($request->kamar_id);
        if ($kamar->status !== 'Kosong') {
            return response()->json(['message' => 'Kamar tidak tersedia untuk dibooking.'], 400);
        }

        $transaksi = Transaksi::create([
            'user_id'          => $user->id,
            'kamar_id'         => $request->kamar_id,
            'total'            => $request->total,
            'status_transaksi' => 'Unpaid',
            'type'             => 'DP Booking',
            'angka_meteran'    => null,
            'foto_meteran'     => null,
            'expired_at'       => Carbon::now()->addHours(2),
        ]);

        // Update status kamar dan role user otomatis menjadi tenant
        $kamar->update(['status' => 'Booking']);

        return response()->json([
            'message' => 'Booking berhasil, silakan lakukan pembayaran DP dalam 2 jam.',
            'data'    => $transaksi
        ], 201);
    }
}
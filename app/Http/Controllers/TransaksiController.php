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
    try {
        // 1. Validasi
        $validator = \Validator::make($request->all(), [
            'kamar_id'      => 'required|exists:kamars,id',
            'user_id'       => 'required|exists:users,id',
            'angka_meteran' => 'required|numeric',
            'total'         => 'required|numeric',
            'foto_meteran'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // 2. Upload Foto
        $path = $request->file('foto_meteran')->store('meteran', 'public');

        // 3. Simpan
        Transaksi::create([
            'kamar_id'         => $request->kamar_id,
            'user_id'          => $request->user_id,
            'angka_meteran'    => $request->angka_meteran,
            'total'            => $request->total,
            'foto_meteran'     => $path,
            'status_transaksi' => 'Unpaid',
            'type'             => 'Bulanan',
            'expired_at'       => Carbon::now()->addDays(3),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan']);

    } catch (\Exception $e) {
        // Jika ada error sistem (database/server), kirim pesan error
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
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
<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Survey;

class VisitorController extends Controller
{
    public function index()
    {
        $branches = Kos::withCount('kamars')
            ->withCount([
                'kamars',
                'kamars as available_kamar_count' => function ($query) {
                $query->where('status', 'Kosong');
            }])
            ->orderBy('created_at', 'desc') // Urutkan dari cabang terbaru
            ->get();

        $availableRooms = Kamar::where('status', 'Kosong')
            ->with('kos')
            ->get();

        return view('visitor.home', compact('branches', 'availableRooms'));
    }

    public function branches()
    {
        $branches = Kos::withCount('kamars')
            ->withCount(['kamars as available_kamar_count' => function ($query) {
                $query->where('status', 'Kosong');
            }])
            ->get();

        return view('visitor.branches', compact('branches'));
    }

    public function profile()
    {
        $user = Auth::user();
        $branchesCount = Kos::count();
        $availableRoomsCount = Kamar::where('status', 'Kosong')->count();

        return view('visitor.profile', compact('user', 'branchesCount', 'availableRoomsCount'));
    }
    public function show($id)
    {
        // Menggunakan model Kos, bukan Branch. Ambil beserta data kamarnya.
        $branch = Kos::with('kamars')->findOrFail($id);

        return view('visitor.detail', compact('branch'));
    }

    public function storeBooking(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu untuk melakukan booking.');
        }

        // 1. Validasi input dari form UI David
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal_masuk' => 'required|date',
        ]);

        // 2. Ambil data kamar
        $kamar = Kamar::findOrFail($request->kamar_id);
        $userId = Auth::id();

        // 3. Buat transaksi DP Booking
         $transaksi = Transaksi::create([
            'user_id' => $userId,
            'kamar_id' => $kamar->id,
            'total' => 500000, // Misal nominal tetap DP Booking Rp 500.000
            'status_transaksi' => 'Unpaid',
            'type' => 'DP',
        ]);

        // 4. Ubah status kamar sementara menjadi "Booking" agar tidak diambil orang lain
         $kamar->update(['status' => 'Booking']);

        // 5. Lempar langsung ke halaman Checkout Midtrans yang sudah kamu buat sebelumnya!
        return redirect()->route('pembayaran.checkout', $transaksi->id);
    }

    public function storeSurvey(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'tanggal_survey' => 'required|date',
            'jam_survey' => 'required',
            'no_wa' => 'required|string|max:20',
        ]);

        // 2. Gabungkan tanggal dan jam menjadi format datetime
        $waktuSurvey = $request->tanggal_survey . ' ' . $request->jam_survey;

        // 3. Simpan ke database
        Survey::create([
            'kos_id' => $request->kos_id,
            'user_id' => auth()->id(), // Mengambil ID user yang sedang login
            'waktu_survey' => $waktuSurvey,
            'no_wa' => $request->no_wa,
            'status' => 'Pending', // Status default saat pengajuan
        ]);

        // 4. Kembali ke halaman dengan pesan sukses
        return redirect()->back()->with('success', 'Pengajuan survey berhasil! Admin akan segera menghubungi Anda.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function index()
    {
        $branches = Kos::withCount('kamars')
            ->withCount(['kamars as available_kamar_count' => function ($query) {
                $query->where('status', 'Kosong');
            }])
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
    public function storeSurvey(Request $request)
    {
        // Nanti kamu bisa tambahkan kode untuk menyimpan data ke database di sini
        return redirect()->back()->with('success', 'Pengajuan survey berhasil dikirim. Admin akan segera menghubungi Anda!');
    }

    public function storeBooking(Request $request)
    {
        // Nanti kamu bisa tambahkan kode untuk menyimpan data booking ke database di sini
        return redirect()->back()->with('success', 'Booking berhasil diajukan! Silakan selesaikan pembayaran.');
    }
}

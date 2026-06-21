<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kos;
use App\Models\User;
use App\Models\MaintenanceTiket;
use App\Models\Survey;
use App\Models\Pengumuman; // 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung total pendapatan dari transaksi lunas (handling case-insensitive 'Paid'/'paid')
        $totalPendapatan = Transaksi::whereIn('status_transaksi', ['paid', 'Paid'])->sum('total');

        // 2. Hitung total cabang properti
        $totalCabang = Kos::count();

        // 3. Hitung total penghuni dengan role tenant
        $totalPenghuni = User::where('role', 'tenant')->count();

        // 4. Hitung keluhan/maintenance tiket yang berstatus Pending
        $totalKomplain = MaintenanceTiket::where('status', 'Pending')->count();

        // Mengambil 5 aktivitas survey terupdate dari cabang manapun tanpa memandang status (Pending/Approved/Selesai)
        $surveys = Survey::with(['user', 'kos'])
            ->latest()
            ->take(5)
            ->get();

        $cabangList = Kos::orderBy('nama', 'asc')->get();

        return view('superadmin.dashboard', compact(
            'totalPendapatan',
            'totalCabang',
            'totalPenghuni',
            'totalKomplain',
            'surveys',
            'cabangList'
        ));

    
    }

    public function updateSurveyStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Disetujui,Ditolak']);
        $survey = Survey::findOrFail($id);
        $survey->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan ?? null
        ]);
        return redirect()->back()->with('success', 'Permintaan survey berhasil diproses.');
    }



    public function storePengumuman(Request $request)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'kos_id' => 'required', // Bisa 'all' atau ID cabang
            'isi'    => 'required|string',
        ]);

        // 1. Simpan ke Database
        $pengumuman = Pengumuman::create([
            'judul'  => $request->judul,
            'isi'    => $request->isi,
            'kos_id' => $request->kos_id === 'all' ? null : $request->kos_id,
        ]);

        // 2. Logika WA Blast ke Penghuni menggunakan Fonnte
        // Kumpulkan data user berdasarkan cabang (atau semua cabang)
        $query = User::where('role', 'tenant')->whereNotNull('no_wa');
        if ($request->kos_id !== 'all') {
            $query->where('kos_id', $request->kos_id);
        }
        $tenants = $query->get();

        // Format nomor agar sesuai dengan Fonnte (koma separator untuk multiple target)
        $targetWA = $tenants->map(function ($user) {
            $wa = $user->no_wa;
            return (substr($wa, 0, 1) == '0') ? '62' . substr($wa, 1) : $wa;
        })->implode(',');



        // 3. Kirim Pesan jika ada targetnya
        if (!empty($targetWA)) {
            $teksPesan = "*PENGUMUMAN KOS AJA*\n\n"
                       . "Halo Kak,\nAda informasi baru terkait hunian Anda:\n\n"
                       . "*{$pengumuman->judul}*\n"
                       . "{$pengumuman->isi}\n\n"
                       . "_Pesan otomatis dari PuluBoys Manajemen_";

            // Tambahkan withoutVerifying() untuk mengatasi error SSL Localhost
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target'  => $targetWA,
                'message' => $teksPesan,
            ]);
        
        //     // TAMPILKAN RESPON DARI FONNTE UNTUK DEBUGGING
        //     dd([
        //         'status_http' => $response->status(),
        //         'respon_fonnte' => $response->json(),
        //         'target_nomor' => $targetWA
        //     ]);

         } 
        //else {
        //     // Jika targetWA kosong, tampilkan pesan ini
        //     dd('Gagal kirim WA: Tidak ada user dengan role "tenant" yang memiliki nomor WA di cabang ini.');
        // }


        return redirect()->back()->with('success', 'Pengumuman berhasil disimpan dan dibroadcast ke WhatsApp Tenant!');
    }
}
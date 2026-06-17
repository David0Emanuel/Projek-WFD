<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Transaksi;
use App\Models\MaintenanceTiket;
use App\Models\Survey;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $totalKamar = Kamar::count();
        $terisiCount = Kamar::where('status', 'Terisi')->count();
        $occupancyRate = $totalKamar > 0 ? round(($terisiCount / $totalKamar) * 100) : 0;

        $waitingCheckinCount = Transaksi::where('type', 'DP')
            ->where('status_transaksi', 'Paid')
            ->count();

        $pendingTiketCount = MaintenanceTiket::where('status', 'Pending')->count();

        $todaySurveyCount = Survey::whereDate('waktu_survey', now())->count();

        return view('admin.dashboard', compact(
            'totalKamar',
            'terisiCount',
            'occupancyRate',
            'waitingCheckinCount',
            'pendingTiketCount',
            'todaySurveyCount'
        ));
    }
    public function kamar()
    {
        // Mengambil semua data kamar beserta relasi kos dan penghuninya (user)
        // Pastikan model Kamar memiliki relasi ke User jika ada
        $kamars = Kamar::with('kos')->orderBy('nomor', 'asc')->get();
        return view('admin.kamar', compact('kamars'));
    }

    public function survey()
    {
        // 1. Data Check-In: Mengambil data langsung dari tabel Kamar yang berstatus 'Booking'
        // Ini menjamin datanya selalu sama persis dengan halaman Manajemen Kamar
        $checkins = Kamar::with('kos')
            ->where('status', 'Booking')
            ->get();
        
        // 2. Data Survey (Tetap sama)
        $surveys = Survey::with(['kos'])
            ->whereDate('waktu_survey', '>=', now()->toDateString())
            ->orderBy('waktu_survey', 'asc')
            ->get();

        return view('admin.survey', compact('checkins', 'surveys'));
    }

    public function komplain()
    {
        // Mengambil tiket komplain dari terbaru ke terlama
        $komplains = MaintenanceTiket::with(['kamar'])->orderBy('created_at', 'desc')->get();
        return view('admin.komplain', compact('komplains'));
    }
    // ... method komplain yang sudah ada ...

    // FUNGSI UNTUK MENYIMPAN TAGIHAN & FOTO METERAN
    public function storeMeteran(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'total_tagihan' => 'required|numeric|min:0',
            'foto_meteran' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // 2. Simpan Foto ke folder Storage Laravel
        $fotoPath = null;
        if ($request->hasFile('foto_meteran')) {
            $file = $request->file('foto_meteran');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Akan tersimpan di folder: storage/app/public/meteran
            $fotoPath = $file->storeAs('public/meteran', $filename); 
        }

        // 3. Simpan Data Tagihan ke Database (Tabel Transaksi)
        // Pastikan struktur kolom ini sesuai dengan tabel Transaksi yang dibuat timmu
        Transaksi::create([
            'kamar_id' => $request->kamar_id,
            'total' => $request->total_tagihan,
            'type' => 'Tagihan Bulanan', // Tipe transaksi
            'status_transaksi' => 'Unpaid', // Status belum dibayar oleh tenant
            // Jika ada kolom khusus foto meteran di database, masukkan $fotoPath ke sana
            // 'foto_meteran' => str_replace('public/', '', $fotoPath), 
        ]);

        return redirect()->back()->with('success', 'Tagihan meteran beserta foto berhasil dikirim ke tenant kamar tersebut.');
    }

    // FUNGSI UNTUK MENGUBAH STATUS KAMAR MENJADI MAINTENANCE
    public function updateMaintenance(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
        ]);

        // Cari kamar berdasarkan ID, lalu ubah statusnya
        $kamar = Kamar::findOrFail($request->kamar_id);
        $kamar->status = 'Maintenance';
        $kamar->save();

        return redirect()->back()->with('success', 'Status kamar berhasil diubah. Kamar kini dalam perbaikan dan tidak bisa dibooking.');
    }
    // FUNGSI UNTUK MENGEMBALIKAN STATUS KAMAR JADI KOSONG
    public function markAsKosong(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
        ]);

        $kamar = Kamar::findOrFail($request->kamar_id);
        $kamar->status = 'Kosong';
        $kamar->save();

        return redirect()->back()->with('success', 'Perbaikan selesai! Kamar telah dikembalikan ke status Kosong dan siap disewakan.');
    }
}

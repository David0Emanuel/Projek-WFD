<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Transaksi;
use App\Models\MaintenanceTiket;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- WAJIB DITAMBAHKAN

class KamarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect('/login'); 

        // $user = (object) ['kos_id' => 1];   //buat ngetes akses database admin cabang beda

        // 1. FILTER KAMAR BERDASARKAN CABANG ADMIN
        $kamarQuery = Kamar::where('kos_id', $user->kos_id);

        $totalKamar = $kamarQuery->count();
        $terisiCount = (clone $kamarQuery)->where('status', 'Terisi')->count();
        $occupancyRate = $totalKamar > 0 ? round(($terisiCount / $totalKamar) * 100) : 0;

        // 2. FILTER TRANSAKSI (Melalui relasi kamar -> kos_id)
        $waitingCheckinCount = Transaksi::where('type', 'DP')
            ->where('status_transaksi', 'Paid')
            ->whereHas('kamar', function ($query) use ($user) {
                $query->where('kos_id', $user->kos_id);
            })
            ->count();

        // 3. FILTER TIKET KOMPLAIN (Melalui relasi kamar -> kos_id)
        $pendingTiketCount = MaintenanceTiket::where('status', 'Pending')
            ->whereHas('kamar', function ($query) use ($user) {
                $query->where('kos_id', $user->kos_id);
            })
            ->count();

        // 4. FILTER SURVEY BERDASARKAN CABANG ADMIN
        $todaySurveyCount = Survey::where('kos_id', $user->kos_id)
            ->whereDate('waktu_survey', now())
            ->count();

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
        // $user = Auth::user();
        // if (!$user) return redirect('/login');
        // $user = (object) ['kos_id' => 1];   //buat ngetes akses database admin cabang beda
        $user = Auth::user();
        if (!$user) return redirect('/login');

        // Mengambil kamar khusus untuk cabang admin ini saja
        $kamars = Kamar::with('kos')
            ->where('kos_id', $user->kos_id) 
            ->orderBy('nomor', 'asc')
            ->get();
            
        return view('admin.kamar', compact('kamars'));
    }

    public function survey()
    {
        // $user = Auth::user();
        // if (!$user) return redirect('/login');
        // $user = (object) ['kos_id' => 1];   //buat ngetes akses database admin cabang beda
        $user = Auth::user();
        if (!$user) return redirect('/login');
        
        // 1. Data Check-In khusus cabang admin
        $checkins = Kamar::with('kos')
            ->where('kos_id', $user->kos_id) 
            ->where('status', 'Booking')
            ->get();
        
        // 2. Data Survey khusus cabang admin
        $surveys = Survey::with(['kos'])
            ->where('kos_id', $user->kos_id) 
            ->whereDate('waktu_survey', '>=', now()->toDateString())
            ->orderBy('waktu_survey', 'asc')
            ->get();

        return view('admin.survey', compact('checkins', 'surveys'));
    }

    public function komplain()
    {
        // $user = Auth::user();
        // if (!$user) return redirect('/login');
        // $user = (object) ['kos_id' => 1];   //buat ngetes akses database admin cabang beda
        $user = Auth::user();
        if (!$user) return redirect('/login');

        // Mengambil tiket komplain khusus untuk kamar-kamar yang ada di cabang admin ini
        $komplains = MaintenanceTiket::with(['kamar'])
            ->whereHas('kamar', function ($query) use ($user) {
                $query->where('kos_id', $user->kos_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.komplain', compact('komplains'));
    }

    // ====================================================================
    // FUNGSI AKSI DI BAWAH INI TETAP SAMA (Tidak perlu ada yg diubah)
    // ====================================================================

    // FUNGSI UNTUK MENYIMPAN TAGIHAN & FOTO METERAN
    public function storeMeteran(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'total_tagihan' => 'required|numeric|min:0',
            'foto_meteran' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_meteran')) {
            $file = $request->file('foto_meteran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $fotoPath = $file->storeAs('public/meteran', $filename); 
        }

        Transaksi::create([
            'kamar_id' => $request->kamar_id,
            'total' => $request->total_tagihan,
            'type' => 'Tagihan Bulanan', 
            'status_transaksi' => 'Unpaid', 
        ]);

        return redirect()->back()->with('success', 'Tagihan meteran beserta foto berhasil dikirim ke tenant kamar tersebut.');
    }

    // FUNGSI UNTUK MENGUBAH STATUS KAMAR MENJADI MAINTENANCE
    public function updateMaintenance(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
        ]);

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

    // FUNGSI UNTUK MELAKUKAN CHECK-IN
    public function checkin(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
        ]);

        $kamar = Kamar::findOrFail($request->kamar_id);
        $kamar->status = 'Terisi';
        $kamar->save();

        return redirect()->back()->with('success', 'Check-in berhasil! Kamar telah diubah menjadi status Terisi.');
    }
}
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


        // 1. FILTER KAMAR BERDASARKAN CABANG ADMIN
        $kamarQuery = Kamar::where('kos_id', $user->kos_id);

        $totalKamar = $kamarQuery->count();
        $terisiCount = (clone $kamarQuery)->where('status', 'Terisi')->count();
        $occupancyRate = $totalKamar > 0 ? round(($terisiCount / $totalKamar) * 100) : 0;

        // 2. FILTER TRANSAKSI
        $waitingCheckinCount = Transaksi::where('type', 'DP')
            ->where('status_transaksi', 'Paid')
            ->whereHas('kamar', function ($query) use ($user) {
                $query->where('kos_id', $user->kos_id);
            })
            ->count();

        // 3. FILTER TIKET KOMPLAIN
        $pendingTiketCount = MaintenanceTiket::where('status', 'Pending')
            ->whereHas('kamar', function ($query) use ($user) {
                $query->where('kos_id', $user->kos_id);
            })
            ->count();

        // 4. FILTER SURVEY BERDASARKAN CABANG ADMIN
        $todaySurveyCount = Survey::where('kos_id', $user->kos_id)
            ->whereDate('waktu_survey', '>=', now()->toDateString())
            ->whereIn('status', ['Pending', 'pending', 'PENDING']) 
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
        $user = Auth::user();
        if (!$user) return redirect('/login');
        
        // 1. Data Check-In
        $checkins = Kamar::with(['kos', 'transaksis' => function($query) {
                $query->whereIn('type', ['DP', 'DP Booking'])
                      ->where('status_transaksi', 'Paid')
                      ->latest();
            }, 'transaksis.user'])
            ->where('kos_id', $user->kos_id) 
            ->where('status', 'Booking')
            ->get();
        
        // 2. Data Survey
        $surveys = Survey::with(['kos', 'user']) 
            ->where('kos_id', $user->kos_id) 
            ->whereDate('waktu_survey', '>=', now()->toDateString())
            ->orderBy('waktu_survey', 'asc')
            ->get();

        return view('admin.survey', compact('checkins', 'surveys'));
    }

    public function komplain()
    {
        $user = Auth::user();
        if (!$user) return redirect('/login');

        $komplains = MaintenanceTiket::with(['kamar'])
            ->whereHas('kamar', function ($query) use ($user) {
                $query->where('kos_id', $user->kos_id);
            })
            ->whereIn('status', ['Pending', 'Proses']) 
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.komplain', compact('komplains'));
    }

    // MENYIMPAN TAGIHAN & FOTO METERAN
    public function storeMeteran(Request $request)
    {
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric|min:0',
            'angka_meteran' => 'required|integer|min:0',
            'foto_meteran' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_meteran')) {
            $file = $request->file('foto_meteran');
            $filename = time() . '_' . $file->getClientOriginalName();
           $fotoPath = $file->storeAs('meteran', $filename, 'public'); 
        }

        // TRANSAKSI BARU khusus listrik terpisah dari uang kos
        Transaksi::create([
            'user_id' => $request->user_id,
            'kamar_id' => $request->kamar_id,
            'total' => $request->total,
            'angka_meteran' => $request->angka_meteran,
            'foto_meteran' => $fotoPath,
            'type' => 'Token Listrik', // Tipe dibedakan
            'status_transaksi' => 'Unpaid', 
            // Batas waktu bayar listrik (misal 3 hari setelah diinput admin)
            'expired_at' => now()->addDays(3)->endOfDay() 
        ]);

        return redirect()->back()->with('success', 'Tagihan Token Listrik berhasil dikirim ke tenant tersebut.');
    }

    // FUNGSI MENGUBAH STATUS KAMAR
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

    // FUNGSI MENGEMBALIKAN STATUS KAMAR
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

    // FUNGSI MELAKUKAN CHECK-IN
    public function checkin(Request $request)
    {
        // 1. Validasi kamar yang diklik
        $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
        ]);

        $kamar = \App\Models\Kamar::findOrFail($request->kamar_id);

        // 2. CARI OTOMATIS: Siapa Visitor yang sudah lunas DP di kamar ini?
        $transaksi = \App\Models\Transaksi::where('kamar_id', $kamar->id)
                        ->where(function($query) {
                            $query->where('type', 'DP')->orWhere('type', 'DP Booking');
                        })
                        ->where('status_transaksi', 'Paid')
                        ->latest()
                        ->first();

        // 3. Jika ketemu, ganti role jadi Tenant
        if ($transaksi && $transaksi->user) {
            $transaksi->user->update([
                'role' => 'tenant',
                'kamar_id' => $kamar->id,
                'kos_id' => $kamar->kos_id,
                'tanggal_mulaiSewa' => now()->toDateString(),
            ]);
        } else {
            // Jika admin menekan check-in tapi DP belum lunas, tolak!
            return redirect()->back()->with('error', 'Gagal Check-In: Belum ada pembayaran DP yang lunas untuk kamar ini.');
        }

        // 4. Ubah status kamar menjadi Terisi
        $kamar->update(['status' => 'Terisi']);

        return redirect()->back()->with('success', 'Check-in berhasil! Kunci telah diserahkan dan Visitor resmi menjadi Tenant.');
    }

    // FUNGSI MENGUBAH STATUS TIKET KOMPLAIN
    public function updateStatusKomplain(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'tiket_id' => 'required|exists:maintenance_tikets,id', // Sesuaikan nama tabel 'maintenance_tikets' jika berbeda
            'status_baru' => 'required|in:Proses,Selesai'
        ]);

        // 2. Cari tiket komplain berdasarkan ID
        $tiket = MaintenanceTiket::findOrFail($request->tiket_id);

        // 3. Ubah statusnya sesuai tombol yang ditekan (Proses / Selesai)
        $tiket->status = $request->status_baru;
        $tiket->save();

        // 4. Siapkan pesan sukses dinamis
        if ($request->status_baru == 'Proses') {
            $pesan = 'Tiket komplain berhasil diubah menjadi Sedang Dikerjakan (Proses).';
        } else {
            $pesan = 'Pekerjaan selesai! Tiket komplain telah ditutup.';
        }

        // 5. Kembalikan ke halaman sebelumnya dengan pesan
        return redirect()->back()->with('success', $pesan);
    }
    public function approveSurvey($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->status = 'Approved'; // Pastikan kolom 'status' ada di tabel surveys
        $survey->save();

        return redirect()->back()->with('success', 'Jadwal survey berhasil di-approve.');
    }

    // FUNGSI UNTUK MENGUBAH DETAIL, FOTO, DAN HARGA KAMAR
    public function updateDetail(Request $request)
    {
        $request->validate([
            'kamar_id'    => 'required|exists:kamars,id',
            'tipe_kamar'  => 'required|string',
            'harga'       => 'required|numeric|min:0',
            'spesifikasi' => 'nullable|string',
            'fasilitas'   => 'nullable|string',
            'foto_kamar'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kamar = \App\Models\Kamar::findOrFail($request->kamar_id);
        $fotoPath = $kamar->foto_kamar;

        // Proses Upload Foto Kamar Baru
        if ($request->hasFile('foto_kamar')) {
            if ($kamar->foto_kamar && \Illuminate\Support\Facades\Storage::disk('public')->exists($kamar->foto_kamar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($kamar->foto_kamar);
            }
            $file = $request->file('foto_kamar');
            $filename = time() . '_kamar_' . $file->getClientOriginalName();
            $fotoPath = $file->storeAs('kamar', $filename, 'public');
        }

        // CEK APAKAH ADMIN MENCENTANG "TERAPKAN KE SEMUA"
        if ($request->has('apply_to_all')) {
            // Update SEMUA kamar di kos ini yang tipe aslinya sama dengan kamar yang diedit
            \App\Models\Kamar::where('kos_id', $kamar->kos_id)
                ->where('tipe_kamar', $kamar->tipe_kamar) 
                ->update([
                    'tipe_kamar'  => $request->tipe_kamar,
                    'harga'       => $request->harga,
                    'spesifikasi' => $request->spesifikasi,
                    'fasilitas'   => $request->fasilitas,
                    'foto_kamar'  => $fotoPath,
                ]);
            $pesan = 'Update Massal Berhasil! Semua kamar ' . $kamar->tipe_kamar . ' telah diperbarui.';
        } else {
            // Update HANYA 1 kamar ini saja
            $kamar->update([
                'tipe_kamar'  => $request->tipe_kamar,
                'harga'       => $request->harga,
                'spesifikasi' => $request->spesifikasi,
                'fasilitas'   => $request->fasilitas,
                'foto_kamar'  => $fotoPath,
            ]);
            $pesan = 'Detail Spesifikasi Kamar ' . $kamar->nomor . ' berhasil diperbarui!';
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function completeSurvey($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->status = 'Selesai';
        $survey->save();

        return redirect()->back()->with('success', 'Survey telah selesai dilakukan. Baris terkunci.');
    }
}
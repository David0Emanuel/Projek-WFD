<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\Kamar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CabangController extends Controller
{
    public function index()
    {
        // Panggil juga data relasi 'admin' agar bisa diedit di modal
        $cabang = Kos::with(['admin'])->withCount([
                'kamars as kamar_count', 
                'kamars as kamar_kosong_count' => function ($q) {
                    $q->where('status', 'Kosong');
                },
                'kamars as kamar_terisi_count' => function ($q) {
                    $q->where('status', 'Terisi');
                }
            ])
            ->latest()
            ->paginate(10);

        return view('superadmin.cabang.index', compact('cabang'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Lengkap
        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'alamat'         => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'total_kamar'    => 'required|integer|min:1',
            'tipe_kamar'     => 'required|string|max:50', 
            'harga'          => 'required|numeric|min:0',
            'admin_nama'     => 'required|string|max:100',
            'admin_username' => 'required|string|unique:users,username|max:50',
            'admin_password' => 'required|string|min:6',
        ]);

        // Proses Upload Foto (Jika ada)
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $fotoPath = $file->storeAs('kos', $filename, 'public'); 
        }

        // 2. Buat Cabang Baru
        $kos = Kos::create([
            'nama'      => $request->nama,
            'alamat'    => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'foto'      => $fotoPath,
        ]);

        // 3. Auto-Generate Kamar 
        for ($i = 1; $i <= $request->total_kamar; $i++) {
            Kamar::create([
                'kos_id' => $kos->id,
                'nomor'  => str_pad($i, 2, '0', STR_PAD_LEFT), 
                'status' => 'Kosong',
                'tipe_kamar' => $request->tipe_kamar, 
                'harga'      => $request->harga,
            ]);
        }

        // 4. Buat Akun Admin Cabang
        User::create([
            'nama'     => $request->admin_nama,
            'username' => $request->admin_username,
            'email'    => $request->admin_username . '@puluboys.com', 
            'no_wa'    => '0000', 
            'password' => \Illuminate\Support\Facades\Hash::make($request->admin_password),
            'role'     => 'admin_cabang',
            'kos_id'   => $kos->id,
        ]);

        return redirect()->route('superadmin.cabang.index')
            ->with('success', 'Cabang, Kamar, dan Akun Admin berhasil dibuat!');
    }

    public function update(Request $request, Kos $cabang)
    {
       // 1. Validasi Update
        $request->validate([
            'nama'           => 'required|string|max:100',
            'alamat'         => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'total_kamar'    => 'required|integer|min:' . $cabang->kamars()->count(),
            'tipe_kamar'     => 'nullable|string|max:50', 
            'harga'          => 'nullable|numeric|min:0', 
            'admin_nama'     => 'required|string|max:100',
            'admin_username' => 'required|string|max:50|unique:users,username,' . ($cabang->admin->id ?? ''),
        ]);

        $fotoPath = $cabang->foto;

        // Jika upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($cabang->foto && Storage::disk('public')->exists($cabang->foto)) {
                Storage::disk('public')->delete($cabang->foto);
            }
            // Upload foto baru
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $fotoPath = $file->storeAs('kos', $filename, 'public');
        }

        // 2. Update Data Kos
        $cabang->update([
            'nama'      => $request->nama,
            'alamat'    => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'foto'      => $fotoPath,
        ]);

        // 3. Tambah Kamar Baru
        $kamarSekarang = $cabang->kamars()->count();
        if ($request->total_kamar > $kamarSekarang) {
            $selisih = $request->total_kamar - $kamarSekarang;
            for ($i = 1; $i <= $selisih; $i++) {
                $nomorBaru = $kamarSekarang + $i;
                Kamar::create([
                    'kos_id' => $cabang->id,
                    'nomor'  => str_pad($nomorBaru, 2, '0', STR_PAD_LEFT),
                    'status' => 'Kosong',
                    'tipe_kamar' => $request->tipe_kamar ?? 'Standard', 
                    'harga'      => $request->harga ?? 0,
                ]);
            }
        }

        // 4. Update Akun Admin
        $admin = $cabang->admin;
        if ($admin) {
            $admin->nama = $request->admin_nama;
            $admin->username = $request->admin_username;
            if ($request->filled('admin_password')) {
                $admin->password = \Illuminate\Support\Facades\Hash::make($request->admin_password);
            }
            $admin->save();
        }

        return redirect()->route('superadmin.cabang.index')
            ->with('success', 'Data cabang dan admin berhasil diperbarui.');
    }

    public function destroy(Kos $cabang)
    {
        // Hapus akun admin cabang
        User::where('kos_id', $cabang->id)->where('role', 'admin_cabang')->delete();
        
        // Hapus semua data kamar
        $cabang->kamars()->delete();
        
        // Terakhir, hapus data cabang
        $cabang->delete();

        return redirect()->route('superadmin.cabang.index')
            ->with('success', 'Cabang beserta data kamar dan adminnya berhasil dihapus.');
    }
}
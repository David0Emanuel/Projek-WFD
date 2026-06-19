<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function requestKeluar(\Illuminate\Http\Request $request)
    {
        // Validasi agar tanggal tidak boleh di masa lalu
        $request->validate([
            'tanggal_selesaiSewa' => 'required|date|after_or_equal:today',
        ]);

        $user = auth()->user();
        
        // Simpan tanggal ke tabel users
        $user->update([
            'tanggal_selesaiSewa' => $request->tanggal_selesaiSewa
        ]);

        // Kembalikan ke halaman dashboard dengan pesan sukses
        return back()->with('success', 'Rencana tanggal keluar berhasil disimpan.');
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        $cabang = Kos::withCount(['kamar', 'kamar as kamar_kosong_count' => function ($q) {
                $q->where('status', 'kosong');
            }])
            ->withCount(['kamar as kamar_terisi_count' => function ($q) {
                $q->where('status', 'terisi');
            }])
            ->latest()
            ->paginate(10);

        return view('superadmin.cabang.index', compact('cabang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'alamat'  => 'required|string|max:255',
        ]);

        Kos::create($validated);

        return redirect()->route('superadmin.cabang.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function update(Request $request, Kos $cabang)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'alamat'  => 'required|string|max:255',
        ]);

        $cabang->update($validated);

        return redirect()->route('superadmin.cabang.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Kos $cabang)
    {
        $cabang->delete();

        return redirect()->route('superadmin.cabang.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}
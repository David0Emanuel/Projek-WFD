<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class PenghuniController extends Controller
{
    public function index()
    {
        $penghuni = User::where('role', 'tenant')
            ->with(['kamar.kos'])
            ->latest()
            ->paginate(15);

        return view('superadmin.penghuni.index', compact('penghuni'));
    }

    public function show($id)
    {
        $penghuni = User::where('role', 'tenant')
            ->with(['kamar.kos', 'transaksi', 'maintenanceTiket'])
            ->findOrFail($id);

        return view('superadmin.penghuni.show', compact('penghuni'));
    }
    
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KosController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            $cabang = Kos::all(); 
        } elseif ($user->role === 'admin_cabang') {
            $cabang = Kos::where('id', $user->kos_id)->get(); 
        }

        return view('dashboard.cabang', compact('cabang'));
    }
}
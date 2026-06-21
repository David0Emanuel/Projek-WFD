<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceTiket;

class MaintenanceTicketController extends Controller
{
    public function index()
    {
        $tickets = MaintenanceTiket::orderBy('created_at', 'desc')->get();
        return view('tenant.maintenance', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            
            $file->move(public_path('uploads'), $filename);
            $fotoPath = 'uploads/' . $filename;
        }

        MaintenanceTiket::create([
            'user_id' => auth()->id(),
            'kamar_id' => auth()->user()->kamar_id,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'status' => 'Pending',
        ]);

        return redirect()->route('tenant.maintenance')->with('success', 'Tiket Maintenance Berhasil Dikirim');
    }
    public function delete($id)
    {
        $ticket = MaintenanceTiket::where('id', $id)
                    ->where('user_id', auth()->id()) // Pastikan tiket ini milik user yang sedang login
                    ->firstOrFail();

        if ($ticket->status !== 'Pending') {
            return redirect()->back()->with('error', 'Tiket yang sudah diproses tidak dapat dibatalkan.');
        }
        if ($ticket->foto && File::exists(public_path($ticket->foto))) {
            File::delete(public_path($ticket->foto));
        }

        // Hapus data dari database
        $ticket->delete();

        return redirect()->route('tenant.maintenance')->with('success', 'Tiket keluhan berhasil dibatalkan dan dihapus.');
    }
}

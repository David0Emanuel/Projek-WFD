<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MaintenanceTicket;

class MaintenanceTicketController extends Controller
{
    public function index()
    {
        $tickets = MaintenanceTicket::orderBy('created_at', 'desc')->get();
        return view('tenant.maintenance', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Buat folder public/uploads jika belum ada
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            
            $file->move(public_path('uploads'), $filename);
            $imagePath = 'uploads/' . $filename;
        }

        MaintenanceTicket::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('tenant.maintenance')->with('success', 'Tiket Maintenance Berhasil Dikirim');
    }
}

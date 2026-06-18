<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register'); // Pastikan David/kamu membuat view ini nanti
    }

    // Proses Register
    public function processRegister(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'username' => 'required|unique:users,username|max:255',
            'email'    => 'required|email|unique:users,email|max:255',
            'no_wa'    => 'required|numeric',
            'password' => 'required|min:6',
        ]);

     $user = User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'no_wa'    => $request->no_wa,
            'password' => Hash::make($request->password),
            'role'     => 'visitor',
        ]);
    }
    // Proses Unified Login
    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            // Logika Unified Login Redirect (Sesuai Proposal)
            switch ($role) {
                case 'super_admin':
                    return redirect()->route('superadmin.dashboard');
                case 'admin_cabang':
                    return redirect()->route('admin.dashboard');
                case 'tenant':
                    return redirect()->route('tenant.dashboard');
                case 'visitor':
                default:
                    return redirect()->route('visitor.branches');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
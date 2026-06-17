@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem Global')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="lg:col-span-2 space-y-6">
            
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 bg-slate-50 px-5 py-4">
                    <h3 class="text-base font-bold text-gray-800">Profil Aplikasi PuluBoys</h3>
                    <p class="text-xs text-gray-500 mt-1">Informasi dasar yang akan ditampilkan ke penghuni (Tenant).</p>
                </div>
                <div class="p-5">
                    <form action="#" method="POST" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">Nama Perusahaan</label>
                                <input type="text" value="PuluBoys Manajemen" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">Nomor WhatsApp CS</label>
                                <input type="text" value="081234567890" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Email Utama</label>
                            <input type="email" value="admin@puluboys.com" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Alamat Kantor Pusat</label>
                            <textarea rows="2" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">Jl. Siwalankerto No. 121-131, Surabaya</textarea>
                        </div>
                        <div class="text-right">
                            <button type="button" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-bold text-white hover:bg-blue-700 cursor-pointer transition-colors">
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 bg-slate-50 px-5 py-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <h3 class="text-base font-bold text-gray-800">Keamanan Akun</h3>
                </div>
                <div class="p-5">
                    <form action="#" method="POST" class="space-y-4">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Password Lama</label>
                            <input type="password" placeholder="••••••••" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">Password Baru</label>
                                <input type="password" placeholder="Minimal 8 karakter" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">Konfirmasi Password Baru</label>
                                <input type="password" placeholder="Ulangi password baru" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="text-right mt-2">
                            <button type="button" class="rounded-lg bg-gray-800 px-5 py-2 text-sm font-bold text-white hover:bg-gray-900 cursor-pointer transition-colors">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="space-y-6">
            
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 bg-blue-50 px-5 py-4">
                    <h3 class="text-sm font-bold text-blue-800">Payment Gateway (Midtrans)</h3>
                </div>
                <div class="p-5">
                    <form action="#" method="POST" class="space-y-4">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Environment</label>
                            <select class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="sandbox" selected>Sandbox (Testing)</option>
                                <option value="production">Production (Live)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Merchant ID</label>
                            <input type="text" value="G-123456789" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Client Key</label>
                            <input type="text" value="SB-Mid-client-XXXXX" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Server Key</label>
                            <input type="password" value="SB-Mid-server-XXXXX" class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <p class="text-[10px] text-gray-400 mt-1">Jaga kerahasiaan Server Key Anda.</p>
                        </div>
                        <button type="button" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-700 cursor-pointer transition-colors">
                            Simpan Konfigurasi
                        </button>
                    </form>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Status Sistem</h3>
                <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-2">
                    <span class="text-xs text-gray-600">Website Status</span>
                    <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-bold">Online</span>
                </div>
                <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-2">
                    <span class="text-xs text-gray-600">Maintenance Mode</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-500"></div>
                    </label>
                </div>
                <p class="text-[10px] text-gray-400 mt-2 leading-relaxed">Aktifkan Maintenance Mode jika Anda sedang melakukan update sistem. Tenant tidak akan bisa login sementara.</p>
            </div>

        </div>
    </div>
@endsection
@extends('layouts.superadmin') @section('title', 'Super Admin Dashboard')
@section('page-title', 'Super Admin Dashboard')

@section('content')

    <section class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        
        <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mr-4 rounded-full bg-blue-100 p-3 text-blue-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Total Pendapatan</p>
                <p class="text-lg font-bold text-gray-800">Rp 125.500.000</p>
            </div>
        </div>

        <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mr-4 rounded-full bg-green-100 p-3 text-green-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Total Cabang</p>
                <p class="text-lg font-bold text-gray-800">4 Properti</p>
            </div>
        </div>

        <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mr-4 rounded-full bg-purple-100 p-3 text-purple-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Total Penghuni</p>
                <p class="text-lg font-bold text-gray-800">42 Orang</p>
            </div>
        </div>

        <div class="flex items-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="mr-4 rounded-full bg-red-100 p-3 text-red-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Keluhan Aktif</p>
                <p class="text-lg font-bold text-gray-800">3 Menunggu</p>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-gray-100 bg-slate-50 px-5 py-3">
                <h3 class="text-sm font-bold text-gray-700">Permintaan Survey Terbaru</h3>
                <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Lihat Semua &rarr;</a>
            </div>
            <div class="p-5 overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Visitor</th>
                            <th class="px-4 py-3">Cabang Tujuan</th>
                            <th class="px-4 py-3">Waktu Survey</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">Budi Santoso</td>
                            <td class="px-4 py-3">PuluBoys Siwalankerto</td>
                            <td class="px-4 py-3">20 Jun 2026, 14:00</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-yellow-100 px-2 py-1 text-[10px] font-bold text-yellow-700">Menunggu</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button onclick="openApprovalModal('Budi Santoso', 'PuluBoys Siwalankerto', '20 Jun 2026, 14:00')" class="rounded bg-blue-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-600 cursor-pointer">
                                    Proses
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">Clara Wijaya</td>
                            <td class="px-4 py-3">PuluBoys Tenggilis</td>
                            <td class="px-4 py-3">21 Jun 2026, 10:00</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-green-100 px-2 py-1 text-[10px] font-bold text-green-700">Disetujui</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button class="rounded bg-gray-200 px-3 py-1.5 text-xs font-bold text-gray-500 cursor-not-allowed" disabled>
                                    Selesai
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-amber-50 px-5 py-3">
                <h3 class="text-sm font-bold text-gray-700">Kirim Pengumuman</h3>
            </div>
            <div class="p-5">
                <form action="#" method="POST" class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Judul Pengumuman</label>
                        <input type="text" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Contoh: Jadwal Maintenance">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Tujuan Cabang</label>
                        <select class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="all">Semua Cabang (Global)</option>
                            <option value="1">PuluBoys Siwalankerto</option>
                            <option value="2">PuluBoys Tenggilis</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Isi Pesan</label>
                        <textarea rows="3" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Tulis pesan untuk tenant..."></textarea>
                    </div>
                    <button type="button" class="w-full rounded-lg bg-gray-800 px-4 py-2 text-sm font-bold text-white hover:bg-gray-900 cursor-pointer">
                        Kirim Sekarang
                    </button>
                </form>
            </div>
        </div>

    </section>

    <div id="approval-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all">
            <div class="flex items-center justify-between bg-blue-600 px-5 py-4 border-b border-gray-200">
                <span class="text-base font-bold text-white">Proses Permintaan Survey</span>
                <button type="button" onclick="closeApprovalModal()" class="text-blue-200 hover:text-white font-medium text-2xl transition-colors duration-200 cursor-pointer focus:outline-none">
                    &times;
                </button>
            </div>
            
            <div class="p-6">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-5">
                    <p class="text-sm text-gray-500 mb-1">Nama Visitor:</p>
                    <p class="text-base font-bold text-gray-800 mb-3" id="modal-visitor-name">-</p>
                    
                    <p class="text-sm text-gray-500 mb-1">Tujuan & Waktu:</p>
                    <p class="text-sm font-bold text-gray-800" id="modal-visitor-details">-</p>
                </div>
                
                <p class="text-sm font-medium text-gray-700 mb-3">Pilih Tindakan:</p>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeApprovalModal()" class="flex-1 py-2.5 px-4 bg-white border border-red-500 text-red-600 rounded-lg font-bold text-sm hover:bg-red-50 transition-colors cursor-pointer">
                        Tolak
                    </button>
                    <button type="button" onclick="closeApprovalModal()" class="flex-1 py-2.5 px-4 bg-green-500 text-white rounded-lg font-bold text-sm hover:bg-green-600 transition-colors cursor-pointer">
                        Setujui Survey
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // FUNGSI MODAL SUPER ADMIN
    function openApprovalModal(name, branch, time) {
        document.getElementById('modal-visitor-name').innerText = name;
        document.getElementById('modal-visitor-details').innerText = branch + ' | ' + time;
        document.getElementById('approval-modal').classList.remove('hidden');
    }

    function closeApprovalModal() {
        document.getElementById('approval-modal').classList.add('hidden');
    }
</script>
@endpush
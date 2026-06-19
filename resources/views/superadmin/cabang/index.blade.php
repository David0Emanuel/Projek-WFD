@extends('layouts.superadmin')
@section('title', 'Manajemen Cabang')
@section('page-title', 'Manajemen Cabang')

@section('content')

{{-- Alert Sukses --}}
@if(session('success'))
    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm font-medium text-green-700">
        {{ session('success') }}
    </div>
@endif

{{-- Header + Tombol Tambah --}}
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-500">Total <span class="font-bold text-gray-800">{{ $cabang->total() }}</span> cabang terdaftar</p>
    <button onclick="openModal('modal-tambah')" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700 cursor-pointer flex items-center gap-2">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Cabang
    </button>
</div>

{{-- Tabel Data Cabang --}}
<div class="rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3">#</th>
                <th class="px-5 py-3">Nama Cabang</th>
                <th class="px-5 py-3">Alamat</th>
                <th class="px-5 py-3 text-center">Total Kamar</th>
                <th class="px-5 py-3 text-center">Kamar Kosong</th>
                <th class="px-5 py-3 text-center">Terisi</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($cabang as $i => $kos)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-gray-400">{{ $cabang->firstItem() + $i }}</td>
                <td class="px-5 py-3 font-semibold text-gray-800">{{ $kos->nama }}</td>
                <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $kos->alamat }}</td>
                <td class="px-5 py-3 text-center font-bold text-gray-800">{{ $kos->kamar_count }}</td>
                <td class="px-5 py-3 text-center">
                    <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-bold text-green-700">
                        {{ $kos->kamar_kosong_count }}
                    </span>
                </td>
                <td class="px-5 py-3 text-center">
                    <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-bold text-blue-700">
                        {{ $kos->kamar_terisi_count }}
                    </span>
                </td>
                <td class="px-5 py-3 text-right flex items-center justify-end gap-2">
                    {{-- Tombol Edit (Kirim data kos & admin ke JS) --}}
                    <button onclick='openEditModal({!! json_encode([
                        "id" => $kos->id, 
                        "nama" => $kos->nama, 
                        "alamat" => $kos->alamat,
                        "kamar_count" => $kos->kamar_count,
                        "admin_nama" => $kos->admin->nama ?? "",
                        "admin_username" => $kos->admin->username ?? ""
                    ]) !!})'
                        class="rounded bg-amber-400 px-3 py-1.5 text-xs font-bold text-white hover:bg-amber-500 cursor-pointer">
                        Edit
                    </button>
                    {{-- Tombol Hapus --}}
                    <button onclick="openDeleteModal({{ $kos->id }}, '{{ $kos->nama }}')"
                        class="rounded bg-red-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-red-600 cursor-pointer">
                        Hapus
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-10 text-center text-gray-400">Belum ada cabang terdaftar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $cabang->links() }}
    </div>
</div>

{{-- ========================================== --}}
{{-- ============ MODAL TAMBAH ================ --}}
{{-- ========================================== --}}
<div id="modal-tambah" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between bg-blue-600 px-5 py-4 sticky top-0 z-10">
            <span class="text-base font-bold text-white">Tambah Cabang & Admin Baru</span>
            <button type="button" onclick="closeModal('modal-tambah')" class="text-blue-200 hover:text-white text-2xl cursor-pointer">&times;</button>
        </div>
        <form action="{{ route('superadmin.cabang.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Cabang</label>
                    <input type="text" name="nama" required placeholder="Contoh: PuluBoys Tenggilis"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Total Kamar (Auto-Generate)</label>
                    <input type="number" name="total_kamar" min="1" required placeholder="Misal: 10"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Kamar (Utama)</label>
                    <input type="text" name="tipe_kamar" required placeholder="Contoh: AC / Non-AC"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Harga Sewa Bulanan</label>
                    <input type="number" name="harga" min="0" required placeholder="Misal: 1500000"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Cabang</label>
                <textarea name="alamat" rows="2" required placeholder="Jl. Raya Tenggilis..."
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
            </div>

            <hr class="border-gray-200 my-4">
            <h4 class="text-xs font-bold text-gray-700 uppercase mb-2">Setup Akun Admin Cabang</h4>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap Admin</label>
                <input type="text" name="admin_nama" required placeholder="Nama Admin"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Username Login</label>
                    <input type="text" name="admin_username" required placeholder="admin_tenggilis"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Password Baru</label>
                    <input type="password" name="admin_password" required placeholder="Minimal 6 Karakter"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('modal-tambah')"
                    class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 cursor-pointer">
                    Simpan Cabang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================== --}}
{{-- ============== MODAL EDIT ================ --}}
{{-- ========================================== --}}
<div id="modal-edit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between bg-amber-500 px-5 py-4 sticky top-0 z-10">
            <span class="text-base font-bold text-white">Edit Cabang & Admin</span>
            <button type="button" onclick="closeModal('modal-edit')" class="text-amber-100 hover:text-white text-2xl cursor-pointer">&times;</button>
        </div>
        <form id="form-edit" action="" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Cabang</label>
                    <input type="text" id="edit-nama" name="nama" required
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Total Kamar</label>
                    <input type="number" id="edit-kamar" name="total_kamar" min="1" required
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <p class="text-[10px] text-gray-500 mt-1">Isi angka lebih besar jika ingin menambah kamar baru.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Kamar Baru (Opsional)</label>
                    <input type="text" name="tipe_kamar" placeholder="Isi jika menambah kamar"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Harga Kamar Baru (Opsional)</label>
                    <input type="number" name="harga" min="0" placeholder="Isi jika menambah kamar"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Cabang</label>
                <textarea id="edit-alamat" name="alamat" rows="2" required
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
            </div>

            <hr class="border-gray-200 my-4">
            <h4 class="text-xs font-bold text-gray-700 uppercase mb-2">Data Akun Admin Cabang</h4>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap Admin</label>
                <input type="text" id="edit-admin-nama" name="admin_nama" required
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Username Login</label>
                    <input type="text" id="edit-admin-username" name="admin_username" required
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Password (Opsional)</label>
                    <input type="password" name="admin_password" placeholder="Kosongkan jika tak diubah"
                        class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('modal-edit')"
                    class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-amber-500 text-white rounded-lg text-sm font-bold hover:bg-amber-600 cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================== --}}
{{-- ============= MODAL HAPUS ================ --}}
{{-- ========================================== --}}
<div id="modal-hapus" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between bg-red-600 px-5 py-4">
            <span class="text-base font-bold text-white">Konfirmasi Hapus</span>
            <button type="button" onclick="closeModal('modal-hapus')" class="text-red-200 hover:text-white text-2xl cursor-pointer">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-1">Anda akan menghapus cabang:</p>
            <p id="hapus-nama" class="text-base font-bold text-gray-800 mb-4"></p>
            <p class="text-xs text-red-500 mb-5">Tindakan ini tidak dapat dibatalkan. Semua data kamar dan admin cabang ini juga akan ikut terhapus.</p>
            <form id="form-hapus" action="" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('modal-hapus')"
                    class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 cursor-pointer">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // FUNGSI MEMBUKA & MENUTUP MODAL
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // FUNGSI MENGISI DATA KE DALAM FORM EDIT
    function openEditModal(data) {
        document.getElementById('edit-nama').value           = data.nama;
        document.getElementById('edit-alamat').value         = data.alamat;
        document.getElementById('edit-kamar').value          = data.kamar_count;
        document.getElementById('edit-admin-nama').value     = data.admin_nama;
        document.getElementById('edit-admin-username').value = data.admin_username;
        
        // Mencegah input jumlah kamar kurang dari yang sudah ada
        document.getElementById('edit-kamar').setAttribute('min', data.kamar_count);
        
        // Atur action form menuju ke route update
        document.getElementById('form-edit').action = `/superadmin/cabang/${data.id}`;
        
        openModal('modal-edit');
    }

    // FUNGSI MENGISI DATA KE DALAM FORM HAPUS
    function openDeleteModal(id, nama) {
        document.getElementById('hapus-nama').innerText = nama;
        document.getElementById('form-hapus').action    = `/superadmin/cabang/${id}`;
        openModal('modal-hapus');
    }
</script>
@endpush
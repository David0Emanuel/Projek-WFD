@extends('layouts.superadmin')
@section('title', 'Manajemen Cabang')
@section('page-title', 'Manajemen Cabang')

@section('content')

{{-- Alert --}}
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

{{-- Tabel --}}
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
                    {{-- Tombol Edit --}}
                    <button onclick='openEditModal({{ json_encode(["id" => $kos->id, "nama" => $kos->nama, "alamat" => $kos->alamat]) }})'
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

{{-- ===== MODAL TAMBAH ===== --}}
<div id="modal-tambah" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between bg-blue-600 px-5 py-4">
            <span class="text-base font-bold text-white">Tambah Cabang Baru</span>
            <button onclick="closeModal('modal-tambah')" class="text-blue-200 hover:text-white text-2xl cursor-pointer">&times;</button>
        </div>
        <form action="{{ route('superadmin.cabang.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Cabang</label>
                <input type="text" name="nama" required placeholder="Contoh: PuluBoys Siwalankerto"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat</label>
                <textarea name="alamat" rows="3" required placeholder="Jl. Siwalankerto No. 123, Surabaya"
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-tambah')"
                    class="flex-1 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div id="modal-edit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between bg-amber-500 px-5 py-4">
            <span class="text-base font-bold text-white">Edit Cabang</span>
            <button onclick="closeModal('modal-edit')" class="text-amber-100 hover:text-white text-2xl cursor-pointer">&times;</button>
        </div>
        <form id="form-edit" action="" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Cabang</label>
                <input type="text" id="edit-nama" name="nama" required
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat</label>
                <textarea id="edit-alamat" name="alamat" rows="3" required
                    class="w-full rounded-lg border border-gray-300 p-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
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

{{-- ===== MODAL HAPUS ===== --}}
<div id="modal-hapus" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between bg-red-600 px-5 py-4">
            <span class="text-base font-bold text-white">Konfirmasi Hapus</span>
            <button onclick="closeModal('modal-hapus')" class="text-red-200 hover:text-white text-2xl cursor-pointer">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-1">Anda akan menghapus cabang:</p>
            <p id="hapus-nama" class="text-base font-bold text-gray-800 mb-4"></p>
            <p class="text-xs text-red-500 mb-5">Tindakan ini tidak dapat dibatalkan. Semua data kamar di cabang ini juga akan terhapus.</p>
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
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openEditModal(data) {
        document.getElementById('edit-nama').value   = data.nama;
        document.getElementById('edit-alamat').value = data.alamat;
        document.getElementById('form-edit').action  = `/superadmin/cabang/${data.id}`;
        openModal('modal-edit');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('hapus-nama').innerText    = nama;
        document.getElementById('form-hapus').action       = `/superadmin/cabang/${id}`;
        openModal('modal-hapus');
    }
</script>
@endpush
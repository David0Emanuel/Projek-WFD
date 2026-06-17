@extends('layouts.admin')

@section('title', 'Manajemen Kamar')
@section('page-title', 'Kamar & Meteran')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 shadow-sm">
            <div class="flex items-center gap-3 text-green-800">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($kamars as $kamar)
        <div class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-900">Kamar {{ $kamar->nomor }}</h3>
                
                @if (strtolower($kamar->status) === 'kosong')
                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-green-700">Kosong</span>
                @elseif (strtolower($kamar->status) === 'booking')
                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-600">Booking</span>
                @elseif (strtolower($kamar->status) === 'maintenance')
                    <span class="rounded-full bg-gray-200 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-700">Maintenance</span>
                @else
                    <span class="rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-red-600">Terisi</span>
                @endif
            </div>
            
            <div class="mt-4 space-y-3">
                @if (strtolower($kamar->status) === 'kosong')
                    <div>
                        <p class="text-[10px] font-bold uppercase text-gray-500">Status</p>
                        <p class="text-sm font-semibold text-gray-800">Siap Disewakan</p>
                    </div>
                    <button type="button" onclick="openMaintenanceModal({{ $kamar->id }}, '{{ $kamar->nomor }}')" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-100 cursor-pointer">
                        Ubah ke Maintenance
                    </button>

                @elseif (strtolower($kamar->status) === 'maintenance')
                    <div>
                        <p class="text-[10px] font-bold uppercase text-gray-500">Status</p>
                        <p class="text-sm font-semibold text-gray-800">Sedang Diperbaiki</p>
                    </div>
                    <form action="{{ route('admin.kamar.kosong') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
                        <button type="submit" onclick="return confirm('Apakah perbaikan sudah selesai? Kamar akan dikembalikan ke status Kosong.')" class="w-full rounded-xl bg-green-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-green-700 cursor-pointer">
                            Selesai (Kembalikan Kosong)
                        </button>
                    </form>

                @elseif (strtolower($kamar->status) === 'booking')
                    <div>
                        <p class="text-[10px] font-bold uppercase text-gray-500">Pemesan (Menunggu Check-In)</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $kamar->user->nama ?? 'Menunggu Data' }}</p>
                    </div>
                    <a href="{{ route('admin.survey') }}" class="block w-full text-center rounded-xl border border-amber-500 bg-amber-50 px-4 py-2.5 text-sm font-bold text-amber-700 transition-colors hover:bg-amber-100">
                        Proses di Menu Check-in
                    </a>

                @else
                    <div>
                        <p class="text-[10px] font-bold uppercase text-gray-500">Penghuni Aktif</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $kamar->user->nama ?? 'Data Penghuni' }}</p>
                    </div>
                    <button type="button" onclick="openMeteranModal({{ $kamar->id }}, '{{ $kamar->nomor }}', {{ $kamar->user->id ?? 'null' }})" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-blue-700 cursor-pointer">
                        Input Meteran Listrik
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm font-medium text-gray-500">
            Belum ada data kamar di database.
        </div>
        @endforelse
    </div>
</div>

<div id="meteranModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 p-4 opacity-0 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-lg scale-95 transform rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300">
        
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold text-gray-900">Input Tagihan - Kamar <span id="teks_nomor_kamar_meteran" class="text-blue-600"></span></h3>
            <button type="button" onclick="closeMeteranModal()" class="rounded-full p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="formMeteran" action="/admin/transaksi/bulanan" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
            @csrf
            <input type="hidden" name="kamar_id" id="input_kamar_id_meteran">
            <input type="hidden" name="user_id" id="input_user_id_meteran">

            <div>
                <label for="angka_meteran" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Angka Meteran (kWh)</label>
                <input type="number" id="angka_meteran" name="angka_meteran" required placeholder="Contoh: 1540"
                       class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="total" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Total Tagihan (Rp)</label>
                <input type="number" id="total" name="total" required placeholder="Contoh: 1500000"
                       class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="foto_meteran" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Foto Bukti Meteran</label>
                <input type="file" id="foto_meteran" name="foto_meteran" required accept="image/jpeg, image/png, image/jpg"
                       class="w-full cursor-pointer rounded-xl border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none transition-all file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white hover:file:bg-blue-700">
                <p class="mt-1 text-[10px] text-gray-500">Maksimal 2MB. Format: JPG, PNG.</p>
            </div>

            <div class="mt-6 flex gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeMeteranModal()" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">Batal</button>
                <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-blue-700">Kirim Tagihan</button>
            </div>
        </form>
    </div>
</div>

<div id="maintenanceModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 p-4 opacity-0 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md scale-95 transform rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300 text-center">
        
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900">Ubah Status Kamar</h3>
        <p class="mt-2 text-sm text-gray-600">Apakah Anda yakin ingin mengubah status <strong>Kamar <span id="teks_nomor_kamar_maintenance"></span></strong> menjadi Maintenance? Kamar ini tidak akan bisa dibooking oleh pengunjung sementara waktu.</p>

        <form id="formMaintenance" action="{{ route('admin.kamar.maintenance') }}" method="POST" class="mt-6 flex gap-3">
            @csrf
            <input type="hidden" name="kamar_id" id="input_kamar_id_maintenance">
            
            <button type="button" onclick="closeMaintenanceModal()" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">Batal</button>
            <button type="submit" class="w-full rounded-xl bg-amber-500 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-amber-600">Ya, Ubah Status</button>
        </form>

    </div>
</div>

<script>
    // Modal Meteran
    const meteranModal = document.getElementById('meteranModal');
    const meteranContent = meteranModal.querySelector('div.scale-95');

    // Menambahkan parameter userId
    function openMeteranModal(id, nomor, userId) {
        document.getElementById('input_kamar_id_meteran').value = id;
        document.getElementById('input_user_id_meteran').value = userId; // Set user_id untuk Controller
        document.getElementById('teks_nomor_kamar_meteran').innerText = nomor;
        
        meteranModal.classList.remove('hidden');
        meteranModal.classList.add('flex');
        setTimeout(() => {
            meteranModal.classList.remove('opacity-0');
            meteranContent.classList.remove('scale-95');
        }, 10);
    }

    function closeMeteranModal() {
        meteranModal.classList.add('opacity-0');
        meteranContent.classList.add('scale-95');
        setTimeout(() => {
            meteranModal.classList.add('hidden');
            meteranModal.classList.remove('flex');
            document.getElementById('formMeteran').reset();
        }, 300);
    }

    // Modal Maintenance
    const maintenanceModal = document.getElementById('maintenanceModal');
    const maintenanceContent = maintenanceModal.querySelector('div.scale-95');

    function openMaintenanceModal(id, nomor) {
        document.getElementById('input_kamar_id_maintenance').value = id;
        document.getElementById('teks_nomor_kamar_maintenance').innerText = nomor;

        maintenanceModal.classList.remove('hidden');
        maintenanceModal.classList.add('flex');
        setTimeout(() => {
            maintenanceModal.classList.remove('opacity-0');
            maintenanceContent.classList.remove('scale-95');
        }, 10);
    }

    function closeMaintenanceModal() {
        maintenanceModal.classList.add('opacity-0');
        maintenanceContent.classList.add('scale-95');
        setTimeout(() => {
            maintenanceModal.classList.add('hidden');
            maintenanceModal.classList.remove('flex');
        }, 300);
    }
</script>
@endsection
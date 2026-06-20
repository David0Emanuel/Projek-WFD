@extends('layouts.admin')

@section('title', 'Manajemen Kamar')
@section('page-title', 'Kamar & Meteran')

@section('content')

<div id="toastSuccess" class="fixed top-5 right-5 z-[200] hidden transform transition-all duration-300 translate-y-[-20px] opacity-0 bg-white border-l-4 border-green-500 shadow-xl rounded-xl p-4">
    <div class="flex items-center gap-3">
        <div class="rounded-full bg-green-100 p-1 text-green-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-gray-900">Input Berhasil!</h4>
            <p class="text-xs text-gray-500">Data meteran dan tagihan terkirim ke database.</p>
        </div>
    </div>
</div>

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
            
            {{-- HEADER KARTU KAMAR --}}
            <div>
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
                
                {{-- LABEL TIPE DAN HARGA KAMAR --}}
                <div class="mt-3 flex flex-col items-start gap-1">
                    <span class="rounded bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-200">
                        {{ strtoupper($kamar->tipe_kamar ?? 'STANDARD') }}
                    </span>
                    <span class="text-xs font-semibold text-gray-500">Rp {{ number_format($kamar->harga ?? 0, 0, ',', '.') }}</span>
                </div>
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
                        <button type="submit" class="w-full rounded-xl bg-green-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-green-700 cursor-pointer">
                            Selesai (Kembalikan Kosong)
                        </button>
                    </form>

                @else
                    <div>
                        <p class="text-[10px] font-bold uppercase text-gray-500">Penghuni Aktif</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $kamar->tenant->nama ?? 'Tidak ada nama' }}</p>
                    </div>
                    <button type="button" onclick="openMeteranModal({{ $kamar->id }}, '{{ $kamar->nomor }}', {{ $kamar->tenant->id ?? 'null' }})" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-blue-700 cursor-pointer">
                        Input Meteran Listrik
                    </button>
                @endif

                {{-- TOMBOL EDIT SPESIFIKASI (Tampil di semua status kamar) --}}
                <button type="button" 
                    onclick="openEditKamarModal({{ $kamar->id }}, '{{ $kamar->nomor }}', '{{ $kamar->tipe_kamar ?? '' }}', {{ $kamar->harga ?? 0 }}, '{{ addslashes($kamar->spesifikasi ?? '') }}', '{{ addslashes($kamar->fasilitas ?? '') }}')" 
                    class="w-full rounded-xl border border-blue-600 bg-white px-4 py-2.5 text-sm font-bold text-blue-600 transition-colors hover:bg-blue-50 cursor-pointer">
                    ✏️ Edit Spesifikasi & Harga
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm font-medium text-gray-500">
            Belum ada data kamar di database.
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL INPUT METERAN --}}
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

        <form id="formMeteran" action="{{ route('transaksi.bulanan') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
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
                <button type="submit" id="btnSubmitMeteran" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-blue-700 flex justify-center items-center">
                    <span>Kirim Tagihan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL MAINTENANCE --}}
<div id="maintenanceModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 p-4 opacity-0 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md scale-95 transform rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300 text-center">
        
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900">Ubah Status Kamar</h3>
        <p class="mt-2 text-sm text-gray-600">Apakah Anda yakin ingin mengubah status <strong>Kamar <span id="teks_nomor_kamar_maintenance"></span></strong> menjadi Maintenance?</p>

        <form action="{{ route('admin.kamar.maintenance') }}" method="POST" class="mt-6 flex gap-3">
            @csrf
            <input type="hidden" name="kamar_id" id="input_kamar_id_maintenance">
            
            <button type="button" onclick="closeMaintenanceModal()" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">Batal</button>
            <button type="submit" class="w-full rounded-xl bg-amber-500 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-amber-600">Ya, Ubah Status</button>
        </form>

    </div>
</div>

{{-- MODAL EDIT SPESIFIKASI & HARGA KAMAR --}}
<div id="editKamarModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 p-4 opacity-0 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-lg scale-95 transform rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold text-gray-900">Edit Kamar <span id="teks_kamar_nomor" class="text-blue-600"></span></h3>
            <button type="button" onclick="closeEditKamarModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form action="{{ route('admin.kamar.update-detail') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="kamar_id" id="edit_kamar_id">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Tipe Kamar</label>
                    <input type="text" name="tipe_kamar" id="edit_tipe_kamar" required class="w-full rounded-lg border border-gray-300 p-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Harga Bulanan</label>
                    <input type="number" name="harga" id="edit_harga_kamar" required class="w-full rounded-lg border border-gray-300 p-2.5 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Spesifikasi (Pisahkan dengan koma)</label>
                <textarea name="spesifikasi" id="edit_spesifikasi" rows="2" placeholder="Contoh: Ukuran 3x4m, Kasur Springbed, Kamar Mandi Dalam" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Fasilitas Tambahan (Pisahkan dengan koma)</label>
                <textarea name="fasilitas" id="edit_fasilitas" rows="2" placeholder="Contoh: WiFi Gratis, Air Minum, Parkir Motor" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Foto Kamar (Opsional)</label>
                <input type="file" name="foto_kamar" accept="image/jpeg, image/png, image/jpg" class="w-full rounded-lg border border-gray-300 p-2 text-sm">
            </div>

            {{-- Fitur Update Massal --}}
            <div class="mt-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3">
                <div class="flex h-5 items-center">
                    <input id="apply_to_all" name="apply_to_all" type="checkbox" value="1" class="h-4 w-4 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                </div>
                <div class="text-sm">
                    <label for="apply_to_all" class="font-bold text-amber-800">Terapkan ke Semua Kamar <span id="label_tipe_kamar_massal" class="uppercase"></span></label>
                    <p class="text-[10px] text-amber-700">Centang ini jika ingin menyalin harga, spesifikasi, dan foto ini ke seluruh kamar bertipe sama di cabang ini.</p>
                </div>
            </div>

            <div class="flex gap-3 pt-3">
                <button type="button" onclick="closeEditKamarModal()" class="flex-1 rounded-xl border border-gray-300 py-3 text-sm font-bold text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 rounded-xl bg-blue-600 py-3 text-sm font-bold text-white hover:bg-blue-700 cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- MANAJEMEN OPERASI MODAL METERAN ---
    const meteranModal = document.getElementById('meteranModal');
    const meteranContent = meteranModal.querySelector('div.scale-95');

    function openMeteranModal(id, nomor, userId) {
        document.getElementById('input_kamar_id_meteran').value = id;
        document.getElementById('input_user_id_meteran').value = userId;
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

    // --- MANAJEMEN OPERASI MODAL MAINTENANCE ---
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

    // --- MANAJEMEN OPERASI MODAL EDIT KAMAR (BARU) ---
    const editKamarModal = document.getElementById('editKamarModal');
    const editKamarContent = editKamarModal.querySelector('div.scale-95');

    function openEditKamarModal(id, nomor, tipe, harga, spesifikasi, fasilitas) {
        document.getElementById('edit_kamar_id').value = id;
        document.getElementById('teks_kamar_nomor').innerText = nomor;
        document.getElementById('edit_tipe_kamar').value = tipe;
        document.getElementById('edit_harga_kamar').value = harga;
        document.getElementById('edit_spesifikasi').value = spesifikasi;
        document.getElementById('edit_fasilitas').value = fasilitas;
        document.getElementById('label_tipe_kamar_massal').innerText = tipe; // Tipe untuk Checkbox Massal

        editKamarModal.classList.remove('hidden');
        editKamarModal.classList.add('flex');
        setTimeout(() => { 
            editKamarModal.classList.remove('opacity-0'); 
            editKamarContent.classList.remove('scale-95'); 
        }, 10);
    }

    function closeEditKamarModal() {
        editKamarModal.classList.add('opacity-0');
        editKamarContent.classList.add('scale-95');
        setTimeout(() => { 
            editKamarModal.classList.add('hidden'); 
            editKamarModal.classList.remove('flex'); 
        }, 300);
    }

    // --- TOAST NOTIFIKASI HANDLING ---
    function showSuccessToast() {
        const toast = document.getElementById('toastSuccess');
        toast.classList.remove('hidden');
        
        setTimeout(() => {
            toast.classList.remove('translate-y-[-20px]', 'opacity-0');
        }, 10);

        setTimeout(() => {
            toast.classList.add('translate-y-[-20px]', 'opacity-0');
            setTimeout(() => { toast.classList.add('hidden'); }, 300);
        }, 3000);
    }

    // --- PROD AJAX SUBMIT (HANDLING ERROR SECARA ELEGAN) ---
    document.getElementById('formMeteran').addEventListener('submit', function(e) {
        e.preventDefault(); 

        let form = this;
        let formData = new FormData(form);
        let btnSubmit = document.getElementById('btnSubmitMeteran');
        let btnText = btnSubmit.querySelector('span');
        let originalText = btnText.innerText;

        // Pasang state loading tombol
        btnText.innerText = 'Mengirim...';
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            const data = await response.json();
            
            if (response.ok) {
                // Jika status HTTP Berhasil (200)
                closeMeteranModal(); 
                showSuccessToast();  
            } else {
                // Jika validasi gagal (422) atau server bermasalah (500)
                let errorMessage = data.message || 'Terjadi kesalahan sistem.';
                
                if (data.errors) {
                    errorMessage = Object.values(data.errors).flat().join('\n');
                }
                throw new Error(errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal Menyimpan Data:\n' + error.message);
        })
        .finally(() => {
            btnText.innerText = originalText;
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-70', 'cursor-not-allowed');
        });
    });
</script>
@endsection
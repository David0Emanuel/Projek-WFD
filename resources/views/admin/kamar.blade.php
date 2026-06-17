@extends('layouts.admin')

@section('title', 'Manajemen Kamar')
@section('page-title', 'Kamar & Meteran')

@section('content')
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    
    @for ($i = 101; $i <= 106; $i++)
    <div class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900">Kamar {{ $i }}</h3>
            @if ($i % 3 == 0)
                <span class="rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-red-600">Kosong</span>
            @else
                <span class="rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-green-700">Terisi</span>
            @endif
        </div>
        
        <div class="mt-4 space-y-3">
            @if ($i % 3 != 0)
                <div>
                    <p class="text-[10px] font-bold uppercase text-gray-500">Penghuni</p>
                    <p class="text-sm font-semibold text-gray-800">Budi Santoso</p>
                </div>
                <button type="button" onclick="bukaModalMeteran('{{ $i }}', 1, 'Budi Santoso')" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-blue-700">
                    Input Meteran Listrik
                </button>
            @else
                <div>
                    <p class="text-[10px] font-bold uppercase text-gray-500">Status</p>
                    <p class="text-sm font-semibold text-gray-800">Siap Disewakan</p>
                </div>
                <button type="button" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-100">
                    Ubah Status ke Maintenance
                </button>
            @endif
        </div>
    </div>
    @endfor

</div>

<div id="modalMeteran" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center">
    
    <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
            <h3 class="text-lg font-bold text-gray-900">Input Tagihan Bulanan</h3>
            <button onclick="tutupModalMeteran()" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="/admin/transaksi/bulanan" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <input type="hidden" name="kamar_id" id="modal_kamar_id">
            <input type="hidden" name="user_id" id="modal_user_id">

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Penghuni / Kamar</label>
                <input type="text" id="modal_info_penghuni" readonly class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 outline-none">
            </div>

            <div>
                <label for="angka_meteran" class="block text-xs font-bold text-gray-700 uppercase">Angka Meteran Listrik</label>
                <input type="number" name="angka_meteran" id="angka_meteran" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label for="total" class="block text-xs font-bold text-gray-700 uppercase">Total Tagihan (Rp)</label>
                <input type="number" name="total" id="total" required class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label for="foto_meteran" class="block text-xs font-bold text-gray-700 uppercase">Upload Foto Meteran</label>
                <input type="file" name="foto_meteran" id="foto_meteran" accept="image/png, image/jpeg, image/jpg" required class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-xs file:font-bold file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-[10px] text-gray-500">Format: JPG, PNG. Maks: 2MB.</p>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-blue-700">
                    Generate Tagihan Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalMeteran(kamarId, userId, namaPenghuni) {
        document.getElementById('modal_kamar_id').value = kamarId;
        document.getElementById('modal_user_id').value = userId;
        document.getElementById('modal_info_penghuni').value = namaPenghuni + ' (Kamar ' + kamarId + ')';
        
        document.getElementById('modalMeteran').classList.remove('hidden');
    }

    function tutupModalMeteran() {
        document.getElementById('modalMeteran').classList.add('hidden');
    }
</script>
@endsection
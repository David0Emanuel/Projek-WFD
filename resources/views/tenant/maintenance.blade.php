@extends('layouts.tenant')

@section('title', 'Maintenance Tickets')
@section('page-title', 'Maintenance Tickets')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Kolom Kiri: Form Buat Tiket Keluhan -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
        <h3 class="text-sm font-bold text-gray-700 border-b border-gray-150 pb-3 mb-4">Buat Tiket Keluhan</h3>
        
        <form action="{{ route('tenant.maintenance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <!-- Deskripsi Keluhan -->
            <div>
                <label for="deskripsi" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase">Deskripsi Detail Kerusakan</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" required placeholder="Jelaskan detail kerusakan..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
            </div>

            <!-- Foto Bukti -->
            <div>
                <label for="foto" class="block text-xs font-bold text-gray-500 mb-1.5 uppercase">Unggah Foto Bukti Kendala</label>
                <input type="file" name="foto" id="foto" accept="image/*"
                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border file:border-gray-300 file:text-xs file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 cursor-pointer">
            </div>

            <!-- Tombol Kirim -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 bg-blue-500 hover:bg-blue-600 border border-blue-600 rounded-lg font-bold text-sm text-white cursor-pointer transition-colors">
                    Kirim Maintenance Tickets
                </button>
            </div>
        </form>
    </div>

    <!-- Kolom Kanan: Lacak Progres Perbaikan -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 flex flex-col">
        <h3 class="text-sm font-bold text-gray-700 border-b border-gray-150 pb-3 mb-4">Lacak Progres Perbaikan</h3>
        
        <div class="flex-1 overflow-y-auto space-y-6 max-h-[500px] pr-1">
            @if($tickets->isEmpty())
                <div class="text-center py-12 text-gray-400 text-sm">
                    Belum ada tiket keluhan yang diajukan.
                </div>
            @else
                @foreach($tickets as $ticket)
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50/50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">Keluhan #{{ $ticket->id }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->deskripsi }}</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded border 
                                @if($ticket->status === 'Selesai') bg-green-100 text-green-700 border-green-300
                                @elseif($ticket->status === 'Proses') bg-blue-100 text-blue-700 border-blue-300
                                @else bg-gray-100 text-gray-600 border-gray-300 @endif uppercase">
                                {{ $ticket->status === 'Proses' ? 'Berjalan' : ($ticket->status === 'Selesai' ? 'Selesai' : 'Pending') }}
                            </span>
                        </div>

                        @if($ticket->foto)
                            <div class="mt-2.5 mb-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Bukti Foto:</p>
                                <a href="{{ asset($ticket->foto) }}" target="_blank">
                                    <img src="{{ asset($ticket->foto) }}" alt="Bukti Kendala" class="h-20 w-auto rounded border border-gray-200 object-cover hover:opacity-90">
                                </a>
                            </div>
                        @endif

                        <!-- Visual Timeline Tracker -->
                        <div class="mt-4 border-t border-gray-200 pt-4 space-y-4 relative">
                            <!-- Garis vertikal penghubung titik timeline -->
                            <div class="absolute left-[11px] top-6 bottom-6 w-0.5 bg-gray-200"></div>

                            <!-- Tahap 1: Selesai Ditangani -->
                            <div class="flex items-start gap-3 relative z-10">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 
                                    @if($ticket->status === 'Selesai') bg-green-500 border-green-500 text-white
                                    @else bg-white border-gray-200 text-gray-300 @endif">
                                    &check;
                                </div>
                                <div class="flex-1 text-xs">
                                    <div class="flex justify-between font-bold @if($ticket->status === 'Selesai') text-green-600 @else text-gray-400 @endif">
                                        <span>Selesai Ditangani</span>
                                        @if($ticket->status === 'Selesai')
                                            <span>Selesai Ditangani</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tahap 2: Diproses -->
                            <div class="flex items-start gap-3 relative z-10">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 
                                    @if($ticket->status === 'Proses' || $ticket->status === 'Selesai') bg-blue-500 border-blue-500 text-white
                                    @else bg-white border-gray-200 text-gray-300 @endif">
                                    &bull;
                                </div>
                                <div class="flex-1 text-xs">
                                    <div class="flex justify-between font-bold @if($ticket->status === 'Proses' || $ticket->status === 'Selesai') text-blue-600 @else text-gray-400 @endif">
                                        <span>Diproses</span>
                                        @if($ticket->status === 'Proses')
                                            <span>Berjalan</span>
                                        @endif
                                    </div>
                                    @if($ticket->status === 'Proses' || $ticket->status === 'Selesai')
                                        <p class="text-gray-600 mt-0.5">{{ $ticket->status_message }}</p>
                                    @else
                                        <p class="text-gray-400 mt-0.5">Keluhan akan segera diproses.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Tahap 3: Tiket Berhasil Diajukan -->
                            <div class="flex items-start gap-3 relative z-10">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 bg-blue-500 border-blue-500 text-white">
                                    &bull;
                                </div>
                                <div class="flex-1 text-xs">
                                    <div class="flex justify-between font-bold text-blue-600">
                                        <span>Tiket Berhasil Diajukan</span>
                                        @if($ticket->status === 'Pending')
                                            <span>Pending</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-400 text-[10px] mt-0.5">Tanggal Pengajuan: {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            @endif
        </div>
    </div>

</div>

<!-- Popup Sukses Submit (Yellow Modal Card) -->
@if(session('success'))
<div id="success-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" onclick="closeSuccessModal()">
    <div class="w-full max-w-xs bg-amber-400 rounded-lg border-2 border-gray-300 shadow-xl p-6 text-center transform transition-all" onclick="event.stopPropagation()">
        <!-- Simbol Checkmark -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full border-4 border-gray-800 bg-white mb-4">
            <svg class="h-10 w-10 text-gray-800" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>
        
        <h3 class="text-sm font-bold text-gray-800 mb-1">Sukses!</h3>
        <p class="text-xs text-gray-700 font-semibold mb-4">{{ session('success') }}</p>
        
        <button type="button" onclick="closeSuccessModal()"
                class="w-full py-2 bg-gray-800 text-white text-xs font-bold rounded-lg border border-gray-900 hover:bg-black cursor-pointer">
            Tutup
        </button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function closeSuccessModal() {
        const modal = document.getElementById('success-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endpush

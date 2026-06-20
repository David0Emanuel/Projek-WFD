@extends('layouts.visitor')

@section('title', 'Detail Kos - KosInAja')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">
            <ul class="text-sm font-bold text-red-800 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 shadow-sm">
            <div class="flex items-center gap-3 text-green-800">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">
            <div class="flex items-center gap-3 text-red-800">
                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        </div>
    @endif
    
    <nav class="flex flex-wrap items-center gap-2 text-sm font-medium text-gray-500">
        <a href="{{ route('home') }}" class="transition-colors hover:text-gray-900">Beranda</a>
        <span>/</span>
        <a href="{{ route('visitor.branches') }}" class="transition-colors hover:text-gray-900">Daftar Cabang</a>
        <span>/</span>
        <span class="text-gray-800">{{ $branch->nama ?? 'Nama Kos' }}</span>
    </nav>

    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $branch->nama ?? 'Nama Kos' }}</h1>
        <p class="mt-2 text-sm text-gray-600">
            <svg class="mr-1 inline-block h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
            {{ $branch->alamat ?? 'Alamat Kos' }}
        </p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-[2fr_1fr]">

        <div class="space-y-8">
            
            {{-- BAGIAN FOTO UTAMA KOS --}}
            <div class="aspect-video w-full overflow-hidden rounded-2xl border border-gray-200 bg-gray-100 shadow-sm">
                @if(isset($branch->foto) && $branch->foto)
                    <img src="{{ asset('storage/' . $branch->foto) }}" alt="Foto {{ $branch->nama }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full w-full flex-col items-center justify-center text-gray-400">
                        <svg class="mb-2 h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">Belum ada foto cabang</span>
                    </div>
                @endif
            </div>

            {{-- BAGIAN DESKRIPSI KOS (Ditambahkan ke sini) --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900">Tentang {{ $branch->nama }}</h2>
                <div class="mt-3 text-sm text-gray-600 leading-relaxed">
                    @if(isset($branch->deskripsi) && $branch->deskripsi)
                        {!! nl2br(e($branch->deskripsi)) !!}
                    @else
                        <p class="italic text-gray-400">Belum ada deskripsi untuk kos ini.</p>
                    @endif
                </div>
            </div>

            <div>
                <h2 class="mb-4 text-xl font-bold text-gray-900">Tipe Kamar Tersedia</h2>
                
                @php
                    $availableRoomsByType = isset($branch) ? $branch->kamars->where('status', 'Kosong')->groupBy('tipe_kamar') : collect();
                @endphp

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @forelse($availableRoomsByType as $tipe => $kamars)
                        @php
                            $contohKamar = $kamars->first(); 
                        @endphp
                        
                        <article class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                            <div class="aspect-[4/3] w-full border-b border-gray-100 bg-gray-100">
                                {{-- Nanti foto_kamar akan dipanggil di sini --}}
                                @if(isset($contohKamar->foto_kamar) && $contohKamar->foto_kamar)
                                    <img src="{{ asset('storage/' . $contohKamar->foto_kamar) }}" alt="Kamar {{ $tipe }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-gray-300">
                                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-2 flex items-start justify-between gap-2">
                                    <h3 class="text-lg font-bold text-gray-900">Kamar <span class="uppercase text-blue-600">{{ $tipe }}</span></h3>
                                    <span class="shrink-0 rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-green-700">Tersedia {{ $kamars->count() }}</span>
                                </div>
                                <p class="text-xl font-black text-gray-900">Rp {{ number_format($contohKamar->harga, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">/ bulan</span></p>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-sm font-medium text-gray-500">
                            Mohon maaf, saat ini tidak ada kamar yang kosong di cabang ini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- CATATAN: Ini masih bersifat Statis (Hardcode) sampai kita mengupdate database tabel Kamar --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                
                {{-- KOTAK SPESIFIKASI --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Spesifikasi Kamar Utama</h2>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        @php $contohKamar = isset($branch) && $branch->kamars->count() > 0 ? $branch->kamars->first() : null; @endphp
                        
                        @if($contohKamar && $contohKamar->spesifikasi)
                            {{-- Memecah teks koma menjadi list ke bawah --}}
                            @foreach(explode(',', $contohKamar->spesifikasi) as $spek)
                                <li class="flex items-center gap-3"><span class="text-blue-500">❖</span> {{ trim($spek) }}</li>
                            @endforeach
                        @else
                            <li class="italic text-gray-400">Belum ada rincian spesifikasi.</li>
                        @endif
                    </ul>
                </div>

                {{-- KOTAK FASILITAS --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900">Fasilitas Tersedia</h2>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        @if($contohKamar && $contohKamar->fasilitas)
                            @foreach(explode(',', $contohKamar->fasilitas) as $fasilitas)
                                <li class="flex items-center gap-3"><span class="text-green-500">✓</span> {{ trim($fasilitas) }}</li>
                            @endforeach
                        @else
                            <li class="italic text-gray-400">Belum ada rincian fasilitas.</li>
                        @endif
                    </ul>
                </div>
                
            </div>
            
        </div>

        <div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-500">Mulai Dari</p>
                <div class="mt-1 flex items-end gap-1">
                    <span class="text-3xl font-black text-gray-900">
                        {{-- Logika otomatis mencari harga terendah --}}
                        Rp {{ isset($branch) && $branch->kamars->count() > 0 ? number_format($branch->kamars->min('harga'), 0, ',', '.') : '0' }}
                    </span>
                    <span class="mb-1 text-sm font-medium text-gray-500">/ bulan</span>
                </div>

                <hr class="my-6 border-dashed border-gray-200">

                <form id="formBooking" action="{{ route('visitor.booking.store') }}" method="POST" class="space-y-5" novalidate>
                    @csrf
                    
                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Status Kamar</label>
                        @if(isset($branch) && $branch->kamars->where('status', 'Kosong')->count() > 0)
                            <div class="flex items-center justify-between rounded-xl border border-green-200 bg-green-50 px-4 py-3">
                                <span class="text-sm font-bold text-green-700">Tersedia</span>
                                <span class="text-xs font-bold text-green-600">{{ $branch->kamars->where('status', 'Kosong')->count() }} Kamar Kosong</span>
                            </div>
                        @else
                            <div class="flex items-center justify-between rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                                <span class="text-sm font-bold text-red-700">Penuh</span>
                            </div>
                        @endif
                    </div>

                    @if(isset($branch) && $branch->kamars->where('status', 'Kosong')->count() > 0)
                    <div>
                        <label for="kamar_id" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Pilih Kamar</label>
                        <select id="kamar_id" name="kamar_id" required
                                class="w-full cursor-pointer rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                            <option value="" disabled selected>-- Pilih Kamar Tersedia --</option>
                            @foreach($branch->kamars->where('status', 'Kosong') as $kamar)
                                <option value="{{ $kamar->id }}">
                                    Kamar {{ $kamar->nomor }} ({{ ucfirst($kamar->tipe_kamar) }}) - Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div>
                        <label for="tanggal_masuk" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Rencana Masuk</label>
                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" min="{{ date('Y-m-d', strtotime('tomorrow')) }}" required
                               class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    </div>

                    <hr class="my-6 border-dashed border-gray-200">

                    <div class="flex flex-col gap-3">
                        @auth
                            <button type="button" onclick="openBookingModal()" class="w-full cursor-pointer rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">
                                Ajukan Booking
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">
                                Login untuk Booking
                            </a>
                        @endauth
                        
                        <button type="button" onclick="openSurveyModal()" class="w-full cursor-pointer rounded-xl border border-blue-600 bg-white px-4 py-3.5 text-sm font-bold text-blue-600 transition-all hover:bg-blue-50 hover:shadow-sm">
                            Ajukan Survey Lokasi
                        </button>

                        <a href="https://wa.me/{{ config('puluboys.wa_admin', '6282329777201') }}" target="_blank" 
                           class="flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3.5 text-sm font-bold text-gray-700 transition-all hover:bg-gray-50 hover:shadow-sm">
                           <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
                            </svg>
                            Tanya Admin
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<div id="bookingModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 p-4 opacity-0 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md scale-95 transform rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300">
        
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold text-gray-900">Selesaikan Pembayaran</h3>
            <button type="button" onclick="closeBookingModal()" class="rounded-full p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-5 space-y-5 text-center">
            <div class="rounded-xl bg-red-50 p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-red-500">Waktu Pembayaran Tersisa</p>
                <p id="bookingTimer" class="mt-1 font-mono text-4xl font-black tracking-tight text-red-600">24:00:00</p>
            </div>

            <p class="text-xs leading-relaxed text-gray-500">
                Silakan lakukan pembayaran sesuai instruksi. Klik tombol di bawah jika akan diarahkan ke Payment Gateway / Upload Bukti Transfer.
            </p>

            <div class="grid grid-cols-2 gap-3 border-t border-gray-100 pt-5">
                <button type="button" onclick="closeBookingModal()" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">Batal</button>
                <button type="button" onclick="document.getElementById('formBooking').submit()" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-blue-700">Bayar Sekarang</button>
            </div>
        </div>

    </div>
</div>

<div id="surveyModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 p-4 opacity-0 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md scale-95 transform rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300">
        
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold text-gray-900">Ajukan Survey Lokasi</h3>
            <button type="button" onclick="closeSurveyModal()" class="rounded-full p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('visitor.survey.store') }}" method="POST" class="mt-5 space-y-4">
            @csrf
            <input type="hidden" name="kos_id" value="{{ $branch->id ?? '' }}">

            <div>
                <label for="tanggal_survey" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Tanggal Survey</label>
                <input type="date" id="tanggal_survey" name="tanggal_survey" min="{{ date('Y-m-d', strtotime('tomorrow')) }}" required 
                       class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="jam_survey" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Jam Survey</label>
                <input type="time" id="jam_survey" name="jam_survey" min="{{ date('H:i') }}" required 
                       class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="no_wa" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Nomor WhatsApp</label>
                <input type="text" id="no_wa_survey" name="no_wa" placeholder="0812xxxxxxx" value="{{ Auth::check() ? Auth::user()->no_wa : '' }}" required 
                       class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-900 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                <p class="mt-1 text-[10px] text-gray-500">Admin akan menghubungi nomor ini untuk konfirmasi.</p>
            </div>

            <div class="mt-6 flex gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeSurveyModal()" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">Batal</button>
                <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition-colors hover:bg-blue-700">Kirim Pengajuan</button>
            </div>
        </form>

    </div>
</div>

<script>
    // Konfigurasi Survey Modal
    const surveyModal = document.getElementById('surveyModal');
    const surveyContent = surveyModal.querySelector('div.scale-95');

    function openSurveyModal() {
        surveyModal.classList.remove('hidden');
        surveyModal.classList.add('flex');
        setTimeout(() => {
            surveyModal.classList.remove('opacity-0');
            surveyContent.classList.remove('scale-95');
        }, 10);
    }

    function closeSurveyModal() {
        surveyModal.classList.add('opacity-0');
        surveyContent.classList.add('scale-95');
        setTimeout(() => {
            surveyModal.classList.add('hidden');
            surveyModal.classList.remove('flex');
        }, 300);
    }

    // Konfigurasi Booking Modal & Timer
    const bookingModal = document.getElementById('bookingModal');
    const bookingContent = bookingModal.querySelector('div.scale-95');
    const displayTimer = document.getElementById('bookingTimer');
    let timerInterval;

    function openBookingModal() {
        // Validasi simpel sebelum buka modal: Cek apakah kamar dan tanggal sudah diisi
        const kamar = document.getElementById('kamar_id');
        const tanggal = document.getElementById('tanggal_masuk');
        
        if(kamar && kamar.value === "") {
            alert('Silakan pilih kamar terlebih dahulu!');
            kamar.focus();
            return;
        }
        if(tanggal && tanggal.value === "") {
            alert('Silakan tentukan rencana masuk terlebih dahulu!');
            tanggal.focus();
            return;
        }

        bookingModal.classList.remove('hidden');
        bookingModal.classList.add('flex');
        setTimeout(() => {
            bookingModal.classList.remove('opacity-0');
            bookingContent.classList.remove('scale-95');
        }, 10);

        // Jalankan Timer 24 Jam (86400 detik)
        startTimer(86400, displayTimer);
    }

    function closeBookingModal() {
        bookingModal.classList.add('opacity-0');
        bookingContent.classList.add('scale-95');
        setTimeout(() => {
            bookingModal.classList.add('hidden');
            bookingModal.classList.remove('flex');
            clearInterval(timerInterval); // Matikan timer saat ditutup
        }, 300);
    }

    function startTimer(duration, display) {
        clearInterval(timerInterval); // Reset timer jika sudah pernah jalan
        let timer = duration, hours, minutes, seconds;
        
        timerInterval = setInterval(function () {
            hours = parseInt(timer / 3600, 10);
            minutes = parseInt((timer % 3600) / 60, 10);
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = hours + ":" + minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(timerInterval);   
                display.textContent = "00:00:00";
                closeBookingModal();
                alert('Waktu pembayaran habis. Silakan ajukan booking kembali.');
            }
        }, 1000);
    }
</script>
@endsection
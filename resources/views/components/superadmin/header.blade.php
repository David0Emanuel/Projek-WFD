@php
    // Mengambil notifikasi pembayaran & survey yang belum dibaca
    $notifikasis = \App\Models\AdminLog::whereIn('tipe', ['pembayaran', 'survey'])
                                        ->where('is_read', false)
                                        ->latest()
                                        ->get();
    $unreadCount = $notifikasis->count();
@endphp

<header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden cursor-pointer hover:text-gray-900" onclick="toggleSidebar()">
        <span class="sr-only">Buka menu sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 items-center justify-between gap-x-4 self-stretch lg:gap-x-6">
        
        <h1 class="text-xl font-bold text-gray-800 tracking-tight">@yield('page-title')</h1>

        <div class="flex items-center gap-x-4 lg:gap-x-6">
            
            <div class="relative">
                <button type="button" id="notification-button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 relative cursor-pointer focus:outline-none">
                    <span class="sr-only">Lihat notifikasi</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    
                    @if($unreadCount > 0)
                        <span id="notif-badge" class="absolute top-2 right-2 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <div id="notification-dropdown" class="absolute right-0 top-12 z-50 mt-2 w-80 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-gray-900/5 hidden">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900">Notifikasi Masuk</h3>
                        @if($unreadCount > 0)
                            <button onclick="clearNotif()" class="text-xs font-semibold text-blue-600 hover:text-blue-800 cursor-pointer">Tandai dibaca</button>
                        @endif
                    </div>
                    <div class="max-h-64 overflow-y-auto p-2 space-y-1">
                        @forelse($notifikasis as $notif)
                            <div class="p-3 rounded-lg text-xs leading-relaxed {{ $notif->tipe == 'pembayaran' ? 'bg-green-50 text-green-800' : 'bg-blue-50 text-blue-800' }}">
                                <div class="flex items-center gap-2 font-bold mb-0.5">
                                    <span>{{ $notif->tipe == 'pembayaran' ? '💰 Pembayaran' : '📋 Survey Visitor' }}</span>
                                    <span class="text-[10px] text-gray-400 font-normal ml-auto">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                {{ $notif->pesan }}
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-gray-400">Tidak ada notifikasi baru</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            <div class="relative">
                <button type="button" id="profile-button" class="-m-1.5 flex items-center p-1.5 cursor-pointer focus:outline-none" aria-expanded="false" aria-haspopup="true">
                    <span class="sr-only">Buka menu profil</span>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="text-sm font-bold text-gray-700" aria-hidden="true">Super Admin</span>
                        <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>

                <div id="profile-popup" class="absolute right-0 top-14 z-50 mt-2 w-48 origin-top-right rounded-lg bg-white shadow-xl ring-1 ring-gray-900/5 hidden focus:outline-none">
                    <div class="px-4 py-3 border-b border-gray-100 bg-slate-50 rounded-t-lg">
                        <p class="text-sm font-bold text-gray-800">Administrator Utama</p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">superadmin@kosinaja.com</p>
                    </div>
                    
                    <div class="py-1">
                        <button type="button" onclick="openLogModal()" class="block w-full text-left px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 cursor-pointer focus:outline-none">
                            Log Aktivitas Sesi
                        </button>
                    </div>
                    
                    <div class="border-t border-gray-100 py-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 cursor-pointer">
                                Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

<div id="log-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 hidden">
    <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-gray-900/5">
        
        <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
            <h3 class="text-sm font-bold text-gray-950 flex items-center gap-2">
                Riwayat Sesi Login
            </h3>
            <button onclick="closeLogModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer focus:outline-none">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="max-h-64 overflow-y-auto pr-1">
            <div id="log-loading" class="py-8 text-center text-xs text-gray-400 font-medium">
                Memuat data riwayat sesi...
            </div>
            <ul id="log-list" class="space-y-1.5 hidden">
                </ul>
        </div>

        <div class="mt-5 border-t border-gray-100 pt-3 flex justify-end">
            <button onclick="closeLogModal()" class="rounded-xl bg-gray-100 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-200 cursor-pointer focus:outline-none">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notifBtn = document.getElementById('notification-button');
        const notifDropdown = document.getElementById('notification-dropdown');
        const profileBtn = document.getElementById('profile-button');
        const profilePopup = document.getElementById('profile-popup');

        // Toggle Notifikasi
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
            profilePopup.classList.add('hidden');
        });

        // Toggle Profile
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profilePopup.classList.toggle('hidden');
            notifDropdown.classList.add('hidden');
        });

        // Tutup saat klik di luar area
        document.addEventListener('click', function() {
            notifDropdown.classList.add('hidden');
            profilePopup.classList.add('hidden');
        });
    });

    function clearNotif() {
        fetch("{{ route('superadmin.notifications.clear') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const badge = document.getElementById('notif-badge');
                if(badge) badge.remove();
                setTimeout(() => {
                    location.reload();
                }, 300);
            }
        });
    }

    // Fungsi untuk membuka Modal Log Aktivitas Sesi
    function openLogModal() {
        const modal = document.getElementById('log-modal');
        const loading = document.getElementById('log-loading');
        const list = document.getElementById('log-list');
        
        // Sembunyikan dropdown profil terlebih dahulu
        document.getElementById('profile-popup').classList.add('hidden');
        
        // Tampilkan modal pop-up dan indikator loading
        modal.classList.remove('hidden');
        loading.classList.remove('hidden');
        list.classList.add('hidden');
        list.innerHTML = ''; // Reset list lama

        fetch("{{ route('superadmin.log-aktivitas') }}")
            .then(response => response.json())
            .then(data => {
                loading.classList.add('hidden');
                list.classList.remove('hidden');

                if (data.length === 0) {
                    list.innerHTML = `<li class="py-6 text-center text-xs text-gray-400">Tidak ada riwayat login tercatat</li>`;
                    return;
                }

                data.forEach(log => {
                    const li = document.createElement('li');
                    li.className = 'p-3 bg-slate-50 border border-slate-100/70 rounded-xl text-xs flex justify-between items-start gap-4 leading-relaxed';
                    li.innerHTML = `
                        <span class="text-gray-700 font-medium">${log.pesan}</span>
                        <span class="text-[10px] text-gray-400 shrink-0 font-normal mt-0.5">${log.waktu}</span>
                    `;
                    list.appendChild(li);
                });
            })
            .catch(err => {
                loading.classList.add('hidden');
                list.classList.remove('hidden');
                list.innerHTML = `<li class="py-4 text-center text-xs text-red-500 font-semibold">Gagal memuat data log keamanan</li>`;
            });
    }

    // Fungsi untuk menutup Modal Log Aktivitas Sesi
    function closeLogModal() {
        document.getElementById('log-modal').classList.add('hidden');
    }
</script>
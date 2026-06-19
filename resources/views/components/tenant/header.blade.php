<header class="sticky top-0 z-20 bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3 lg:px-6">

        <div class="flex items-center gap-3">
            <button id="sidebar-toggle" type="button" onclick="toggleSidebar()"
                    class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <h2 class="text-lg font-bold text-gray-800 lg:text-xl">@yield('page-title', 'Main Dashboard')</h2>
        </div>

        @php
            $user = auth()->user();
            
            // Logika Data Profil
            $namaLengkap = $user->nama ?? $user->username ?? 'Admin';
            $inisial = strtoupper(substr($namaLengkap, 0, 2));
            $namaCabang = $user->kos ? $user->kos->nama : 'Cabang Utama';
            
            // Logika Data Pengumuman (Notifikasi)
            // Mengambil pengumuman khusus cabang ini ATAU pengumuman global (kos_id = null)
            $pengumumans = \App\Models\Pengumuman::where('kos_id', $user->kos_id)
                            ->orWhereNull('kos_id')
                            ->orderBy('created_at', 'desc')
                            ->take(5) // Ambil 5 terbaru untuk di dropdown
                            ->get();
                            
            $notifCount = $pengumumans->count();
        @endphp

        <div class="flex items-center gap-3">

            <div class="relative">
                <button id="notification-bell" type="button" onclick="toggleNotifications()"
                        class="relative p-2 text-gray-500 rounded-lg hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    @if($notifCount > 0)
                        <span class="absolute top-1 right-1 h-2.5 w-2.5 rounded-full border-2 border-white bg-red-500"></span>
                    @endif
                </button>

                <div id="notification-dropdown"
                     class="absolute right-0 top-full mt-2 hidden w-80 rounded-lg border border-gray-200 bg-white shadow-lg">
                    <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-4 py-3">
                        <h3 class="text-sm font-bold text-gray-700">Pengumuman</h3>
                        @if($notifCount > 0)
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-bold text-blue-700">{{ $notifCount }} Baru</span>
                        @endif
                    </div>
                    
                    <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                        @forelse($pengumumans as $notif)
                            <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-sm font-bold text-gray-800">{{ $notif->judul }}</p>
                                    <span class="text-[10px] font-semibold text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $notif->isi }}</p>
                                @if(is_null($notif->kos_id))
                                    <span class="inline-block mt-2 rounded bg-purple-100 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-purple-700">Global</span>
                                @else
                                    <span class="inline-block mt-2 rounded bg-blue-100 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-blue-700">Cabang</span>
                                @endif
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <p class="text-xs text-gray-500">Belum ada pengumuman baru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            <div class="relative">
                <button type="button" id="profile-btn" onclick="toggleProfilePopup()" class="-m-1.5 flex items-center p-1.5 cursor-pointer hover:bg-gray-50 rounded-lg transition-colors">
                    <span class="sr-only">Buka menu profil</span>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-3 text-sm font-bold leading-6 text-gray-800">{{ $namaLengkap }}</span>
                        <svg class="ml-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </span>
                </button>

                <div id="profile-popup" class="absolute right-0 top-full mt-2 hidden w-64 rounded-xl border border-gray-200 bg-white p-5 shadow-lg z-30">
                    <div class="flex flex-col items-center text-center border-b border-gray-100 pb-4 mb-4">
                        <h4 class="text-base font-bold text-gray-900">{{ $namaLengkap }}</h4>
                        <span class="mt-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">{{ $namaCabang }}</span>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Username</span>
                            <span class="font-bold text-gray-800">{{ $user->username }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">WhatsApp</span>
                            <span class="font-bold text-gray-800">{{ $user->no_wa ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</header>

</header>

<script>
    // Fungsi untuk memunculkan/menyembunyikan Notifikasi
    function toggleNotifications() {
        const notifDropdown = document.getElementById('notification-dropdown');
        const profilePopup = document.getElementById('profile-popup');
        
        // Tutup profil jika sedang terbuka
        if(profilePopup && !profilePopup.classList.contains('hidden')) {
            profilePopup.classList.add('hidden');
        }
        
        // Toggle notifikasi
        if(notifDropdown) {
            notifDropdown.classList.toggle('hidden');
        }
    }

    // Fungsi untuk memunculkan/menyembunyikan Profil
    function toggleProfilePopup() {
        const profilePopup = document.getElementById('profile-popup');
        const notifDropdown = document.getElementById('notification-dropdown');
        
        // Tutup notifikasi jika sedang terbuka
        if(notifDropdown && !notifDropdown.classList.contains('hidden')) {
            notifDropdown.classList.add('hidden');
        }
        
        // Toggle profil
        if(profilePopup) {
            profilePopup.classList.toggle('hidden');
        }
    }

    // Fitur UX tambahan: Tutup dropdown jika user mengklik area luar layar
    document.addEventListener('click', function(event) {
        const notifBtn = document.getElementById('notification-bell');
        const notifDropdown = document.getElementById('notification-dropdown');
        const profileBtn = document.getElementById('profile-btn');
        const profilePopup = document.getElementById('profile-popup');

        // Jika klik di luar notifikasi, tutup dropdownnya
        if (notifBtn && notifDropdown && !notifBtn.contains(event.target) && !notifDropdown.contains(event.target)) {
            notifDropdown.classList.add('hidden');
        }

        // Jika klik di luar profil, tutup popupnya
        if (profileBtn && profilePopup && !profileBtn.contains(event.target) && !profilePopup.contains(event.target)) {
            profilePopup.classList.add('hidden');
        }
    });
</script>
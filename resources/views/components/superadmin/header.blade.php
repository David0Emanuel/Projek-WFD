<!-- Header Super Admin -->
<header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    
    <!-- Tombol Hamburger Mobile -->
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden cursor-pointer hover:text-gray-900" onclick="toggleSidebar()">
        <span class="sr-only">Buka menu sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <!-- Separator vertikal mobile -->
    <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 items-center justify-between gap-x-4 self-stretch lg:gap-x-6">
        
        <!-- Judul Halaman Dinamis -->
        <h1 class="text-xl font-bold text-gray-800 tracking-tight">@yield('page-title')</h1>

        <!-- Area Kanan Header -->
        <div class="flex items-center gap-x-4 lg:gap-x-6 relative">
            
            <!-- Icon Notifikasi (Lonceng) -->
            <button type="button" id="notification-bell" onclick="toggleNotifications()" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 relative cursor-pointer">
                <span class="sr-only">Lihat notifikasi</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <!-- Indikator ada notifikasi baru -->
                <span class="absolute top-2.5 right-3 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            </button>

            <!-- Dropdown Notifikasi -->
            <div id="notification-dropdown" class="absolute right-10 top-14 z-50 mt-2 w-80 origin-top-right rounded-lg bg-white py-2 shadow-lg ring-1 ring-gray-900/5 hidden focus:outline-none">
                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-slate-50 rounded-t-lg">
                    <h3 class="text-sm font-bold text-gray-800">Notifikasi Masuk</h3>
                    <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">1 Baru</span>
                </div>
                <div class="max-h-60 overflow-y-auto">
                    <!-- Item Notifikasi -->
                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50">
                        <p class="text-sm font-semibold text-gray-900">Pengajuan Survey Baru</p>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">Budi Santoso telah mengajukan survey kamar di cabang PuluBoys Siwalankerto.</p>
                        <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-wide">10 Menit yang lalu</p>
                    </a>
                </div>
                <div class="px-4 py-2 border-t border-gray-100 text-center">
                    <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-800">Lihat Semua History &rarr;</a>
                </div>
            </div>

            <!-- Separator vertikal Desktop -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            <!-- Profile Menu Dropdown -->
            <div class="relative">
                <button type="button" id="profile-btn" onclick="toggleProfilePopup()" class="-m-1.5 flex items-center p-1.5 cursor-pointer hover:bg-gray-50 rounded-lg transition-colors">
                    <span class="sr-only">Buka menu profil</span>
                    <!-- Avatar Lingkaran -->
                    <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm border border-blue-700">
                        SA
                    </div>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-3 text-sm font-bold leading-6 text-gray-800">Super Admin</span>
                        <svg class="ml-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </span>
                </button>

                <!-- Popup Profil -->
                <div id="profile-popup" class="absolute right-0 top-14 z-50 mt-2 w-48 origin-top-right rounded-lg bg-white shadow-xl ring-1 ring-gray-900/5 hidden focus:outline-none">
                    <div class="px-4 py-3 border-b border-gray-100 bg-slate-50 rounded-t-lg">
                        <p class="text-sm font-bold text-gray-800">Administrator Utama</p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">superadmin@puluboys.com</p>
                    </div>
                    
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700">Pengaturan Akun</a>
                        <a href="#" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700">Log Aktivitas</a>
                    </div>
                    
                    <div class="border-t border-gray-100 py-1">
                        <!-- Tombol Logout yang mengarah ke AuthController -->
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
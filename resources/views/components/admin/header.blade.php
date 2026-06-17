<!-- Header -->
<header class="sticky top-0 z-20 bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3 lg:px-6">

        <!-- Kiri: hamburger + judul halaman -->
        <div class="flex items-center gap-3">
            <!-- Tombol hamburger (mobile) -->
            <button id="sidebar-toggle" type="button" onclick="toggleSidebar()"
                    class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <h2 class="text-lg font-bold text-gray-800 lg:text-xl">@yield('page-title', 'Main Dashboard')</h2>
        </div>

        <!-- Kanan: notifikasi + profil -->
        <div class="flex items-center gap-3">

            <!-- Tombol Notifikasi -->
            <div class="relative">
                <button id="notification-bell" type="button" onclick="toggleNotifications()"
                        class="relative p-2 text-gray-500 rounded-lg hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <!-- Badge notifikasi -->
                    <span class="absolute top-1 right-1 h-2.5 w-2.5 rounded-full bg-red-500"></span>
                </button>

                <!-- Dropdown Notifikasi -->
                <div id="notification-dropdown"
                     class="absolute right-0 top-full mt-2 hidden w-72 rounded-lg border border-gray-200 bg-white shadow-lg">
                    <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-4 py-3">
                        <h3 class="text-sm font-bold text-gray-700">Notifikasi</h3>
                        <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-600">2 Baru</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-4 py-3 hover:bg-gray-50">
                            <p class="text-sm font-medium text-gray-700">Tagihan Jatuh Tempo</p>
                            <p class="mt-1 text-xs text-gray-500">Tagihan bulan Juni akan jatuh tempo dalam 18 hari.</p>
                        </div>
                        <div class="px-4 py-3 hover:bg-gray-50">
                            <p class="text-sm font-medium text-gray-700">Pengumuman Baru</p>
                            <p class="mt-1 text-xs text-gray-500">Admin telah memposting pengumuman baru.</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 px-4 py-2 text-center">
                        <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-700">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            <!-- Profile Menu Dropdown -->
            <div class="relative">
                <button type="button" id="profile-btn" onclick="toggleProfilePopup()" class="-m-1.5 flex items-center p-1.5 cursor-pointer hover:bg-gray-50 rounded-lg transition-colors">
                    <span class="sr-only">Buka menu profil</span>
                    <div class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm border border-blue-700">
                        AD
                    </div>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-3 text-sm font-bold leading-6 text-gray-800">Admin</span>
                        <svg class="ml-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </span>
                </button>

                <!-- Popup Card Profile -->
                <div id="profile-popup" class="absolute right-0 top-full mt-2 hidden w-64 rounded-lg border border-gray-200 bg-white p-4 shadow-lg z-30">
                    <div class="flex flex-col items-center text-center border-b border-gray-100 pb-3 mb-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-500 text-base font-bold text-white mb-2">
                            NT
                        </div>
                        <h4 class="text-sm font-bold text-gray-800">Nama Tenant</h4>
                        <span class="text-xs text-gray-500">Kamar No. 12</span>
                    </div>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Username:</span>
                            <span class="font-medium text-gray-850">tenant12</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">WhatsApp:</span>
                            <span class="font-medium text-gray-850">081234567890</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

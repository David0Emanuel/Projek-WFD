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
                <button type="button" id="profile-btn" onclick="toggleProfilePopup()" class="flex items-center gap-2 text-left focus:outline-none cursor-pointer">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->nama ?? auth()->user()->username }}</p>
                        <p class="text-xs text-gray-500">Kamar {{ auth()->user()->kamar->nomor ?? 'Belum ada' }}</p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-500 text-sm font-bold text-white hover:bg-blue-600 transition-colors uppercase">
                        {{-- Mengambil 2 huruf pertama dari nama untuk ikon avatar --}}
                        {{ substr(auth()->user()->nama ?? auth()->user()->username, 0, 2) }}
                    </div>
                </button>

                <div id="profile-popup" class="absolute right-0 top-full mt-2 hidden w-64 rounded-lg border border-gray-200 bg-white p-4 shadow-lg z-30">
                    <div class="flex flex-col items-center text-center border-b border-gray-100 pb-3 mb-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-500 text-base font-bold text-white mb-2 uppercase">
                            {{ substr(auth()->user()->nama ?? auth()->user()->username, 0, 2) }}
                        </div>
                        <h4 class="text-sm font-bold text-gray-800">{{ auth()->user()->nama ?? auth()->user()->username }}</h4>
                        <span class="text-xs text-gray-500">Kamar {{ auth()->user()->kamar->nomor ?? '-' }}</span>
                    </div>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Username:</span>
                            <span class="font-medium text-gray-800">{{ auth()->user()->username }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">WhatsApp:</span>
                            <span class="font-medium text-gray-800">{{ auth()->user()->no_wa }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

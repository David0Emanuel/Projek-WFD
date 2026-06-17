<!-- Sidebar -->
<div id="tenant-sidebar" class="flex flex-col min-h-screen fixed inset-y-0 left-0 z-40 w-64 -translate-x-full bg-white border-r border-gray-200 shadow-sm transition-transform duration-300 lg:translate-x-0">

    <!-- Logo -->
    <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
        <h1 class="text-lg font-bold text-black     ">Kos<span class="text-blue-500">In</span>Aja</h1>
        <!-- Tombol tutup sidebar (mobile) -->
        <button type="button" onclick="toggleSidebar()" class="p-1 text-gray-400 hover:text-gray-600 lg:hidden">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex-1 px-3 py-4 space-y-1">
        <p class="px-3 mb-2 text-xs font-semibold uppercase text-gray-400">Menu</p>

        <a href="{{ route('tenant.dashboard') }}" id="nav-dashboard"
           class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
                  {{ request()->routeIs('tenant.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Main Dashboard
        </a>

        <a href="{{ route('tenant.invoice') }}" id="nav-invoice"
           class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
                  {{ request()->routeIs('tenant.invoice') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            Invoice & Tagihan
        </a>

        <a href="{{ route('tenant.maintenance') }}" id="nav-maintenance"
           class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
                  {{ request()->routeIs('tenant.maintenance') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1 5.1a2.121 2.121 0 11-3-3l5.1-5.1m0 0L15 9.59m-7.58 5.58L4.5 18m7.08-12.42l3.42 3.42m-3.42-3.42L15 4.5m-3.58 3.58l3.42 3.42" />
            </svg>
            Maintenance
        </a>
    </nav>

    <!-- Logout -->
    <div class="mt-auto border-t border-gray-200 p-3">
        <a href="#" id="nav-logout"
           class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-500 hover:bg-red-50 hover:text-red-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
            Logout
        </a>
    </div>
</div>

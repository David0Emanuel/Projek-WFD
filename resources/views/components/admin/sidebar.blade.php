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

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
                  {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            Main Dashboard
        </a>

        <a href="{{ route('admin.kamar') }}"
           class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
                  {{ request()->routeIs('admin.kamar') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            Kamar & Meteran
        </a>

        <a href="{{ route('admin.survey') }}"
           class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
                  {{ request()->routeIs('admin.survey') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            Survey
        </a>
        
        <a href="{{ route('admin.komplain') }}"
           class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
                  {{ request()->routeIs('admin.komplain') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }}">
            Komplain
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

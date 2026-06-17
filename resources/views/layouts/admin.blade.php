<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Cabang - KosInAja')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body class="bg-gray-50 font-sans text-gray-900">

    <div class="flex min-h-screen flex-col lg:flex-row">
        <!-- Sidebar Desktop -->
        <aside class="hidden w-64 flex-col border-r border-gray-200 bg-white lg:flex">
            <div class="flex h-16 items-center justify-center border-b border-gray-200">
                <a href="#" class="text-xl font-bold text-gray-900">Kos<span class="text-blue-600">In</span>Aja <span class="text-xs font-normal text-gray-500">Admin</span></a>
            </div>
            <nav class="flex flex-1 flex-col gap-2 overflow-y-auto p-4">
                <a href="{{ route('admin.dashboard') }}" class="rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }} px-4 py-3 text-sm font-bold transition-colors">Ringkasan Operasional</a>
                <a href="{{ route('admin.kamar') }}" class="rounded-lg {{ request()->routeIs('admin.kamar') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }} px-4 py-3 text-sm font-bold transition-colors">Kamar & Meteran</a>
                <a href="{{ route('admin.survey') }}" class="rounded-lg {{ request()->routeIs('admin.survey') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }} px-4 py-3 text-sm font-bold transition-colors">Survey & Check-In</a>
                <a href="{{ route('admin.komplain') }}" class="rounded-lg {{ request()->routeIs('admin.komplain') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }} px-4 py-3 text-sm font-bold transition-colors">Tiket Komplain</a>
            </nav>
            <div class="border-t border-gray-200 p-4">
                <form action="#" method="POST">
                    @csrf
                    <button type="submit" class="w-full rounded-lg bg-red-50 px-4 py-3 text-sm font-bold text-red-600 transition-colors hover:bg-red-100">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Header Mobile -->
        <header class="sticky top-0 z-50 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 lg:hidden">
            <a href="#" class="text-lg font-bold text-gray-900">Kos<span class="text-blue-600">In</span>Aja</a>
            <button id="mobile-btn" class="text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </header>

        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobile-menu" class="hidden flex-col border-b border-gray-200 bg-white p-4 shadow-sm lg:hidden">
            <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50">Ringkasan</a>
            <a href="{{ route('admin.kamar') }}" class="block rounded-lg px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50">Kamar & Meteran</a>
            <a href="{{ route('admin.survey') }}" class="block rounded-lg px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50">Survey & Check-In</a>
            <a href="{{ route('admin.komplain') }}" class="block rounded-lg px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50">Komplain</a>
            <hr class="my-2">
            <button class="w-full rounded-lg px-4 py-3 text-left text-sm font-bold text-red-600 hover:bg-red-50">Logout</button>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">@yield('page-title')</h1>
                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-bold text-gray-900">Admin Siwalankerto</p>
                        <p class="text-xs text-gray-500">Cabang PuluBoys</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">A</div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    <script>
        const btn = document.getElementById('mobile-btn');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
        });
    </script>
</body>
</html>
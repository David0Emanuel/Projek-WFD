<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KosInAja') - KosInAja</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex min-h-screen flex-col bg-gray-50 font-sans text-gray-900">
    <header class="sticky top-0 z-50 border-b border-gray-200 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900 transition-colors hover:text-blue-600">Kos<span class="text-blue-600">In</span>Aja</a>

            <button id="mobile-menu-btn" class="block text-gray-500 hover:text-gray-900 focus:outline-none lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <nav id="mobile-menu" class="hidden w-full flex-col gap-4 mt-4 lg:mt-0 lg:flex lg:w-auto lg:flex-row lg:items-center text-sm font-medium text-gray-600 transition-all duration-300">
                <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 transition-colors hover:bg-gray-100 hover:text-gray-900 lg:px-2 lg:py-1">Beranda</a>
                <a href="{{ route('visitor.branches') }}" class="block rounded-lg px-3 py-2 transition-colors hover:bg-gray-100 hover:text-gray-900 lg:px-2 lg:py-1">Daftar Cabang</a>
                <a href="{{ route('home') }}#cara-pesan" class="block rounded-lg px-3 py-2 transition-colors hover:bg-gray-100 hover:text-gray-900 lg:px-2 lg:py-1">Cara Pesan</a>
                
                @auth
                    @php $role = Auth::user()->role; @endphp
                    @if ($role === 'tenant')
                        <a href="{{ route('tenant.dashboard') }}" class="block rounded-lg px-3 py-2 font-bold text-blue-600 hover:text-blue-700 lg:px-2 lg:py-1">Dashboard</a>
                    @elseif ($role === 'visitor')
                        <a href="{{ route('visitor.profile') }}" class="block rounded-lg px-3 py-2 font-bold text-blue-600 hover:text-blue-700 lg:px-2 lg:py-1">Dashboard</a>
                    @elseif ($role === 'admin_cabang')
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 font-bold text-blue-600 hover:text-blue-700 lg:px-2 lg:py-1">Dashboard</a>
                    @elseif ($role === 'super_admin')
                        <a href="{{ route('superadmin.dashboard') }}" class="block rounded-lg px-3 py-2 font-bold text-blue-600 hover:text-blue-700 lg:px-2 lg:py-1">Dashboard</a>
                    @endif
                    
                    <form action="{{ route('logout') }}" method="POST" class="mt-2 block lg:mt-0 lg:inline lg:ml-2">
                        @csrf
                        <button type="submit" class="w-full lg:w-auto rounded-full bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-rose-600">Logout</button>
                    </form>
                @else
                    <div class="mt-2 flex flex-col gap-3 lg:mt-0 lg:flex-row lg:items-center lg:ml-2">
                        <a href="{{ route('login') }}" class="text-center rounded-full border border-gray-200 bg-white px-4 py-2 font-bold transition-all hover:border-gray-300 hover:bg-gray-50">Login</a>
                        <a href="{{ route('register') }}" class="text-center rounded-full bg-blue-600 px-4 py-2 font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">Daftar</a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl grow px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        @yield('content')
    </main>

    <footer class="mt-auto border-t border-gray-200 bg-white py-8">
        <div class="mx-auto max-w-7xl px-4 text-sm text-gray-500 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 text-center md:flex-row md:text-left">
                <span class="font-medium text-gray-600">KosInAja &copy; {{ date('Y') }}. Semua hak dilindungi.</span>
                <span class="text-gray-400">Pesan kamar lebih mudah dan cepat melalui website.</span>
            </div>
        </div>
    </footer>

    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
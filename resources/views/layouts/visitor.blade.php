<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KosInAja') - KosInAja</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="flex min-h-screen flex-col bg-gray-50 font-sans text-gray-900">
    <!-- Header Sticky -->
    <header class="sticky top-0 z-50 border-b border-gray-200 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900 transition-colors hover:text-blue-600">Kos<span class="text-blue-600">In</span>Aja</a>

            <nav class="flex flex-wrap items-center gap-2 text-sm font-medium text-gray-600 sm:gap-4">
                <a href="{{ route('home') }}" class="rounded-lg px-2 py-1 transition-colors hover:bg-gray-100 hover:text-gray-900">Beranda</a>
                <a href="{{ route('visitor.branches') }}" class="rounded-lg px-2 py-1 transition-colors hover:bg-gray-100 hover:text-gray-900">Daftar Cabang</a>
                <a href="{{ route('home') }}#cara-pesan" class="rounded-lg px-2 py-1 transition-colors hover:bg-gray-100 hover:text-gray-900">Cara Pesan</a>
                
                @auth
                    @php $role = Auth::user()->role; @endphp
                    @if ($role === 'tenant')
                        <a href="{{ route('tenant.dashboard') }}" class="rounded-lg px-2 py-1 font-bold text-blue-600 hover:text-blue-700">Dashboard</a>
                    @elseif ($role === 'visitor')
                        <a href="{{ route('visitor.profile') }}" class="rounded-lg px-2 py-1 font-bold text-blue-600 hover:text-blue-700">Dashboard</a>
                    @elseif ($role === 'admin_cabang')
                        <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-2 py-1 font-bold text-blue-600 hover:text-blue-700">Dashboard</a>
                    @elseif ($role === 'super_admin')
                        <a href="{{ route('superadmin.dashboard') }}" class="rounded-lg px-2 py-1 font-bold text-blue-600 hover:text-blue-700">Dashboard</a>
                    @endif
                    
                    <form action="{{ route('logout') }}" method="POST" class="ml-2 inline">
                        @csrf
                        <button type="submit" class="rounded-full bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-rose-600">Logout</button>
                    </form>
                @else
                    <div class="ml-2 flex items-center gap-2">
                        <a href="{{ route('login') }}" class="rounded-full border border-gray-200 bg-white px-4 py-2 font-bold transition-all hover:border-gray-300 hover:bg-gray-50">Login</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-blue-600 px-4 py-2 font-bold text-white transition-all hover:bg-blue-700 hover:shadow-md">Daftar</a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mx-auto w-full max-w-7xl grow px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-gray-200 bg-white py-8">
        <div class="mx-auto max-w-7xl px-4 text-sm text-gray-500 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 text-center md:flex-row md:text-left">
                <span class="font-medium text-gray-600">PuluBoys &copy; {{ date('Y') }}. Semua hak dilindungi.</span>
                <span class="text-gray-400">Pesan kamar lebih mudah dan cepat melalui website.</span>
            </div>
        </div>
    </footer>
</body>
</html>
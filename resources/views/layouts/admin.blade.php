<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - KosInAja</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <div class="flex min-h-screen">
        @include('components.admin.sidebar')

        <div id="sidebar-overlay"
             class="fixed inset-0 z-30 bg-black/40 hidden lg:hidden transition-opacity duration-300"
             onclick="toggleSidebar()">
        </div>

        <div class="flex flex-1 flex-col lg:ml-64">
            @include('components.admin.header')

            <main class="p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>
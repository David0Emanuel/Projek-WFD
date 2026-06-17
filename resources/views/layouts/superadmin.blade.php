<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin Dashboard') - KosInAja</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <div class="flex min-h-screen">
        <!-- Memanggil komponen Sidebar khusus Super Admin -->
        @include('components.superadmin.sidebar')

        <!-- Overlay ketika sidebar terbuka di mobile -->
        <div id="sidebar-overlay"
             class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"
             onclick="toggleSidebar()">
        </div>

        <div class="flex flex-1 flex-col lg:ml-64">
            <!-- Memanggil komponen Header khusus Super Admin -->
            @include('components.superadmin.header')

            <!-- Area Konten Utama -->
            <main class="p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Script Global untuk Layout Super Admin -->
    <script>
        // Toggle Sidebar (Pastikan ID di komponen sidebar nanti adalah 'superadmin-sidebar')
        function toggleSidebar() {
            const sidebar = document.getElementById('superadmin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar) sidebar.classList.toggle('-translate-x-full');
            if (overlay) overlay.classList.toggle('hidden');
        }

        // Toggle Notifications Dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notification-dropdown');
            if (dropdown) dropdown.classList.toggle('hidden');
            
            // Tutup profile popup jika terbuka
            const profile = document.getElementById('profile-popup');
            if (profile) profile.classList.add('hidden');
        }

        // Toggle Profile Popup Card
        function toggleProfilePopup() {
            const popup = document.getElementById('profile-popup');
            if (popup) popup.classList.toggle('hidden');
            
            // Tutup notification dropdown jika terbuka
            const notification = document.getElementById('notification-dropdown');
            if (notification) notification.classList.add('hidden');
        }

        // Tutup dropdown/popup ketika klik di luar elemen layar
        document.addEventListener('click', function(e) {
            // Logika tutup notifikasi
            const notifDropdown = document.getElementById('notification-dropdown');
            const notifBell = document.getElementById('notification-bell');
            if (notifDropdown && notifBell && !notifBell.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }

            // Logika tutup menu profil
            const profilePopup = document.getElementById('profile-popup');
            const profileBtn = document.getElementById('profile-btn');
            if (profilePopup && profileBtn && !profileBtn.contains(e.target) && !profilePopup.contains(e.target)) {
                profilePopup.classList.add('hidden');
            }
        });
    </script>

    <!-- Tempat untuk menyisipkan script khusus dari halaman (seperti script Modal Approval) -->
    @stack('scripts')
</body>
</html>
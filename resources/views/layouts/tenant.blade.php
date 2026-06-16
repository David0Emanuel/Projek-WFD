<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tenant Dashboard') - PuluBoys</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <div class="flex min-h-screen">
        @include('components.tenant.sidebar')

        <!-- Overlay ketika sidebar terbuka di mobile -->
        <div id="sidebar-overlay"
             class="fixed inset-0 z-30 bg-black/40 hidden lg:hidden"
             onclick="toggleSidebar()">
        </div>

        <div class="flex flex-1 flex-col lg:ml-64">
            @include('components.tenant.header')

            <main class="p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modal Pembayaran (UI Only) -->
    <div id="payment-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100 transform transition-all">
        <div class="flex items-center justify-between bg-slate-50 px-5 py-4 border-b border-gray-200">
            <span class="text-base font-bold text-gray-800">Metode Pembayaran</span>
            <button type="button" onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 font-medium text-2xl transition-colors duration-200 cursor-pointer focus:outline-none">
                &times;
            </button>
        </div>
        
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Total Tagihan</p>
                <div class="text-3xl font-extrabold text-blue-600" id="modal-amount">
                    Rp 99.999.999
                </div>
            </div>
            
            <hr class="border-gray-100 mb-5" />
            
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Pilih Metode Pembayaran</p>
            
            <div class="space-y-3">
                <button type="button" onclick="closePaymentModal()" class="w-full py-3 px-4 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600 transition-all duration-200 text-left flex justify-between items-center cursor-pointer">
                    <span>QRIS (Gopay, OVO, LinkAja)</span>
                    <span class="text-gray-400 text-xs">&rarr;</span>
                </button>
                
                <button type="button" onclick="closePaymentModal()" class="w-full py-3 px-4 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600 transition-all duration-200 text-left flex justify-between items-center cursor-pointer">
                    <span>Virtual Account (Transfer Bank)</span>
                    <span class="text-gray-400 text-xs">&rarr;</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Review Invoice (UI Only) -->
<div id="invoice-review-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden">
    <div class="w-full max-w-sm bg-white rounded-lg border-2 border-gray-300 shadow-xl overflow-hidden">
        <!-- Header Modal (Yellow) -->
        <div class="bg-amber-400 p-4 border-b-2 border-gray-300 relative">
            <button type="button" onclick="closeInvoiceReviewModal()" class="absolute top-2 right-3 text-gray-800 hover:text-black font-extrabold text-2xl cursor-pointer">
                &times;
            </button>
            <h3 class="text-base font-bold text-gray-800">Review Invoice</h3>
            
            <div class="flex justify-between items-end mt-3 text-xs text-gray-700 font-semibold">
                <div>
                    <p>Nomor Tagihan: <span id="review-inv-no">INV/2026/01</span></p>
                    <p class="mt-0.5">Tanggal Bayar: <span id="review-inv-date">18/01/2026</span></p>
                </div>
                <div class="text-right">
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 font-bold border border-green-300 rounded">LUNAS</span>
                </div>
            </div>
        </div>
        
        <!-- Isi Modal -->
        <div class="p-4">
            <!-- Box Foto Meteran -->
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-2.5 mb-3 text-center">
                <p class="text-xs font-bold text-gray-600 mb-1.5">Foto Meteran Listrik</p>
                <div class="w-full h-36 rounded border border-gray-300 overflow-hidden bg-gray-200 flex items-center justify-center">
                    <img src="{{ asset('meteran.png') }}" alt="Meteran Listrik" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Detail Angka Meteran -->
            <div class="grid grid-cols-3 gap-2 text-center text-xs mb-3">
                <div class="bg-gray-50 border border-gray-200 p-1.5 rounded-lg">
                    <p class="text-[9px] font-bold text-gray-400 uppercase">Meteran Awal</p>
                    <p class="font-bold text-gray-800" id="review-meter-start">1050 kWh</p>
                </div>
                <div class="bg-gray-50 border border-gray-200 p-1.5 rounded-lg">
                    <p class="text-[9px] font-bold text-gray-400 uppercase">Meteran Akhir</p>
                    <p class="font-bold text-gray-800" id="review-meter-end">1250 kWh</p>
                </div>
                <div class="bg-gray-50 border border-gray-200 p-1.5 rounded-lg">
                    <p class="text-[9px] font-bold text-gray-400 uppercase">Pemakaian</p>
                    <p class="font-bold text-gray-800" id="review-meter-usage">200 kWh</p>
                </div>
            </div>

            <!-- Box Total Rp (Yellow) -->
            <div class="bg-amber-100 border border-amber-300 rounded-lg py-3 text-center">
                <span class="text-lg font-black text-gray-800" id="review-inv-amount">Rp. 99.999.999</span>
            </div>
        </div>
    </div>
</div>

    <!-- Script untuk WFD Dashboard -->
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('tenant-sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Toggle Notifications Dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('hidden');
            // Tutup profile popup jika terbuka
            const profile = document.getElementById('profile-popup');
            if (profile) profile.classList.add('hidden');
        }

        // Toggle Profile Popup Card
        function toggleProfilePopup() {
            const popup = document.getElementById('profile-popup');
            popup.classList.toggle('hidden');
            // Tutup notification dropdown jika terbuka
            const notification = document.getElementById('notification-dropdown');
            if (notification) notification.classList.add('hidden');
        }

        // Open Payment Modal
        function openPaymentModal(amount) {
            const modal = document.getElementById('payment-modal');
            const amountEl = document.getElementById('modal-amount');
            if (amount) {
                amountEl.innerText = amount;
            }
            modal.classList.remove('hidden');
        }

        // Close Payment Modal
        function closePaymentModal() {
            const modal = document.getElementById('payment-modal');
            modal.classList.add('hidden');
        }

        // Open Invoice Review Modal
        function openInvoiceReviewModal(invNo, date, amount, startMeter, endMeter, usage) {
            document.getElementById('review-inv-no').innerText = invNo;
            document.getElementById('review-inv-date').innerText = date;
            document.getElementById('review-inv-amount').innerText = amount;
            document.getElementById('review-meter-start').innerText = startMeter;
            document.getElementById('review-meter-end').innerText = endMeter;
            document.getElementById('review-meter-usage').innerText = usage;
            document.getElementById('invoice-review-modal').classList.remove('hidden');
        }

        // Close Invoice Review Modal
        function closeInvoiceReviewModal() {
            document.getElementById('invoice-review-modal').classList.add('hidden');
        }

        // Tutup dropdown/popup ketika klik di luar elemen
        document.addEventListener('click', function(e) {
            // Tutup notifikasi
            const notifDropdown = document.getElementById('notification-dropdown');
            const notifBell = document.getElementById('notification-bell');
            if (notifDropdown && notifBell && !notifBell.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }

            // Tutup profile
            const profilePopup = document.getElementById('profile-popup');
            const profileBtn = document.getElementById('profile-btn');
            if (profilePopup && profileBtn && !profileBtn.contains(e.target) && !profilePopup.contains(e.target)) {
                profilePopup.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

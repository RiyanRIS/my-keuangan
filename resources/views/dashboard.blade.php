<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/cache-manager.js', 'resources/js/transaction-dom-cache.js', 'resources/js/transaction-config.js', 'resources/js/transaction-events.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            padding-bottom: 80px;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-wallet text-2xl text-blue-600"></i>
                    <h1 class="text-2xl font-bold text-gray-900">Keuangan</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-4 sm:px-6 lg:px-8">
            <!-- Trans Section -->
            @include('dashboard.trans')

            <!-- Stats Section -->
            @include('dashboard.stats')

            {{-- <section id="stats-section" class="hidden bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <i class="fas fa-chart-line text-green-600"></i>
                    <span>Statistik</span>
                </h2>
                <p class="text-gray-600">Grafik dan statistik keuangan Anda akan ditampilkan di sini.</p>
            </section> --}}

            <!-- Accounts Section -->
            <section id="accounts-section" class="hidden bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <i class="fas fa-piggy-bank text-purple-600"></i>
                    <span>Akun Saya</span>
                </h2>
                <p class="text-gray-600">Daftar wallet dan akun Anda akan ditampilkan di sini.</p>
            </section>

            <!-- More Section -->
            <section id="more-section" class="hidden">
                <!-- App Info Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow p-6 mb-4">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-wallet text-4xl"></i>
                        <div>
                            <h2 class="text-2xl font-bold">Keuangan App</h2>
                            <p class="text-blue-100">v1.0.0</p>
                        </div>
                    </div>
                </div>

                <!-- User Info Card -->
                <div class="bg-white rounded-lg shadow p-4 mb-4 border-l-4 border-blue-600">
                    <p class="text-sm text-gray-600 font-semibold mb-2">Pengguna Aktif</p>
                    <div id="userData" class="space-y-1">
                        <p class="text-gray-700 font-semibold">Loading...</p>
                    </div>
                </div>

                <!-- Settings Menu -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Kelola Data / Pengaturan Data -->
                    <a href="/settings-menu"
                        class="block px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-cogs text-xl text-indigo-600 group-hover:text-indigo-700"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Kelola Data</p>
                                <p class="text-xs text-gray-500">Kategori, dompet, tipe dompet</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <!-- Pengaturan -->
                    <a href="/settings"
                        class="block px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-sliders-h text-xl text-blue-600 group-hover:text-blue-700"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Pengaturan</p>
                                <p class="text-xs text-gray-500">Atur preferensi aplikasi</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <!-- Akun -->
                    <a href="/account"
                        class="block px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-user-circle text-xl text-green-600 group-hover:text-green-700"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Akun</p>
                                <p class="text-xs text-gray-500">Kelola profil dan data akun</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <!-- Keamanan -->
                    <a href="/security"
                        class="block px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-lock text-xl text-orange-600 group-hover:text-orange-700"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Keamanan</p>
                                <p class="text-xs text-gray-500">Ubah password dan keamanan</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <!-- Backup -->
                    <a href="/backup"
                        class="block px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-database text-xl text-purple-600 group-hover:text-purple-700"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Backup</p>
                                <p class="text-xs text-gray-500">Backup dan restore data</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <!-- Feedback -->
                    <a href="/feedback"
                        class="block px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-comments text-xl text-yellow-600 group-hover:text-yellow-700"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Feedback</p>
                                <p class="text-xs text-gray-500">Berikan masukan dan saran</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <!-- Bantuan -->
                    <a href="/help"
                        class="block px-6 py-4 hover:bg-gray-50 transition flex items-center justify-between group">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-question-circle text-xl text-red-600 group-hover:text-red-700"></i>
                            <div>
                                <p class="font-semibold text-gray-900">Bantuan</p>
                                <p class="text-xs text-gray-500">Pusat bantuan dan FAQ</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                </div>

                <!-- Footer Info -->
                <div class="mt-6 text-center text-gray-500 text-xs">
                    <p>© 2026 Keuangan App. All rights reserved.</p>
                    <p class="mt-1">Last Update: 2026-05-07</p>
                </div>
            </section>
        </main>
    </div>

    <!-- Floating Action Button (FAB) -->
    <button id="fabMain"
        class="fixed bottom-24 right-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center transition transform hover:scale-110 z-50 flex-col justify-center items-center">
        <i class="fas fa-plus text-3xl"></i>
    </button>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-30">
        <div class="max-w-6xl mx-auto px-4 flex justify-around">
            <!-- Trans -->
            <button class="nav-item active flex-1 py-4 text-center text-blue-600 hover:bg-blue-50 transition"
                data-section="trans">
                <i class="fas fa-exchange-alt text-xl mb-1"></i>
                <p class="text-xs font-semibold">Trans</p>
            </button>

            <!-- Stats -->
            <button class="nav-item flex-1 py-4 text-center text-gray-600 hover:bg-gray-50 transition"
                data-section="stats">
                <i class="fas fa-chart-line text-xl mb-1"></i>
                <p class="text-xs font-semibold">Stats</p>
            </button>

            <!-- Accounts -->
            <button class="nav-item flex-1 py-4 text-center text-gray-600 hover:bg-gray-50 transition"
                data-section="accounts">
                <i class="fas fa-piggy-bank text-xl mb-1"></i>
                <p class="text-xs font-semibold">Akun</p>
            </button>

            <!-- More -->
            <button class="nav-item flex-1 py-4 text-center text-gray-600 hover:bg-gray-50 transition"
                data-section="more">
                <i class="fas fa-ellipsis-h text-xl mb-1"></i>
                <p class="text-xs font-semibold">Lainya</p>
            </button>
        </div>
    </nav>

    <!-- Modal Container for Dynamic Content -->
    <div id="transactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end">
        <div id="modalContent" class="bg-white w-full shadow-lg h-full overflow-y-auto">
            <!-- Content loaded via AJAX -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const token = localStorage.getItem('api_token');
            const user = JSON.parse(localStorage.getItem('user'));

            if (!token || !user) {
                window.location.href = '/login';
                return;
            }

            // Initialize master data cache (wallets & categories)
            CacheManager.initMasterData();

            $('#modalContent').load('/transactions/create-modal');

            // Display user info
            $('#userData').html(`
                <p class="text-gray-700"><strong>${user.name}</strong></p>
                <p class="text-sm text-gray-600">${user.email}</p>
            `);

            // ========== Bottom Navigation ==========
            $('.nav-item').on('click', function() {
                activateTab($(this).data('section'));
            });

            // ========== Restore Active Tab from LocalStorage ==========
            const savedSection = localStorage.getItem('activeTab') || 'trans';
            const $navItem = $(`.nav-item[data-section="${savedSection}"]`);

            if ($navItem.length) {
                $navItem.trigger('click');
            }

            // ========== Open Transaction Modal with Lazy Loading ==========
            $('#fabMain').on('click', function() {
                openTransactionModal();
            });

            // ========== Handle Browser Back Button ==========
            window.addEventListener('hashchange', function () {
                if (window.location.hash !== '#transaction-create') {
                    closeTransactionModal();
                }
            });
        });

        function openTransactionModal() {
            window.location.hash = 'transaction-create';

            $('#transactionModal').removeClass('hidden');
        }

        function closeTransactionModal() {
            $('#transactionModal').addClass('hidden');

            $('#categoryDisplay').text("Pilih kategori...");
            $('#walletDisplay').text("Pilih dompet...");
            $('#fromWalletDisplay').text("Pilih dompet...");
            $('#toWalletDisplay').text("Pilih dompet...");
            $('#amountDisplay').val('');

            $('#amount').val('');
            $('#category_id').val('');
            $('#wallet_id').val('');
            $('#from_wallet_id').val('');
            $('#to_wallet_id').val('');
        }

        function activateTab(section) {
            // Remove active state from all
            $('.nav-item').removeClass('active text-blue-600').addClass('text-gray-600');
            $('main section').addClass('hidden');
            $('#fabMain').addClass('hidden');

            // Save active section to localStorage
            localStorage.setItem('activeTab', section);

            // Add active state to clicked
            const $navItem = $(`.nav-item[data-section="${section}"]`);

            $navItem
                .addClass('active text-blue-600')
                .removeClass('text-gray-600');

            // Show selected section
            if (section === 'trans') {
                $('#trans-section').removeClass('hidden');
                $('#fabMain').removeClass('hidden');
            } else if (section === 'stats') {
                $('#stats-section').removeClass('hidden');
                $('#fabMain').removeClass('hidden');
            } else if (section === 'accounts') {
                $('#accounts-section').removeClass('hidden');
            } else if (section === 'more') {
                $('#more-section').removeClass('hidden');
            }
        }
    </script>
</body>

</html>

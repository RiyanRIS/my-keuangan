<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <button id="logoutBtn" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg flex items-center space-x-1 text-sm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <!-- Trans Section -->
            <section id="trans-section" class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <i class="fas fa-exchange-alt text-blue-600"></i>
                    <span>Transaksi</span>
                </h2>
                <p class="text-gray-600">Daftar transaksi Anda akan ditampilkan di sini.</p>
            </section>

            <!-- Stats Section -->
            <section id="stats-section" class="hidden bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <i class="fas fa-chart-line text-green-600"></i>
                    <span>Statistik</span>
                </h2>
                <p class="text-gray-600">Grafik dan statistik keuangan Anda akan ditampilkan di sini.</p>
            </section>

            <!-- Accounts Section -->
            <section id="accounts-section" class="hidden bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <i class="fas fa-piggy-bank text-purple-600"></i>
                    <span>Akun Saya</span>
                </h2>
                <p class="text-gray-600">Daftar wallet dan akun Anda akan ditampilkan di sini.</p>
            </section>

            <!-- More Section -->
            <section id="more-section" class="hidden bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center space-x-2">
                    <i class="fas fa-ellipsis-h text-orange-600"></i>
                    <span>Lainnya</span>
                </h2>
                <div class="space-y-3">
                    <div id="userInfo" class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-gray-700"><strong>Informasi Pengguna:</strong></p>
                        <div id="userData" class="mt-2 text-gray-600">
                            <p>Loading...</p>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600">
                            <strong>Versi:</strong> 1.0.0<br>
                            <strong>Last Update:</strong> 2026-05-07
                        </p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Floating Action Button (FAB) -->
    <button id="fabMain" class="fixed bottom-24 right-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center transition transform hover:scale-110 z-40 flex-col justify-center items-center">
        <i class="fas fa-plus text-3xl"></i>
        <span class="text-xs mt-1 font-semibold">Transaksi</span>
    </button>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-30">
        <div class="max-w-6xl mx-auto px-4 flex justify-around">
            <!-- Trans -->
            <button class="nav-item active flex-1 py-4 text-center text-blue-600 hover:bg-blue-50 transition" data-section="trans">
                <i class="fas fa-exchange-alt text-xl mb-1"></i>
                <p class="text-xs font-semibold">Trans</p>
            </button>

            <!-- Stats -->
            <button class="nav-item flex-1 py-4 text-center text-gray-600 hover:bg-gray-50 transition" data-section="stats">
                <i class="fas fa-chart-line text-xl mb-1"></i>
                <p class="text-xs font-semibold">Stats</p>
            </button>

            <!-- Accounts -->
            <button class="nav-item flex-1 py-4 text-center text-gray-600 hover:bg-gray-50 transition" data-section="accounts">
                <i class="fas fa-piggy-bank text-xl mb-1"></i>
                <p class="text-xs font-semibold">Accounts</p>
            </button>

            <!-- More -->
            <button class="nav-item flex-1 py-4 text-center text-gray-600 hover:bg-gray-50 transition" data-section="more">
                <i class="fas fa-ellipsis-h text-xl mb-1"></i>
                <p class="text-xs font-semibold">More</p>
            </button>
        </div>
    </nav>

    <script>
        $(document).ready(function() {
            const token = localStorage.getItem('api_token');
            const user = JSON.parse(localStorage.getItem('user'));

            if (!token || !user) {
                window.location.href = '/login';
                return;
            }

            // Display user info
            $('#userData').html(`
                <p><strong>ID:</strong> ${user.id}</p>
                <p><strong>Nama:</strong> ${user.name}</p>
                <p><strong>Email:</strong> ${user.email}</p>
            `);

            // ========== Bottom Navigation ==========
            $('.nav-item').on('click', function() {
                const section = $(this).data('section');

                // Remove active state from all
                $('.nav-item').removeClass('active text-blue-600').addClass('text-gray-600');
                $('main section').addClass('hidden');

                // Add active state to clicked
                $(this).addClass('active text-blue-600').removeClass('text-gray-600');

                // Show selected section
                if (section === 'trans') {
                    $('#trans-section').removeClass('hidden');
                } else if (section === 'stats') {
                    $('#stats-section').removeClass('hidden');
                } else if (section === 'accounts') {
                    $('#accounts-section').removeClass('hidden');
                } else if (section === 'more') {
                    $('#more-section').removeClass('hidden');
                }
            });

            // ========== Floating Action Button ==========
            $('#fabMain').on('click', function() {
                // Navigate to transaction creation page
                window.location.href = '/transactions/create';
            });

            // ========== Logout Handler ==========
            $('#logoutBtn').on('click', function() {
                Swal.fire({
                    title: 'Logout?',
                    text: 'Apakah Anda yakin ingin logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/api/auth/logout',
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + token
                            },
                            success: function() {
                                localStorage.removeItem('api_token');
                                localStorage.removeItem('user');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Logout Berhasil',
                                    timer: 1000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '/login';
                                });
                            },
                            error: function() {
                                localStorage.removeItem('api_token');
                                localStorage.removeItem('user');
                                window.location.href = '/login';
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

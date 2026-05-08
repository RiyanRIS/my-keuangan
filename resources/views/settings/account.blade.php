<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            padding-bottom: 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex items-center space-x-4">
                <button id="backBtn" class="text-gray-600 hover:text-gray-900 text-xl">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-900">Akun Saya</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-2xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Profile Card -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-blue-600 text-2xl">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h2 id="userName" class="text-2xl font-bold">Loading...</h2>
                        <p id="userEmail" class="text-blue-100">Loading...</p>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        <span>Informasi Akun</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input
                            type="text"
                            id="fullName"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Nama Lengkap"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input
                            type="email"
                            id="email"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Email"
                        >
                    </div>
                </div>
            </div>

            <!-- Account Actions -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-cog text-green-600"></i>
                        <span>Tindakan Akun</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <button id="updateProfile" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-sync"></i>
                        <span>Perbarui Profil</span>
                    </button>
                    <button id="deleteAccount" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-trash"></i>
                        <span>Hapus Akun</span>
                    </button>
                    <button id="logoutBtn" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </div>
            </div>

            <!-- Account Stats -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-chart-pie text-purple-600"></i>
                        <span>Statistik Akun</span>
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600" id="totalTransactions">0</p>
                            <p class="text-xs text-gray-600">Total Transaksi</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600" id="totalWallets">0</p>
                            <p class="text-xs text-gray-600">Total Dompet</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            const token = localStorage.getItem('api_token');
            const user = JSON.parse(localStorage.getItem('user'));

            if (!token || !user) {
                window.location.href = '/login';
                return;
            }

            // Back button handler
            $('#backBtn').on('click', function() {
                history.replaceState(null, '', '/dashboard');
                window.location.href = '/dashboard';
            });

            // Load user info
            $('#userName').text(user.name);
            $('#userEmail').text(user.email);
            $('#fullName').val(user.name);
            $('#email').val(user.email);

            // Load account stats
            $.ajax({
                url: '/api/transactions',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    $('#totalTransactions').text(response.meta?.total || 0);
                }
            });

            $.ajax({
                url: '/api/wallets',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    $('#totalWallets').text(response.data?.length || 0);
                }
            });

            // Update profile
            $('#updateProfile').on('click', function() {
                const name = $('#fullName').val();
                const email = $('#email').val();

                if (!name || !email) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete',
                        text: 'Mohon isi semua field'
                    });
                    return;
                }

                $.ajax({
                    url: '/api/auth/user',
                    method: 'PATCH',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        name: name,
                        email: email
                    }),
                    success: function(response) {
                        const updatedUser = response.data;
                        localStorage.setItem('user', JSON.stringify(updatedUser));
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Profil Diperbarui',
                            text: 'Data akun Anda telah diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.responseJSON?.message || 'Gagal memperbarui profil'
                        });
                    }
                });
            });

            // Delete account
            $('#deleteAccount').on('click', function() {
                Swal.fire({
                    title: 'Hapus Akun?',
                    text: 'Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Implement account deletion API call if available
                        Swal.fire({
                            icon: 'info',
                            title: 'Coming Soon',
                            text: 'Fitur penghapusan akun akan segera tersedia'
                        });
                    }
                });
            });

            // Logout
            $('#logoutBtn').on('click', function() {
                Swal.fire({
                    title: 'Logout?',
                    text: 'Apakah Anda yakin ingin logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c',
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
                                localStorage.removeItem('activeTab');
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
                                localStorage.removeItem('activeTab');
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

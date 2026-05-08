<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keamanan - Keuangan App</title>
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
                <h1 class="text-2xl font-bold text-gray-900">Keamanan</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-2xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Security Status -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg shadow text-white p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="text-4xl">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Akun Aman</h2>
                        <p class="text-green-100">Status keamanan akun Anda baik</p>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-key text-red-600"></i>
                        <span>Ubah Password</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="oldPassword"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 pr-10"
                                placeholder="Masukkan password lama"
                            >
                            <button type="button" class="absolute right-3 top-2.5 text-gray-500 toggle-password" data-target="#oldPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-sm oldPassword-error hidden mt-1 block"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="newPassword"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 pr-10"
                                placeholder="Masukkan password baru (min 8 karakter)"
                            >
                            <button type="button" class="absolute right-3 top-2.5 text-gray-500 toggle-password" data-target="#newPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-sm newPassword-error hidden mt-1 block"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="confirmPassword"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 pr-10"
                                placeholder="Konfirmasi password baru"
                            >
                            <button type="button" class="absolute right-3 top-2.5 text-gray-500 toggle-password" data-target="#confirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-sm confirmPassword-error hidden mt-1 block"></span>
                    </div>
                    <button id="changePassword" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2 rounded-lg transition flex items-center justify-center space-x-2 mt-4">
                        <i class="fas fa-sync"></i>
                        <span>Ubah Password</span>
                    </button>
                </div>
            </div>

            <!-- Two Factor Authentication -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-lock text-purple-600"></i>
                        <span>Autentikasi Dua Faktor</span>
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <p class="text-gray-600 mb-4">Tambahkan lapisan keamanan ekstra untuk akun Anda</p>
                    <button id="enable2FA" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-shield-alt"></i>
                        <span>Aktifkan 2FA</span>
                    </button>
                </div>
            </div>

            <!-- Active Sessions -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-laptop text-orange-600"></i>
                        <span>Sesi Aktif</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900"><i class="fas fa-laptop mr-2 text-blue-600"></i>Perangkat Ini</p>
                            <p class="text-xs text-gray-600 mt-1">Browser: Chrome | IP: 192.168.1.1</p>
                        </div>
                        <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Login History -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-history text-green-600"></i>
                        <span>Riwayat Login Terakhir</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-2">
                    <div class="p-3 bg-gray-50 rounded border border-gray-200 text-sm">
                        <p class="font-semibold text-gray-700">Hari Ini, Pukul 14:30 WIB</p>
                        <p class="text-gray-600">Chrome | Windows</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded border border-gray-200 text-sm">
                        <p class="font-semibold text-gray-700">Kemarin, Pukul 09:15 WIB</p>
                        <p class="text-gray-600">Mobile Safari | iOS</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            const token = localStorage.getItem('api_token');

            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Back button handler
            $('#backBtn').on('click', function() {
                history.replaceState(null, '', '/dashboard');
                window.location.href = '/dashboard';
            });

            // Toggle password visibility
            $('.toggle-password').on('click', function() {
                const targetId = $(this).data('target');
                const input = $(targetId);
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Change password
            $('#changePassword').on('click', function() {
                const oldPassword = $('#oldPassword').val();
                const newPassword = $('#newPassword').val();
                const confirmPassword = $('#confirmPassword').val();

                // Clear errors
                $('.error').addClass('hidden');

                if (!oldPassword || !newPassword || !confirmPassword) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete',
                        text: 'Mohon isi semua field'
                    });
                    return;
                }

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Password baru dan konfirmasi tidak sesuai'
                    });
                    return;
                }

                if (newPassword.length < 8) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Terlalu Pendek',
                        text: 'Password minimal harus 8 karakter'
                    });
                    return;
                }

                $(this).prop('disabled', true).css('opacity', '0.5');

                $.ajax({
                    url: '/api/auth/user',
                    method: 'PATCH',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        old_password: oldPassword,
                        password: newPassword,
                        password_confirmation: confirmPassword
                    }),
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Diubah',
                            text: 'Password Anda berhasil diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            $('#oldPassword, #newPassword, #confirmPassword').val('');
                        });
                        $('#changePassword').prop('disabled', false).css('opacity', '1');
                    },
                    error: function(error) {
                        $('#changePassword').prop('disabled', false).css('opacity', '1');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.responseJSON?.message || 'Gagal mengubah password'
                        });
                    }
                });
            });

            // Enable 2FA
            $('#enable2FA').on('click', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Coming Soon',
                    text: 'Fitur autentikasi dua faktor akan segera tersedia'
                });
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-wallet text-2xl text-blue-600"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-white">Keuangan</h1>
                <p class="text-blue-100 text-sm mt-2">Kelola keuangan Anda dengan mudah</p>
            </div>

            <!-- Form -->
            <div class="p-6 sm:p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Masuk ke Akun Anda</h2>

                <form id="loginForm" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition duration-300"
                            placeholder="Masukkan email Anda"
                            required
                        >
                        <span class="text-red-500 text-sm email-error hidden mt-1 block"></span>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="Masukkan password Anda"
                                required
                            >
                            <button
                                type="button"
                                class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
                                onclick="togglePassword()"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-sm password-error hidden mt-1 block"></span>
                    </div>

                    <!-- Remember Me -->
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2"
                        id="submitBtn"
                    >
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </button>

                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="hidden text-center">
                        <div class="inline-block">
                            <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t-2 border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">atau</span>
                    </div>
                </div>

                <!-- Register Link -->
                <p class="text-center text-gray-600 mb-4">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:text-blue-700 transition">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-xs mt-6">
            &copy; 2026 Keuangan App. Semua hak dilindungi.
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = event.target;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                const email = $('#email').val();
                const password = $('#password').val();

                // Hide previous errors
                $('.email-error, .password-error').addClass('hidden');

                // Show loading
                $('#submitBtn').addClass('hidden');
                $('#loadingSpinner').removeClass('hidden');

                $.ajax({
                    url: '/api/auth/login',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        email: email,
                        password: password
                    }),
                    success: function(response) {
                        // Store token
                        localStorage.setItem('api_token', response.data.token);
                        localStorage.setItem('user', JSON.stringify(response.data.user));

                        Swal.fire({
                            icon: 'success',
                            title: 'Login Berhasil!',
                            text: 'Anda akan dialihkan ke dashboard',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/dashboard';
                        });
                    },
                    error: function(error) {
                        $('#submitBtn').removeClass('hidden');
                        $('#loadingSpinner').addClass('hidden');

                        if (error.status === 422) {
                            // Validation errors
                            const errors = error.responseJSON.meta || {};
                            if (errors.email) {
                                $('.email-error').text(errors.email[0]).removeClass('hidden');
                            }
                            if (errors.password) {
                                $('.password-error').text(errors.password[0]).removeClass('hidden');
                            }
                        } else if (error.status === 401) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Gagal',
                                text: error.responseJSON.message || 'Email atau password salah'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan. Silakan coba lagi.'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

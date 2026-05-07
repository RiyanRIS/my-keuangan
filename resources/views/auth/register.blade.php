<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Keuangan App</title>
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
                <h2 class="text-xl font-bold text-gray-800 mb-6">Buat Akun Baru</h2>

                <form id="registerForm" class="space-y-4">
                    @csrf

                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Nama Lengkap
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition duration-300"
                            placeholder="Masukkan nama Anda"
                            required
                        >
                        <span class="text-red-500 text-sm name-error hidden mt-1 block"></span>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition duration-300"
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
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="Minimal 8 karakter"
                                required
                            >
                            <button
                                type="button"
                                class="absolute right-3 top-2 text-gray-500 hover:text-gray-700"
                                onclick="togglePassword('password')"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-sm password-error hidden mt-1 block"></span>
                    </div>

                    <!-- Password Confirmation Input -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition duration-300"
                                placeholder="Ulangi password"
                                required
                            >
                            <button
                                type="button"
                                class="absolute right-3 top-2 text-gray-500 hover:text-gray-700"
                                onclick="togglePassword('password_confirmation')"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="text-red-500 text-sm password_confirmation-error hidden mt-1 block"></span>
                    </div>

                    <!-- Terms & Conditions -->
                    <label class="flex items-start space-x-2 cursor-pointer mt-3">
                        <input type="checkbox" id="terms" name="terms" class="w-4 h-4 text-blue-600 rounded mt-1" required>
                        <span class="text-sm text-gray-600">
                            Saya setuju dengan
                            <a href="#" class="text-blue-600 font-bold hover:text-blue-700">Syarat & Ketentuan</a>
                        </span>
                    </label>
                    <span class="text-red-500 text-sm terms-error hidden mt-1 block"></span>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2 rounded-lg transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2 mt-4"
                        id="submitBtn"
                    >
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar</span>
                    </button>

                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="hidden text-center">
                        <div class="inline-block">
                            <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t-2 border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">atau</span>
                    </div>
                </div>

                <!-- Login Link -->
                <p class="text-center text-gray-600 mb-4 text-sm">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:text-blue-700 transition">
                        Masuk di sini
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
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = event.target.closest('button').querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                const name = $('#name').val();
                const email = $('#email').val();
                const password = $('#password').val();
                const passwordConfirmation = $('#password_confirmation').val();
                const terms = $('#terms').is(':checked');

                // Hide previous errors
                $('.name-error, .email-error, .password-error, .password_confirmation-error, .terms-error').addClass('hidden');

                if (!terms) {
                    $('.terms-error').text('Anda harus menyetujui syarat & ketentuan').removeClass('hidden');
                    return;
                }

                // Show loading
                $('#submitBtn').addClass('hidden');
                $('#loadingSpinner').removeClass('hidden');

                $.ajax({
                    url: '/api/auth/register',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: passwordConfirmation
                    }),
                    success: function(response) {
                        // Store token
                        localStorage.setItem('api_token', response.data.token);
                        localStorage.setItem('user', JSON.stringify(response.data.user));

                        Swal.fire({
                            icon: 'success',
                            title: 'Registrasi Berhasil!',
                            text: 'Akun Anda telah dibuat. Anda akan dialihkan ke dashboard',
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
                            $.each(errors, function(field, messages) {
                                const errorClass = '.' + field + '-error';
                                $(errorClass).text(messages[0]).removeClass('hidden');
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

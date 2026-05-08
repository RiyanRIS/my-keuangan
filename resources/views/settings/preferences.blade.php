<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Keuangan App</title>
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
                <h1 class="text-2xl font-bold text-gray-900">Pengaturan</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-2xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Notifikasi -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-bell text-blue-600"></i>
                        <span>Notifikasi</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">Notifikasi Transaksi</p>
                            <p class="text-sm text-gray-600">Aktifkan notifikasi untuk setiap transaksi</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="notifTransaction" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">Notifikasi Pengingat</p>
                            <p class="text-sm text-gray-600">Pengingat budget bulanan</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="notifReminder" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Tampilan -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-palette text-purple-600"></i>
                        <span>Tampilan</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Tema</label>
                        <select id="themeSelect" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="light">Terang (Light)</option>
                            <option value="dark">Gelap (Dark)</option>
                            <option value="auto">Otomatis</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Mata Uang</label>
                        <select id="currencySelect" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="IDR">IDR - Rupiah Indonesia</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Keamanan Data -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-shield-alt text-green-600"></i>
                        <span>Keamanan Data</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">Simpan Data Offline</p>
                            <p class="text-sm text-gray-600">Cache data untuk akses offline</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="offlineData" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <button id="saveSettings" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 rounded-lg transition duration-300 mt-6 flex items-center justify-center space-x-2">
                <i class="fas fa-save"></i>
                <span>Simpan Pengaturan</span>
            </button>
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

            // Load saved settings
            const settings = JSON.parse(localStorage.getItem('app_settings') || '{}');
            $('#themeSelect').val(settings.theme || 'light');
            $('#currencySelect').val(settings.currency || 'IDR');
            $('#notifTransaction').prop('checked', settings.notifTransaction !== false);
            $('#notifReminder').prop('checked', settings.notifReminder !== false);
            $('#offlineData').prop('checked', settings.offlineData !== false);

            // Save settings
            $('#saveSettings').on('click', function() {
                const settings = {
                    theme: $('#themeSelect').val(),
                    currency: $('#currencySelect').val(),
                    notifTransaction: $('#notifTransaction').is(':checked'),
                    notifReminder: $('#notifReminder').is(':checked'),
                    offlineData: $('#offlineData').is(':checked')
                };

                localStorage.setItem('app_settings', JSON.stringify(settings));

                Swal.fire({
                    icon: 'success',
                    title: 'Pengaturan Tersimpan',
                    text: 'Pengaturan Anda telah diperbarui',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        });
    </script>
</body>
</html>

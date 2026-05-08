<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Data - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex items-center space-x-4">
                <button id="backBtn" class="text-gray-600 hover:text-gray-900 transition text-xl">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Data</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Kategori Pemasukan -->
                <a href="/settings/categories?type=income"
                    class="block px-6 py-4 border-b border-gray-100 hover:bg-blue-50 transition flex items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="fas fa-inbox text-xl text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Kategori Pemasukan</p>
                            <p class="text-xs text-gray-500">Kelola kategori pemasukan Anda</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                </a>

                <!-- Kategori Pengeluaran -->
                <a href="/settings/categories?type=expense"
                    class="block px-6 py-4 border-b border-gray-100 hover:bg-blue-50 transition flex items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-xl text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Kategori Pengeluaran</p>
                            <p class="text-xs text-gray-500">Kelola kategori pengeluaran Anda</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                </a>

                <!-- Tipe Dompet -->
                <a href="/settings/wallet-types"
                    class="block px-6 py-4 border-b border-gray-100 hover:bg-blue-50 transition flex items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-layer-group text-xl text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Tipe Dompet</p>
                            <p class="text-xs text-gray-500">Kelola tipe dompet Anda</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                </a>

                <!-- Dompet -->
                <a href="/settings/wallets"
                    class="block px-6 py-4 hover:bg-blue-50 transition flex items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-wallet text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Dompet</p>
                            <p class="text-xs text-gray-500">Kelola dompet dan saldo Anda</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                </a>
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

            $('#backBtn').on('click', function() {
                history.back();
            });
        });
    </script>
</body>
</html>

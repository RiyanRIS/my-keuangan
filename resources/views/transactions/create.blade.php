<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex items-center space-x-4">
                <a href="/dashboard" class="text-gray-600 hover:text-gray-900 text-xl">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Buat Transaksi</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-2xl w-full mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <form id="transactionForm" class="space-y-5">
                    @csrf

                    <!-- Transaction Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-list mr-2 text-blue-600"></i>Tipe Transaksi
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="income" class="hidden" required>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center hover:border-green-500 hover:bg-green-50 transition">
                                    <i class="fas fa-arrow-down text-green-600 text-2xl mb-2"></i>
                                    <p class="font-semibold text-gray-700">Pemasukan</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="expense" class="hidden" required>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center hover:border-red-500 hover:bg-red-50 transition">
                                    <i class="fas fa-arrow-up text-red-600 text-2xl mb-2"></i>
                                    <p class="font-semibold text-gray-700">Pengeluaran</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="transfer" class="hidden" required>
                                <div class="p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition">
                                    <i class="fas fa-exchange-alt text-blue-600 text-2xl mb-2"></i>
                                    <p class="font-semibold text-gray-700">Transfer</p>
                                </div>
                            </label>
                        </div>
                        <span class="text-red-500 text-sm type-error hidden mt-1 block"></span>
                    </div>

                    <!-- Wallet Selection (Income & Expense) -->
                    <div id="walletField" class="hidden">
                        <label for="wallet_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-wallet mr-2 text-blue-600"></i>Dompet
                        </label>
                        <select id="wallet_id" name="wallet_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Pilih dompet...</option>
                        </select>
                        <span class="text-red-500 text-sm wallet_id-error hidden mt-1 block"></span>
                    </div>

                    <!-- From Wallet (Transfer) -->
                    <div id="fromWalletField" class="hidden">
                        <label for="from_wallet_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-arrow-up text-blue-600"></i>Dari Dompet
                        </label>
                        <select id="from_wallet_id" name="from_wallet_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Pilih dompet asal...</option>
                        </select>
                        <span class="text-red-500 text-sm from_wallet_id-error hidden mt-1 block"></span>
                    </div>

                    <!-- To Wallet (Transfer) -->
                    <div id="toWalletField" class="hidden">
                        <label for="to_wallet_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-arrow-down text-green-600"></i>Ke Dompet
                        </label>
                        <select id="to_wallet_id" name="to_wallet_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Pilih dompet tujuan...</option>
                        </select>
                        <span class="text-red-500 text-sm to_wallet_id-error hidden mt-1 block"></span>
                    </div>

                    <!-- Category (Income & Expense) -->
                    <div id="categoryField" class="hidden">
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-600"></i>Kategori
                        </label>
                        <select id="category_id" name="category_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Pilih kategori...</option>
                        </select>
                        <span class="text-red-500 text-sm category_id-error hidden mt-1 block"></span>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill mr-2 text-green-600"></i>Jumlah
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                            <input
                                type="number"
                                id="amount"
                                name="amount"
                                class="w-full pl-12 pr-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                placeholder="0"
                                step="0.01"
                                min="0.01"
                                required
                            >
                        </div>
                        <span class="text-red-500 text-sm amount-error hidden mt-1 block"></span>
                    </div>

                    <!-- Note -->
                    <div>
                        <label for="note" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2 text-yellow-600"></i>Catatan
                        </label>
                        <textarea
                            id="note"
                            name="note"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 resize-none"
                            rows="3"
                            placeholder="Tambahkan catatan (opsional)"
                        ></textarea>
                        <span class="text-red-500 text-sm note-error hidden mt-1 block"></span>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="transaction_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-blue-600"></i>Tanggal
                        </label>
                        <input
                            type="date"
                            id="transaction_date"
                            name="transaction_date"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                        >
                        <span class="text-red-500 text-sm transaction_date-error hidden mt-1 block"></span>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2 mt-6"
                        id="submitBtn"
                    >
                        <i class="fas fa-save"></i>
                        <span>Simpan Transaksi</span>
                    </button>
                </form>
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

            // Load wallets and categories
            loadWallets();
            loadCategories();

            // Set today's date
            $('#transaction_date').val(new Date().toISOString().split('T')[0]);

            // Handle transaction type change
            $('input[name="type"]').on('change', function() {
                const type = $(this).val();

                // Hide all conditional fields
                $('#walletField, #categoryField, #fromWalletField, #toWalletField').addClass('hidden');

                if (type === 'income' || type === 'expense') {
                    $('#walletField, #categoryField').removeClass('hidden');
                    loadCategoriesByType(type);
                } else if (type === 'transfer') {
                    $('#fromWalletField, #toWalletField').removeClass('hidden');
                }
            });

            // Form submission
            $('#transactionForm').on('submit', function(e) {
                e.preventDefault();

                const type = $('input[name="type"]:checked').val();
                const amount = $('#amount').val();
                const note = $('#note').val();
                const transactionDate = $('#transaction_date').val();

                // Clear previous errors
                $('.error').addClass('hidden');

                let data = {
                    amount: parseFloat(amount),
                    note: note,
                    transaction_date: transactionDate
                };

                let endpoint = '';

                if (type === 'income') {
                    data.wallet_id = parseInt($('#wallet_id').val());
                    data.category_id = parseInt($('#category_id').val());
                    endpoint = '/api/transactions/income';
                } else if (type === 'expense') {
                    data.wallet_id = parseInt($('#wallet_id').val());
                    data.category_id = parseInt($('#category_id').val());
                    endpoint = '/api/transactions/expense';
                } else if (type === 'transfer') {
                    data.from_wallet_id = parseInt($('#from_wallet_id').val());
                    data.to_wallet_id = parseInt($('#to_wallet_id').val());
                    endpoint = '/api/transactions/transfer';
                }

                $('#submitBtn').prop('disabled', true).css('opacity', '0.5');

                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify(data),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Transaksi Berhasil',
                            text: 'Transaksi Anda telah disimpan',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/dashboard';
                        });
                    },
                    error: function(error) {
                        $('#submitBtn').prop('disabled', false).css('opacity', '1');

                        if (error.status === 422) {
                            const errors = error.responseJSON.meta || {};
                            $.each(errors, function(field, messages) {
                                const errorClass = '.' + field + '-error';
                                $(errorClass).text(messages[0]).removeClass('hidden');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.responseJSON?.message || 'Terjadi kesalahan'
                            });
                        }
                    }
                });
            });

            function loadWallets() {
                $.ajax({
                    url: '/api/wallets',
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(response) {
                        const wallets = response.data || [];
                        $('#wallet_id, #from_wallet_id, #to_wallet_id').each(function() {
                            const $select = $(this);
                            $select.find('option:not(:first)').remove();
                            wallets.forEach(wallet => {
                                $select.append(`<option value="${wallet.id}">${wallet.name} - Rp ${parseFloat(wallet.balance).toLocaleString('id-ID')}</option>`);
                            });
                        });
                    }
                });
            }

            function loadCategories() {
                $.ajax({
                    url: '/api/categories',
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(response) {
                        window.allCategories = response.data || [];
                    }
                });
            }

            function loadCategoriesByType(type) {
                const categories = window.allCategories.filter(cat => cat.type === type);
                $('#category_id').find('option:not(:first)').remove();
                categories.forEach(cat => {
                    $('#category_id').append(`<option value="${cat.id}">${cat.name}</option>`);
                });
            }
        });
    </script>
</body>
</html>

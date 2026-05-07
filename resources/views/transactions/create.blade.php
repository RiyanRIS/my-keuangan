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
    <style>
        .active {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .focus-border.focused {
            border-color: #3b82f6;
        }

        .key-btn {
            width: 100%;
            height: 100%;
            margin: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            background: #f9fafb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .key-btn:active {
            background: #e5e7eb;
        }

        .wallet-item,
        .category-item {
            width: 100%;
            height: 100%;
            margin: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            background: #f9fafb;
            text-align: center;
            white-space: normal;
            word-break: break-word;
            overflow-wrap: break-word;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            min-height: 40px;
        }

        .wallet-item:active,
        .category-item:active {
            background: #e5e7eb;
        }

        .wallet-item.active,
        .category-item.active {
            border-color: #3b82f6;
        }
    </style>
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
        <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-4 sm:px-6 lg:px-8">
            <form id="transactionForm" class="space-y-3">
                @csrf

                <!-- Transaction Type -->
                <div>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="income" class="hidden">
                            <div
                                class="p-3 border-2 border-gray-200 rounded-lg text-center hover:border-green-500 hover:bg-green-50 transition">
                                <p class="font-semibold text-gray-700">Pemasukan</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="expense" class="hidden" checked>
                            <div
                                class="p-3 border-2 border-gray-200 rounded-lg text-center hover:border-red-500 hover:bg-red-50 transition active">
                                <p class="font-semibold text-gray-700">Pengeluaran</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="transfer" class="hidden">
                            <div
                                class="p-3 border-2 border-gray-200 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition">
                                <p class="font-semibold text-gray-700">Transfer</p>
                            </div>
                        </label>
                    </div>
                    <span class="text-red-500 text-sm type-error hidden mt-1 block"></span>
                </div>

                <!-- Date and Time (1 baris) -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4">
                            <label for="transaction_date" class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                                Tanggal
                            </label>
                            <input type="date" id="transaction_date" name="transaction_date"
                                class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                        <span class="text-red-500 text-sm transaction_date-error hidden mt-1 block"></span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-4">
                            <input type="time" id="transaction_time" name="transaction_time"
                                class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                        <span class="text-red-500 text-sm transaction_time-error hidden mt-1 block"></span>
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <div class="flex items-center space-x-4">
                        <label for="amountDisplay" class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                            Jumlah
                        </label>
                        <input type="hidden" id="amount" name="amount">
                        <div class="flex-1 relative">
                            <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                            <input type="text" id="amountDisplay"
                                class="w-full pl-12 pr-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                placeholder="0" readonly required>
                        </div>
                    </div>
                    <span class="text-red-500 text-sm amount-error hidden mt-1 block"></span>
                </div>

                <!-- Category (Income & Expense) -->
                <div id="categoryField" class="hidden">
                    <div class="flex items-center space-x-4">
                        <label for="category_id" class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                            Kategori
                        </label>
                        <input type="hidden" id="category_id" name="category_id">
                        <div class="flex-1">
                            <div class="border-2 border-gray-200 rounded-lg p-3 bg-gray-50 cursor-pointer focus-border"
                                id="categoryDisplay">Pilih kategori...</div>
                        </div>
                    </div>
                    <span class="text-red-500 text-sm category_id-error hidden mt-1 block"></span>
                </div>

                <!-- Wallet Selection (Income & Expense) -->
                <div id="walletField" class="hidden">
                    <div class="flex items-center space-x-4">
                        <label for="wallet_id" class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                            Dompet
                        </label>
                        <input type="hidden" id="wallet_id" name="wallet_id">
                        <div class="flex-1">
                            <div class="border-2 border-gray-200 rounded-lg p-3 bg-gray-50 cursor-pointer focus-border"
                                id="walletDisplay">Pilih dompet...</div>
                        </div>
                    </div>
                    <span class="text-red-500 text-sm wallet_id-error hidden mt-1 block"></span>
                </div>

                <!-- From Wallet (Transfer) -->
                <div id="fromWalletField" class="hidden">
                    <div class="flex items-center space-x-4">
                        <label for="from_wallet_id" class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                            Dari
                        </label>
                        <input type="hidden" id="from_wallet_id" name="from_wallet_id">
                        <div class="flex-1">
                            <div class="border-2 border-gray-200 rounded-lg p-3 bg-gray-50 cursor-pointer focus-border"
                                id="fromWalletDisplay">Pilih dompet asal...</div>
                        </div>
                    </div>
                    <span class="text-red-500 text-sm from_wallet_id-error hidden mt-1 block"></span>
                </div>

                <!-- To Wallet (Transfer) -->
                <div id="toWalletField" class="hidden">
                    <div class="flex items-center space-x-4">
                        <label for="to_wallet_id" class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                            Ke
                        </label>
                        <input type="hidden" id="to_wallet_id" name="to_wallet_id">
                        <div class="flex-1">
                            <div class="border-2 border-gray-200 rounded-lg p-3 bg-gray-50 cursor-pointer focus-border"
                                id="toWalletDisplay">Pilih dompet tujuan...</div>
                        </div>
                    </div>
                    <span class="text-red-500 text-sm to_wallet_id-error hidden mt-1 block"></span>
                </div>

                <!-- Note -->
                <div>
                    <div class="flex items-center space-x-4">
                        <label for="note" class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                            Catatan
                        </label>
                        <textarea id="note" name="note"
                            class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 resize-none"
                            rows="3" placeholder="Tambahkan catatan (opsional)"></textarea>
                    </div>
                    <span class="text-red-500 text-sm note-error hidden mt-1 block"></span>
                </div>

                <!-- Submit & Lanjut Button dalam 1 baris -->
                <div class="flex space-x-4 mt-6">
                    <button type="submit"
                        class="w-3/4 flex bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2"
                        id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Simpan Transaksi</span>
                    </button>
                    <button type="button"
                        class="w-1/4 flex bg-gradient-to-r from-gray-600 to-gray-600 hover:from-gray-700 hover:to-gray-700 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105 flex items-center justify-center space-x-2"
                        id="continueBtn">
                        <i class="fas fa-arrow-right"></i>
                        <span>Lanjut</span>
                    </button>
                </div>
            </form>
        </main>
    </div>

    <!-- Floating Category Grid -->
    <div
        class="category-grid-floating hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 p-4 z-50 max-h-80 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Pilih Kategori</h3>
            <button class="text-gray-500 hover:text-gray-700" id="closeCategoryGrid">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="grid grid-cols-3 gap-1 justify-items-center category-grid mb-4" id="floatingCategoryGrid"></div>
    </div>

    <!-- Floating Wallet Grid -->
    <div
        class="wallet-grid-floating hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 p-4 z-50 max-h-80 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Pilih Dompet</h3>
            <button class="text-gray-500 hover:text-gray-700" id="closeWalletGrid">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="grid grid-cols-3 gap-1 justify-items-center wallet-grid" id="floatingWalletGrid"></div>
    </div>


    <!-- Custom Numeric Keyboard -->
    <div class="numeric-keyboard-floating hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 p-4 z-50 max-h-80 overflow-y-auto" id="numericKeyboard">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Jumlah</h3>
            <button class="text-gray-500 hover:text-gray-700" id="closeKeyboard">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="grid grid-cols-4 gap-1 justify-items-center">
            <button class="key-btn">1</button>
            <button class="key-btn">2</button>
            <button class="key-btn">3</button>
            <button class="key-btn" id="backspace"><i class="fas fa-backspace"></i></button>
            <button class="key-btn">4</button>
            <button class="key-btn">5</button>
            <button class="key-btn">6</button>
            <button class="key-btn">-</button>
            <button class="key-btn">7</button>
            <button class="key-btn">8</button>
            <button class="key-btn">9</button>
            <button class="key-btn">.</button>
            <button class="key-btn"></button>
            <button class="key-btn">0</button>
            <button class="key-btn"></button>
            <button class="key-btn" id="done">DONE</button>
        </div>
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

            // autofocus ke amount
            $('#amountDisplay').focus();
            $('.numeric-keyboard-floating').removeClass('hidden');
            
            // Set today's date and current time
            $('#transaction_date').val(new Date().toISOString().split('T')[0]);
            $('#transaction_time').val(new Date().toTimeString().split(' ')[0].substring(0, 5));

            // Default to expense
            $('input[name="type"][value="expense"]').prop('checked', true);
            $('#walletField, #categoryField').removeClass('hidden');

            // Handle transaction type change
            $('input[name="type"]').on('change', function() {
                const type = $(this).val();

                // Remove active class from all
                $('.grid div').removeClass('active');

                // Add active to selected
                $(this).parent().find('div').addClass('active');

                // Hide all conditional fields
                $('#walletField, #categoryField, #fromWalletField, #toWalletField').addClass('hidden');

                if (type === 'income' || type === 'expense') {
                    $('#walletField, #categoryField').removeClass('hidden');
                    loadCategoriesByType(type);
                } else if (type === 'transfer') {
                    $('#fromWalletField, #toWalletField').removeClass('hidden');
                }

                $('#categoryDisplay').text("Pilih kategori...");
                $('#walletDisplay').text("Pilih dompet...");
                $('#fromWalletDisplay').text("Pilih dompet...");
                $('#toWalletDisplay').text("Pilih dompet...");

                $('#amount').val('');
                $('#category_id').val('');
                $('#wallet_id').val('');
                $('#from_wallet_id').val('');
                $('#to_wallet_id').val('');

                setupWalletGrid('#walletDisplay', '#wallet_id');
                setupWalletGrid('#fromWalletDisplay', '#from_wallet_id');
                setupWalletGrid('#toWalletDisplay', '#to_wallet_id');

                $('.focus-border').removeClass('focused');
                $('#amountDisplay').focus();

                $('.numeric-keyboard-floating').removeClass('hidden');
                $('.wallet-grid-floating').addClass('hidden');
                $('.category-grid-floating').addClass('hidden');
            });

            // Custom Numeric Keyboard
            $('#amountDisplay').on('focus', function() {
                $('.focus-border').removeClass('focused');
                $('.numeric-keyboard-floating').removeClass('hidden');
                $('.wallet-grid-floating').addClass('hidden');
                $('.category-grid-floating').addClass('hidden');
            });

            $('#closeKeyboard').on('click', function() {
                $('.focus-border').removeClass('focused');
                $('.numeric-keyboard-floating').addClass('hidden');
                $('.wallet-grid-floating').addClass('hidden');
                $('.category-grid-floating').addClass('hidden');
            });

            $('.key-btn').not('#closeKeyboard, #backspace, #done').on('click', function() {
                const value = $(this).text();
                const current = $('#amount').val();
                const newValue = current + value;
                $('#amount').val(newValue);
                updateAmountDisplay(newValue);
            });

            $('#backspace').on('click', function() {
                const current = $('#amount').val();
                const newValue = current.slice(0, -1);
                $('#amount').val(newValue);
                updateAmountDisplay(newValue);
            });

            $('#done').on('click', function() {
                $('.focus-border').removeClass('focused');
                $('.numeric-keyboard-floating').addClass('hidden');
                $('.wallet-grid-floating').addClass('hidden');
                $('.category-grid-floating').addClass('hidden');
                $('#categoryDisplay').click();
            });

            function updateAmountDisplay(value) {
                if (value === '' || value === '0') {
                    $('#amountDisplay').val('0');
                } else {
                    const numValue = parseInt(value.replace(/\D/g, '')) || 0;
                    $('#amountDisplay').val(numValue.toLocaleString('id-ID'));
                }
            }

            // Wallet selection
            function setupWalletGrid(displayId, hiddenId) {
                $(displayId).on('click', function() {
                    $('#floatingWalletGrid').empty();
                    const wallets = window.allWallets || [];
                    wallets.forEach(wallet => {
                        $('#floatingWalletGrid').append(
                            `<div class="wallet-item" data-id="${wallet.id}" data-name="${wallet.name}" data-balance="${wallet.balance}">${wallet.name}</div>`
                        );
                    });
                    $('.focus-border').removeClass('focused');
                    $(this).addClass('focused');
                    $('.wallet-grid-floating').removeClass('hidden');
                    $('.numeric-keyboard-floating').addClass('hidden');
                    $('.category-grid-floating').addClass('hidden');
                });
            }

            $('#floatingWalletGrid').on('click', '.wallet-item', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const balance = $(this).data('balance');
                // Determine which field to update based on context
                if ($('#walletField').is(':visible')) {
                    $('#wallet_id').val(id);
                    $('#walletDisplay').text(`${name} - Rp ${parseFloat(balance).toLocaleString('id-ID')}`);
                } else if ($('#fromWalletField').is(':visible')) {
                    $('#from_wallet_id').val(id);
                    $('#fromWalletDisplay').text(
                        `${name} - Rp ${parseFloat(balance).toLocaleString('id-ID')}`);
                } else if ($('#toWalletField').is(':visible')) {
                    $('#to_wallet_id').val(id);
                    $('#toWalletDisplay').text(
                        `${name} - Rp ${parseFloat(balance).toLocaleString('id-ID')}`);
                }
                $('#floatingWalletGrid .wallet-item').removeClass('active');
                $(this).addClass('active');
                $('.focus-border').removeClass('focused');
                $('.wallet-grid-floating').addClass('hidden');
                $('.numeric-keyboard-floating').addClass('hidden');
                $('.category-grid-floating').addClass('hidden');
            });

            $('#closeWalletGrid').on('click', function() {
                $('.focus-border').removeClass('focused');
                $('.numeric-keyboard-floating').addClass('hidden');
                $('.wallet-grid-floating').addClass('hidden');
                $('.category-grid-floating').addClass('hidden');
            });

            setupWalletGrid('#walletDisplay', '#wallet_id');
            setupWalletGrid('#fromWalletDisplay', '#from_wallet_id');
            setupWalletGrid('#toWalletDisplay', '#to_wallet_id');

            // Category selection
            $('#categoryDisplay').on('click', function() {
                $('#floatingCategoryGrid').empty();
                const categories = window.allCategories.filter(cat => cat.type === $(
                    'input[name="type"]:checked').val());
                categories.forEach(cat => {
                    $('#floatingCategoryGrid').append(
                        `<div class="category-item" data-id="${cat.id}" data-name="${cat.name}">${cat.name}</div>`
                    );
                });
                $('.focus-border').removeClass('focused');
                $(this).addClass('focused');
                $('.category-grid-floating').removeClass('hidden');
                $('.numeric-keyboard-floating').addClass('hidden');
                $('.wallet-grid-floating').addClass('hidden');
            });

            $('#floatingCategoryGrid').on('click', '.category-item', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('#category_id').val(id);
                $('#categoryDisplay').text(name);
                $('#floatingCategoryGrid .category-item').removeClass('active');
                $(this).addClass('active');
                $('.focus-border').removeClass('focused');
                $('.category-grid-floating').addClass('hidden');
                $('.numeric-keyboard-floating').addClass('hidden');
                $('#walletDisplay').click();

            });

            $('#closeCategoryGrid').on('click', function() {
                $('.focus-border').removeClass('focused');
                $('.numeric-keyboard-floating').addClass('hidden');
                $('.wallet-grid-floating').addClass('hidden');
                $('.category-grid-floating').addClass('hidden');
            });

            $('#note').on('focus', function() {
                $('.focus-border').removeClass('focused');
                $(this).addClass('focused');
            });

            // Continue button - cycle through focus
            $('#continueBtn').on('click', function() {
                const type = $('input[name="type"]:checked').val();
                const currentAmount = $('#amount').val();
                const currentCategory = $('#category_id').val();
                const currentWallet = $('#wallet_id').val();
                const currentFromWallet = $('#from_wallet_id').val();
                const currentToWallet = $('#to_wallet_id').val();

                if (type === 'income' || type === 'expense') {
                    // Order: Amount → Category → Wallet → Submit
                    if (!currentAmount) {
                        $('#amountDisplay').focus();
                    } else if (!currentCategory) {
                        $('#categoryDisplay').click();
                    } else if (!currentWallet) {
                        $('#walletDisplay').click();
                    } else {
                        $('#submitBtn').click();
                    }
                } else if (type === 'transfer') {
                    // Order: Amount → From Wallet → To Wallet → Submit
                    if (!currentAmount) {
                        $('#amountDisplay').focus();
                    } else if (!currentFromWallet) {
                        $('#fromWalletDisplay').click();
                    } else if (!currentToWallet) {
                        $('#toWalletDisplay').click();
                    } else {
                        $('#submitBtn').click();
                    }
                }
            });

            // Form submission
            $('#transactionForm').on('submit', function(e) {
                e.preventDefault();

                const type = $('input[name="type"]:checked').val();
                const amount = $('#amount').val();
                const note = $('#note').val();
                const transactionDate = $('#transaction_date').val();
                const transactionTime = $('#transaction_time').val();

                // Clear previous errors
                $('.error').addClass('hidden');

                let data = {
                    amount: parseFloat(amount),
                    note: note,
                    transaction_date: transactionDate,
                    transaction_time: transactionTime
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
                        window.allWallets = response.data || [];
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
                $('#categoryGrid').empty();
                categories.forEach(cat => {
                    $('#categoryGrid').append(
                        `<div class="category-item" data-id="${cat.id}" data-name="${cat.name}">${cat.name}</div>`
                    );
                });
            }
        });
    </script>
</body>

</html>

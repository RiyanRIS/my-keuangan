<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dompet - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/cache-manager.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button id="backBtn" class="text-gray-600 hover:text-gray-900 transition">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </button>
                    <h1 class="text-2xl font-bold text-gray-900">Dompet</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Loading State -->
            <div id="loadingState" class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
                <p class="text-gray-600">Memuat dompet...</p>
            </div>

            <!-- List Container -->
            <div id="walletsList" class="bg-white rounded-lg shadow overflow-hidden hidden">
                <!-- Items will be rendered here -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-12">
                <i class="fas fa-wallet text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">Tidak ada dompet</p>
                <p class="text-gray-500 text-sm mt-2">Tambahkan dompet pertama Anda</p>
            </div>
        </main>
    </div>

    <!-- Floating Action Button (FAB) - Add -->
    <button id="fabAdd"
        class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center transition transform hover:scale-110 z-50">
        <i class="fas fa-plus text-3xl"></i>
    </button>

    <!-- Modal for Add/Edit Form -->
    <div id="formModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end">
        <div class="bg-white w-full shadow-lg max-h-[90vh] overflow-y-auto">
            <div id="formContent">
                <!-- Form will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // ============= CONFIG =============
        const token = localStorage.getItem('api_token');

        // ============= READY =============
        $(document).ready(function() {
            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Cache callbacks
            window.onMasterDataReady = () => renderWalletsFromCache();
            window.onWalletsUpdated = () => renderWalletsFromCache();

            // Initialize
            CacheManager.initMasterData();

            // Events
            $('#backBtn').on('click', () => history.back());
            $('#fabAdd').on('click', () => openWalletForm(null));
            $(document).on('click', '.btn-delete', function() {
                deleteWalletConfirm($(this).data('id'));
            });
            $(document).on('click', '.btn-edit', function() {
                openWalletForm($(this).data('id'));
            });
            $('#formModal').on('click', (e) => {
                if (e.target.id === 'formModal') closeFormWalletModal();
            });
        });

        // ============= RENDER =============
        function renderWalletsFromCache() {
            const wallets = CacheManager.getAllWallets();
            $('#loadingState').addClass('hidden');

            if (wallets?.length > 0) {
                renderWallets(wallets);
                $('#walletsList').removeClass('hidden');
                $('#emptyState').addClass('hidden');
            } else {
                $('#walletsList').addClass('hidden');
                $('#emptyState').removeClass('hidden');
            }
        }

        function renderWallets(wallets) {
            let html = '';
            wallets.forEach((wallet) => {
                const balance = parseFloat(wallet.balance).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                const icon = wallet.icon || 'fa-wallet';
                const color = wallet.color || '#3B82F6';
                const bgColor = color + '20';

                html += `
                    <div class="wallet-item px-4 py-3 border-b border-gray-100 last:border-b-0 flex items-center space-x-4 hover:bg-gray-50 transition" data-id="${wallet.id}">
                        <div class="w-10 h-10 rounded flex items-center justify-center flex-shrink-0" style="background-color: ${bgColor};">
                            <i class="fas ${icon}" style="color: ${color};"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">${wallet.name}</p>
                            <p class="text-xs text-gray-600">${wallet.wallet_type?.name || 'Unknown'}</p>
                        </div>
                        <div class="text-right flex-shrink-0 mr-3">
                            <p class="font-semibold text-gray-900">Rp ${balance}</p>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <button class="btn-edit px-3 py-2 text-blue-600 hover:bg-blue-50 rounded transition" data-id="${wallet.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete px-3 py-2 text-red-600 hover:bg-red-50 rounded transition" data-id="${wallet.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            $('#walletsList').html(html);
        }

        // ============= MODAL =============
        function openWalletForm(walletId) {
            const url = walletId ? `/settings/wallet/form/${walletId}` : '/settings/wallet/form';
            $('#formContent').load(url, () => $('#formModal').removeClass('hidden'));
        }

        function closeFormWalletModal() {
            $('#formModal').addClass('hidden');
            $('#formContent').empty();
        }

        // ============= DELETE =============
        function deleteWalletConfirm(walletId) {
            Swal.fire({
                title: 'Hapus Dompet?',
                text: 'Data ini tidak bisa dipulihkan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) deleteWallet(walletId);
            });
        }

        function deleteWallet(walletId) {
            $.ajax({
                url: `/api/wallets/${walletId}`,
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire('Terhapus!', 'Dompet berhasil dihapus', 'success');
                    CacheManager.removeWalletFromCache(walletId);
                },
                error: function(error) {
                    Swal.fire('Error!', error.responseJSON?.message || 'Gagal menghapus', 'error');
                }
            });
        }

        // ============= SAVE =============
        window.saveWalletForm = function(formData) {
            const isEdit = formData.id ? true : false;
            const walletId = formData.id;
            const url = isEdit ? `/api/wallets/${walletId}` : '/api/wallets';
            const method = isEdit ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify(formData),
                success: function(response) {
                    Swal.fire('Berhasil!', response.message, 'success');
                    if (isEdit) {
                        CacheManager.updateWalletInCache(walletId, response.data);
                    } else {
                        CacheManager.addWalletToCache(response.data);
                    }
                    closeFormWalletModal();
                },
                error: function(error) {
                    if (error.status === 422) {
                        let msg = '';
                        $.each(error.responseJSON.errors, (field, messages) => {
                            msg += messages[0] + '\\n';
                        });
                        Swal.fire('Validasi Error!', msg, 'error');
                    } else {
                        Swal.fire('Error!', error.responseJSON?.message || 'Gagal menyimpan', 'error');
                    }
                }
            });
        };
    </script>
</body>

</html>

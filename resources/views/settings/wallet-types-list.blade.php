<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tipe Dompet - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <h1 class="text-2xl font-bold text-gray-900">Tipe Dompet</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Loading State -->
            <div id="loadingState" class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
                <p class="text-gray-600">Memuat tipe dompet...</p>
            </div>

            <!-- List Container -->
            <div id="walletTypesList" class="bg-white rounded-lg shadow overflow-hidden hidden">
                <!-- Items will be rendered here -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-12">
                <i class="fas fa-layer-group text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">Tidak ada tipe dompet</p>
                <p class="text-gray-500 text-sm mt-2">Tambahkan tipe dompet pertama Anda</p>
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
        $(document).ready(function() {
            const token = localStorage.getItem('api_token');

            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Load wallet types
            loadWalletTypes();

            // Back button
            $('#backBtn').on('click', function() {
                history.back();
            });

            // FAB - Add
            $('#fabAdd').on('click', function() {
                openWalletTypeForm(null);
            });

            // Delete button
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const walletTypeId = $(this).data('id');
                deleteWalletTypeConfirm(walletTypeId);
            });

            // Edit button
            $(document).on('click', '.btn-edit', function(e) {
                e.preventDefault();
                const walletTypeId = $(this).data('id');
                openWalletTypeForm(walletTypeId);
            });

            // Close form modal
            $('#formModal').on('click', function(e) {
                if (e.target.id === 'formModal') {
                    closeFormModal();
                }
            });
        });

        function loadWalletTypes() {
            $.ajax({
                url: '/api/wallet-types',
                method: 'GET',
                headers: { 'Authorization': `Bearer ${localStorage.getItem('api_token')}` },
                success: function(response) {
                    $('#loadingState').addClass('hidden');

                    if (response.data && response.data.length > 0) {
                        renderWalletTypes(response.data);
                        $('#walletTypesList').removeClass('hidden');
                    } else {
                        $('#emptyState').removeClass('hidden');
                    }
                },
                error: function() {
                    $('#loadingState').addClass('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat tipe dompet'
                    });
                }
            });
        }

        function renderWalletTypes(walletTypes) {
            let html = '';
            walletTypes.forEach((walletType) => {
                html += `
                    <div class="wallet-type-item px-4 py-3 border-b border-gray-100 last:border-b-0 flex items-center space-x-4 hover:bg-gray-50 transition group cursor-move"
                         data-id="${walletType.id}" data-sort="${walletType.sort_order}">
                        <div class="text-gray-400 group-hover:text-gray-600 transition">
                            <i class="fas fa-grip-vertical text-lg"></i>
                        </div>
                        <div class="w-10 h-10 rounded flex items-center justify-center flex-shrink-0 bg-purple-100">
                            <i class="fas fa-layer-group text-purple-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">${walletType.name}</p>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <button class="btn-edit px-3 py-2 text-blue-600 hover:bg-blue-50 rounded transition" data-id="${walletType.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete px-3 py-2 text-red-600 hover:bg-red-50 rounded transition" data-id="${walletType.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            $('#walletTypesList').html(html);
        }

        function openWalletTypeForm(walletTypeId) {
            const url = walletTypeId ? `/settings/wallet-type/form/${walletTypeId}` : '/settings/wallet-type/form';

            $('#formContent').load(url, function() {
                $('#formModal').removeClass('hidden');
            });
        }

        function closeFormModal() {
            $('#formModal').addClass('hidden');
            $('#formContent').empty();
        }

        function deleteWalletTypeConfirm(walletTypeId) {
            Swal.fire({
                title: 'Hapus Tipe Dompet?',
                text: 'Data ini tidak bisa dipulihkan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteWalletType(walletTypeId);
                }
            });
        }

        function deleteWalletType(walletTypeId) {
            $.ajax({
                url: `/api/wallet-types/${walletTypeId}`,
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Tipe dompet berhasil dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        loadWalletTypes();
                    });
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.responseJSON?.message || 'Gagal menghapus tipe dompet'
                    });
                }
            });
        }

        function initializeSortable() {
            const sortableEl = document.getElementById('walletTypesList');
            if (!sortableEl) return;

            Sortable.create(sortableEl, {
                handle: '.fa-grip-vertical',
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    saveOrder('wallet_types');
                }
            });
        }

        function saveOrder(type) {
            const items = [];
            $(`.${type === 'categories' ? 'category' : (type === 'wallet_types' ? 'wallet-type' : 'wallet')}-item`).each(function(index) {
                items.push({
                    id: $(this).data('id'),
                    sort_order: index
                });
            });

            $.ajax({
                url: '/api/settings/reorder',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    type: type,
                    items: items
                }),
                success: function() {
                    console.log('Order saved');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menyimpan urutan'
                    });
                }
            });
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kategori - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/cache-manager.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col pb-20">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button id="backBtn" class="text-gray-600 hover:text-gray-900 transition text-xl">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h1 class="text-2xl font-bold text-gray-900" id="pageTitle">Kategori</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Loading State -->
            <div id="loadingState" class="text-center py-12">
                <div class="inline-block">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-gray-200 animate-pulse mx-auto mb-4"></div>
                    <p class="text-gray-600">Memuat kategori...</p>
                </div>
            </div>

            <!-- List Container -->
            <div id="listContainer" class="bg-white rounded-lg shadow overflow-hidden hidden">
                <!-- Items rendered here -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-12">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">Tidak ada kategori</p>
                <p class="text-gray-500 text-sm mt-2">Mulai dengan menambahkan kategori baru</p>
            </div>
        </main>

        <!-- Floating Add Button -->
        <button id="fabAdd" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center transition transform hover:scale-110 z-40">
            <i class="fas fa-plus text-2xl"></i>
        </button>
    </div>

    <!-- Modal for Form -->
    <div id="formModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen">
            <div id="formContainer" class="bg-white w-full max-w-2xl rounded-t-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                <!-- Form loaded here -->
            </div>
        </div>
    </div>

    <script>
        const categoryType = new URLSearchParams(window.location.search).get('type') || 'income';
        const typeLabel = categoryType === 'income' ? 'Pemasukan' : 'Pengeluaran';
        const token = localStorage.getItem('api_token');

        $(document).ready(function() {
            if (!token) {
                window.location.href = '/login';
                return;
            }

            $('#pageTitle').text(`Kategori ${typeLabel}`);

            // Callback when master data is ready
            window.onMasterDataReady = function() {
                renderCategoriesFromCache();
            };

            // Callback when categories updated (from background sync or manual update)
            window.onCategoriesUpdated = function() {
                renderCategoriesFromCache();
            };

            // Initialize master data from cache or fetch
            CacheManager.initMasterData();

            $('#backBtn').on('click', function() {
                history.back();
            });

            $('#fabAdd').on('click', function() {
                openCategoryForm(null);
            });

            $('#formModal').on('click', function(e) {
                if (e.target === this) {
                    closeFormCategoryModal();
                }
            });

            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                openCategoryForm(id);
            });

            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                confirmDelete(id);
            });
        });

        /**
         * Render categories from cache (instantly)
         * No API call needed - data already in memory
         */
        function renderCategoriesFromCache() {
            const allCategories = CacheManager.getAllCategories();
            const categories = allCategories.filter(c => c.type === categoryType) || [];
            
            $('#loadingState').addClass('hidden');

            if (categories && categories.length > 0) {
                renderCategories(categories);
                $('#listContainer').removeClass('hidden');
                $('#emptyState').addClass('hidden');
            } else {
                $('#listContainer').addClass('hidden');
                $('#emptyState').removeClass('hidden');
            }
        }

        function renderCategories(categories) {
            let html = '';
            categories.forEach(cat => {
                const bgColor = `${cat.color}20`;
                html += `
                    <div class="px-4 py-3 border-b border-gray-100 last:border-0 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-4 flex-1">
                            <div style="background-color: ${bgColor}; color: ${cat.color};" class="w-10 h-10 rounded flex items-center justify-center flex-shrink-0">
                                <i class="fas ${cat.icon}"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">${cat.name}</p>
                                ${cat.description ? `<p class="text-xs text-gray-600">${cat.description}</p>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <button class="btn-edit px-3 py-2 text-blue-600 hover:bg-blue-50 rounded transition" data-id="${cat.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete px-3 py-2 text-red-600 hover:bg-red-50 rounded transition" data-id="${cat.id}" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            $('#listContainer').html(html);
        }

        function openCategoryForm(id) {
            const url = id ? `/settings/category/form/${id}?type=${categoryType}` : `/settings/category/form?type=${categoryType}`;
            
            $('#formContainer').load(url, function() {
                $('#formModal').removeClass('hidden');
                $('#formContainer').scrollTop(0);
            });
        }

        function closeFormCategoryModal() {
            $('#formModal').addClass('hidden');
            $('#formContainer').html('');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: 'Tindakan ini tidak dapat dibatalkan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    deleteCategory(id);
                }
            });
        }

        function deleteCategory(id) {
            $.ajax({
                url: `/api/categories/${id}`,
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Kategori berhasil dihapus',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Update cache immediately (no need to fetch)
                        CacheManager.removeCategoryFromCache(id);
                    });
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.responseJSON?.message || 'Gagal menghapus kategori'
                    });
                }
            });
        }

        window.saveCategoryForm = function(formData) {
            const isEdit = formData.id ? true : false;
            const categoryId = formData.id;
            const url = isEdit ? `/api/categories/${categoryId}` : '/api/categories';
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Update cache immediately (no need to fetch)
                        if (isEdit) {
                            CacheManager.updateCategoryInCache(categoryId, response.data);
                        } else {
                            CacheManager.addCategoryToCache(response.data);
                        }
                        
                        closeFormCategoryModal();
                    });
                },
                error: function(error) {
                    if (error.status === 422) {
                        const errors = error.responseJSON.errors;
                        let msg = '';
                        $.each(errors, function(field, messages) {
                            msg += messages[0] + '\n';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Error',
                            text: msg
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.responseJSON?.message || 'Gagal menyimpan kategori'
                        });
                    }
                }
            });
        };
    </script>
</body>
</html>

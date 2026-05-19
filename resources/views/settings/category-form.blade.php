<!-- Category Form Modal -->
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900" id="formTitle">
            Tambah Kategori
        </h2>
        <button onclick="closeFormCategoryModal()" class="text-gray-600 hover:text-gray-900 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Form -->
    <form id="categoryForm" class="space-y-4">
        <!-- Name -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Gaji, Belanja, dll" required>
            <span class="name-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
            <textarea name="description" id="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Catatan tambahan..."></textarea>
            <span class="description-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Type (hidden) -->
        <input type="hidden" name="type" id="type" value="{{ request('type', 'income') }}">

        <!-- Icon Picker -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Icon</label>
            <div class="grid grid-cols-5 gap-2" id="iconGrid">
                <!-- Icons will be populated here -->
            </div>
            <input type="hidden" name="icon" id="icon" value="fa-tag">
            <span class="icon-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Color Picker -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Warna</label>
            <div class="flex items-center space-x-4">
                <div class="flex space-x-2" id="colorGrid">
                    <!-- Colors will be populated here -->
                </div>
                <input type="hidden" name="color" id="color" value="#3B82F6">
            </div>
            <span class="color-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Buttons -->
        <div class="flex space-x-3 pt-4">
            <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 rounded-lg transition disabled:opacity-50" id="submitBtn">
                Tambah
            </button>
            <button type="button" onclick="closeFormCategoryModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold py-3 rounded-lg transition">
                Batal
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        const icons = [
            'fa-tag', 'fa-briefcase', 'fa-utensils', 'fa-bus', 'fa-home',
            'fa-heart', 'fa-book', 'fa-gamepad', 'fa-film', 'fa-dumbbell',
            'fa-flask', 'fa-music', 'fa-camera', 'fa-gift', 'fa-handshake'
        ];

        const colors = [
            '#EF4444', '#F97316', '#EAB308', '#22C55E',
            '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9',
            '#3B82F6', '#6366F1', '#8B5CF6', '#D946EF'
        ];

        const token = localStorage.getItem('api_token');
        const categoryId = "{{ request('id') ?? request()->route('id') ?? null }}";
        const categoryType = "{{ request('type', 'income') }}";

        // Load category data if editing
        if (categoryId) {
            $.ajax({
                url: `/api/categories/${categoryId}`,
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` },
                success: function(response) {
                    if (response.data) {
                        const cat = response.data;
                        $('#formTitle').text('Edit Kategori');
                        $('#name').val(cat.name);
                        $('#description').val(cat.description || '');
                        $('#type').val(cat.type);
                        $('#icon').val(cat.icon);
                        $('#color').val(cat.color);
                        $('#submitBtn').text('Update');

                        // Re-render icon picker with loaded value
                        renderPickers(cat.icon, cat.color);
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat kategori'
                    });
                }
            });
        } else {
            renderPickers('fa-tag', '#3B82F6');
        }

        function renderPickers(selectedIcon, selectedColor) {
            // Render icons
            icons.forEach(icon => {
                const isSelected = selectedIcon === icon ? 'ring-2 ring-blue-500 border-blue-500' : 'border-gray-200';
                const html = `
                    <button type="button" class="icon-btn p-3 border-2 rounded-lg hover:border-blue-500 transition ${isSelected}" data-icon="${icon}">
                        <i class="fas ${icon} text-xl text-gray-700"></i>
                    </button>
                `;
                $('#iconGrid').append(html);
            });

            // Render colors
            colors.forEach(color => {
                const isSelected = selectedColor === color ? 'ring-4 ring-offset-2 ring-gray-400' : '';
                const html = `
                    <button type="button" class="color-btn w-10 h-10 rounded-full transition hover:scale-110 ${isSelected}" style="background-color: ${color}" data-color="${color}"></button>
                `;
                $('#colorGrid').append(html);
            });

            // Icon selection
            $('.icon-btn').on('click', function(e) {
                e.preventDefault();
                $('.icon-btn').removeClass('ring-2 ring-blue-500 border-blue-500').addClass('border-gray-200');
                $(this).addClass('ring-2 ring-blue-500 border-blue-500').removeClass('border-gray-200');
                $('#icon').val($(this).data('icon'));
            });

            // Color selection
            $('.color-btn').on('click', function(e) {
                e.preventDefault();
                $('.color-btn').removeClass('ring-4 ring-offset-2 ring-gray-400');
                $(this).addClass('ring-4 ring-offset-2 ring-gray-400');
                $('#color').val($(this).data('color'));
            });
        }

        // Form submission
        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();

            const isEdit = categoryId ? true : false;

            const formData = {
                name: $('#name').val(),
                description: $('#description').val(),
                type: categoryType,
                icon: $('#icon').val(),
                color: $('#color').val()
            };

            // Clear errors
            $('.error').addClass('hidden');

            const url = isEdit ? `/api/categories/${categoryId}` : '/api/categories';
            const method = isEdit ? 'PUT' : 'POST';

            $('#submitBtn').prop('disabled', true).css('opacity', '0.5');

            $.ajax({
                url: url,
                method: method,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
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
                        closeFormCategoryModal();
                        loadCategories();
                    });
                },
                error: function(error) {
                    $('#submitBtn').prop('disabled', false).css('opacity', '1');

                    if (error.status === 422) {
                        const errors = error.responseJSON.errors || {};
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
    });
</script>

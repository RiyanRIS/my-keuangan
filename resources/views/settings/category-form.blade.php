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
            <input type="text" name="name" id="name"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Gaji, Belanja, dll" required>
            <span class="name-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
            <textarea name="description" id="description"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows="2" placeholder="Catatan tambahan..."></textarea>
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
            <button type="button" id="colorPickerBtn" 
                class="w-16 h-16 rounded-full border-2 border-gray-300 hover:border-gray-500 transition shadow-sm"
                style="background-color: #3B82F6;">
            </button>
            <input type="hidden" name="color" id="color" value="#3B82F6">
            <span class="color-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Backdrop -->
        <div id="colorPickerBackdrop"
            class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm z-40">
        </div>

        <!-- Color Picker Bottom Sheet -->
        <div id="colorPickerSheet" class="hidden fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl z-50 max-h-[50vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex items-center justify-between rounded-t-3xl">
                <h3 class="font-semibold text-gray-900">Pilih Warna</h3>
                <button type="button" class="w-10 h-10 rounded-full text-gray-600 hover:text-gray-900" id="closeColorSheet">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-5 gap-3" id="colorGridSheet">
                    <!-- Colors akan diisi via JS -->
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex space-x-3 pt-4">
            <button type="submit"
                class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 rounded-lg transition disabled:opacity-50"
                id="submitBtn">
                Tambah
            </button>
            <button type="button" onclick="closeFormCategoryModal()"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold py-3 rounded-lg transition">
                Batal
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        const incomeIcons = [

            // Salary & Work
            'fa-wallet',
            'fa-money-bill-wave',
            'fa-sack-dollar',
            'fa-briefcase',
            'fa-building',

            // Business
            'fa-store',
            'fa-shop',
            'fa-chart-line',
            'fa-coins',

            // Investment
            'fa-piggy-bank',
            'fa-chart-pie',
            'fa-arrow-trend-up',

            // Bonus / Gift
            'fa-gift',
            'fa-award',
            'fa-trophy',

            // Freelance / Side Hustle
            'fa-laptop',
            'fa-code',
            'fa-pen-nib',
            'fa-camera',

            // Passive Income
            'fa-hand-holding-dollar',
            'fa-money-check-dollar',

            // Misc
            'fa-circle-plus',
            'fa-plus',
            'fa-handshake'

        ];

        const expenseIcons = [

            // Food & Drink
            'fa-utensils',
            'fa-burger',
            'fa-pizza-slice',
            'fa-mug-hot',
            'fa-ice-cream',

            // Transport
            'fa-bus',
            'fa-car',
            'fa-motorcycle',
            'fa-gas-pump',
            'fa-plane',

            // Shopping
            'fa-cart-shopping',
            'fa-bag-shopping',
            'fa-shirt',
            'fa-gem',

            // Home
            'fa-house',
            'fa-couch',
            'fa-lightbulb',

            // Health
            'fa-heart-pulse',
            'fa-briefcase-medical',
            'fa-pills',

            // Education
            'fa-book',
            'fa-graduation-cap',

            // Entertainment
            'fa-film',
            'fa-gamepad',
            'fa-music',
            'fa-headphones',

            // Sport
            'fa-dumbbell',
            'fa-biking',
            'fa-swimmer',

            // Family
            'fa-baby',
            'fa-paw',

            // Social
            'fa-gift',
            'fa-cake-candles',
            'fa-champagne-glasses',

            // Bills
            'fa-receipt',
            'fa-file-invoice-dollar',

            // Misc
            'fa-tag',
            'fa-ellipsis',
            'fa-circle-minus'

        ];

        const icons = '{{ request('type', 'income') }}' === 'income' ?
            incomeIcons :
            expenseIcons;

        const colors = [

            // Red
            '#EF4444',
            '#DC2626',
            '#F87171',

            // Orange
            '#F97316',
            '#EA580C',
            '#FB923C',

            // Amber / Yellow
            '#EAB308',
            '#FACC15',
            '#FDE047',

            // Lime / Green
            '#84CC16',
            '#22C55E',
            '#16A34A',
            '#4ADE80',

            // Emerald / Teal
            '#10B981',
            '#14B8A6',
            '#0D9488',
            '#2DD4BF',

            // Cyan / Sky
            '#06B6D4',
            '#0891B2',
            '#38BDF8',
            '#0EA5E9',

            // Blue
            '#3B82F6',
            '#2563EB',
            '#1D4ED8',
            '#60A5FA',

            // Indigo
            '#6366F1',
            '#4F46E5',
            '#818CF8',

            // Purple
            '#8B5CF6',
            '#7C3AED',
            '#A78BFA',

            // Pink / Fuchsia
            '#D946EF',
            '#EC4899',
            '#F472B6',

            // Rose
            '#F43F5E',
            '#FB7185',

            // Neutral / Dark
            '#6B7280',
            '#374151',
            '#111827'

        ];

        const token = localStorage.getItem('api_token');
        const categoryId = "{{ request('id') ?? (request()->route('id') ?? null) }}";
        const categoryType = "{{ request('type', 'income') }}";

        // Default colors based on type
        const defaultColors = {
            'income': '#22C55E',   // Green
            'expense': '#EF4444',  // Red
            'transfer': '#3B82F6'  // Blue
        };

        const defaultColor = defaultColors[categoryType] || '#3B82F6';

        // Load category data if editing
        if (categoryId) {
            $.ajax({
                url: `/api/categories/${categoryId}`,
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                success: function(response) {
                    if (response.data) {
                        const cat = response.data;
                        $('#formTitle').text('Edit Kategori');
                        $('#name').val(cat.name);
                        $('#description').val(cat.description || '');
                        $('#type').val(cat.type);
                        $('#icon').val(cat.icon);
                        $('#submitBtn').text('Update');

                        $('#colorPickerBtn').css('background-color', cat.color);
                        $('#color').val(cat.color);

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
            renderPickers('fa-tag', defaultColor);
            $('#colorPickerBtn').css('background-color', defaultColor);
            $('#color').val(defaultColor);
        }

        function renderPickers(selectedIcon, selectedColor) {
            // Render icons
            icons.forEach(icon => {
                const isSelected = selectedIcon === icon ? 'ring-2 ring-blue-500 border-blue-500' :
                    'border-gray-200';
                const html = `
                    <button type="button" class="icon-btn p-3 border-2 rounded-lg hover:border-blue-500 transition ${isSelected}" data-icon="${icon}">
                        <i class="fas ${icon} text-xl text-gray-700"></i>
                    </button>
                `;
                $('#iconGrid').append(html);
            });

            // Render colors in bottom sheet
            colors.forEach(color => {
                const isSelected = selectedColor === color ? 'ring-4 ring-offset-2 ring-black scale-110' : '';
                const html = `
                    <button type="button" class="color-sheet-btn w-12 h-12 rounded-full active:scale-95 transition transition-transform duration-150 ${isSelected}" style="background-color: ${color}" data-color="${color}"></button>
                `;
                $('#colorGridSheet').append(html);
            });

            // Icon selection
            $('.icon-btn').on('click', function(e) {
                e.preventDefault();
                $('.icon-btn').removeClass('ring-2 ring-blue-500 border-blue-500').addClass(
                    'border-gray-200');
                $(this).addClass('ring-2 ring-blue-500 border-blue-500').removeClass('border-gray-200');
                $('#icon').val($(this).data('icon'));
            });

            // Color picker button click
            $('#colorPickerBtn').on('click', function(e) {
                e.preventDefault();
                $('#colorPickerSheet').removeClass('hidden');
                $('#colorPickerBackdrop').removeClass('hidden');
            });

            // Close color sheet
            $('#closeColorSheet').on('click', function() {
                $('#colorPickerSheet').addClass('hidden');
                $('#colorPickerBackdrop').addClass('hidden');
            });

            // Color selection from sheet
            $('.color-sheet-btn').on('click', function(e) {
                e.preventDefault();
                const color = $(this).data('color');
                
                // Update button
                $('#colorPickerBtn').css('background-color', color);
                $('#color').val(color);
                
                // Update selected state
                $('.color-sheet-btn').removeClass('ring-4 ring-offset-2 ring-gray-400');
                $(this).addClass('ring-4 ring-offset-2 ring-gray-400');
                
                // Close sheet
                $('#colorPickerSheet').addClass('hidden');
                $('#colorPickerBackdrop').addClass('hidden');
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

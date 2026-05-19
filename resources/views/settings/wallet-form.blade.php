<!-- Wallet Form Modal -->
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900" id="formTitle">
            Tambah Dompet
        </h2>
        <button onclick="closeFormWalletModal()" class="text-gray-600 hover:text-gray-900 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Form -->
    <form id="walletForm" class="space-y-4">
        <!-- Wallet Type -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Dompet</label>
            <select name="wallet_type_id" id="wallet_type_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
                <option value="">Memuat...</option>
            </select>
            <span class="wallet_type_id-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Dompet</label>
            <input type="text" name="name" id="name"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="BCA, OVO, Tunai, dll" required>
            <span class="name-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Balance -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Saldo Awal</label>
            <div class="flex items-center space-x-2">
                <span class="text-gray-700 font-semibold">Rp</span>
                <input type="number" name="balance" id="balance"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="0" min="0" step="0.01" required value="0">
            </div>
            <span class="balance-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Icon Picker -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Icon</label>
            <div class="grid grid-cols-5 gap-2" id="iconGrid">
                <!-- Icons will be populated here -->
            </div>
            <input type="hidden" name="icon" id="icon" value="fa-wallet">
            <span class="icon-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Color Picker -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Warna</label>
            <button type="button" id="colorPickerBtn" 
                class="w-12 h-12 rounded-full border-2 border-gray-300 hover:border-gray-500 transition shadow-sm"
                style="background-color: #3B82F6;">
            </button>
            <input type="hidden" name="color" id="color" value="#3B82F6">
            <span class="color-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Color Picker Bottom Sheet -->
        <div id="colorPickerSheet" class="hidden fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl z-50 max-h-[50vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex items-center justify-between rounded-t-3xl">
                <h3 class="font-semibold text-gray-900">Pilih Warna</h3>
                <button type="button" class="text-gray-600 hover:text-gray-900" id="closeColorSheet">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-6 gap-3" id="colorGridSheet">
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
            <button type="button" onclick="closeFormWalletModal()"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold py-3 rounded-lg transition">
                Batal
            </button>
        </div>
    </form>
</div>

<script>
    
    $(document).ready(async function() {
        const walletId = "{{ request('id') ?? null }}";
        const token = localStorage.getItem('api_token');
        
        const walletIcons = [
            // Banks
            'fa-wallet',
            'fa-building',
            'fa-bank',
            'fa-credit-card',
            'fa-coins',

            // Digital
            'fa-mobile',
            'fa-phone',
            'fa-laptop',
            'fa-computer',

            // Cash
            'fa-money-bill-wave',
            'fa-money-bill',
            'fa-money-check-dollar',
            'fa-hand-holding-dollar',

            // Payment
            'fa-qrcode',
            'fa-barcode',
            'fa-cash-register',

            // Investment
            'fa-piggy-bank',
            'fa-chart-pie',
            'fa-chart-line',

            // Other
            'fa-circle-plus',
            'fa-plus',
            'fa-tag'
        ];

        const colors = [
            // Red
            '#EF4444', '#DC2626', '#F87171',

            // Orange
            '#F97316', '#EA580C', '#FB923C',

            // Amber / Yellow
            '#EAB308', '#FACC15', '#FDE047',

            // Lime / Green
            '#84CC16', '#22C55E', '#16A34A', '#4ADE80',

            // Emerald / Teal
            '#10B981', '#14B8A6', '#0D9488', '#2DD4BF',

            // Cyan / Sky
            '#06B6D4', '#0891B2', '#38BDF8', '#0EA5E9',

            // Blue
            '#3B82F6', '#2563EB', '#1D4ED8', '#60A5FA',

            // Indigo
            '#6366F1', '#4F46E5', '#818CF8',

            // Purple
            '#8B5CF6', '#7C3AED', '#A78BFA',

            // Pink / Fuchsia
            '#D946EF', '#EC4899', '#F472B6',

            // Rose
            '#F43F5E', '#FB7185',

            // Neutral / Dark
            '#6B7280', '#374151', '#111827'
        ];

        // Render pickers
        renderPickers('fa-wallet', '#3B82F6');

        await loadWalletTypes();

        if (walletId) {
            await loadWallet(walletId);
        }

        function renderPickers(selectedIcon, selectedColor) {
            // Render icons
            walletIcons.forEach(icon => {
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
                const isSelected = selectedColor === color ? 'ring-4 ring-offset-2 ring-gray-400' : '';
                const html = `
                    <button type="button" class="color-sheet-btn w-12 h-12 rounded-full transition hover:scale-110 ${isSelected}" style="background-color: ${color}" data-color="${color}"></button>
                `;
                $('#colorGridSheet').append(html);
            });

            // Icon selection
            $('.icon-btn').on('click', function(e) {
                e.preventDefault();
                $('.icon-btn').removeClass('ring-2 ring-blue-500 border-blue-500').addClass('border-gray-200');
                $(this).addClass('ring-2 ring-blue-500 border-blue-500').removeClass('border-gray-200');
                $('#icon').val($(this).data('icon'));
            });

            // Color picker button click
            $('#colorPickerBtn').on('click', function(e) {
                e.preventDefault();
                $('#colorPickerSheet').removeClass('hidden');
            });

            // Close color sheet
            $('#closeColorSheet').on('click', function() {
                $('#colorPickerSheet').addClass('hidden');
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
            });
        }

        // Form submission
        $('#walletForm').on('submit', function(e) {
            e.preventDefault();

            const isEdit = walletId ? true : false;

            const formData = {
                wallet_type_id: parseInt($('#wallet_type_id').val()),
                name: $('#name').val(),
                balance: parseFloat($('#balance').val()),
                icon: $('#icon').val(),
                color: $('#color').val()
            };

            // Clear errors
            $('.error').addClass('hidden');

            $('#submitBtn').prop('disabled', true).css('opacity', '0.5');

            const url = isEdit ? `/api/wallets/${walletId}` : '/api/wallets';
            const method = isEdit ? 'PUT' : 'POST';

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
                        // Update cache immediately (no need to fetch)
                        if (isEdit) {
                            CacheManager.updateWalletInCache(walletId, response.data);
                        } else {
                            CacheManager.addWalletToCache(response.data);
                        }
                        
                        closeFormWalletModal();
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

    function loadWalletTypes() {
        const token = localStorage.getItem('api_token');
        return $.ajax({
            url: '/api/wallet-types',
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        }).then(function(response) {

            const types = response.data || [];

            let html = '<option value="">Pilih Tipe Dompet</option>';

            types.forEach(type => {

                html += `
                <option value="${type.id}">
                    ${type.name}
                </option>
            `;

            });

            $('#wallet_type_id').html(html);

        });

    }

    function loadWallet(walletId) {
        const token = localStorage.getItem('api_token');
        return $.ajax({
            url: `/api/wallets/${walletId}`,
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        }).then(function(response) {

            if (response.data) {

                const w = response.data;

                $('#formTitle').text('Edit Dompet');

                $('#wallet_type_id').val(w.wallet_type.id);

                $('#name').val(w.name);

                $('#balance').val(w.balance);

                $('#icon').val(w.icon || 'fa-wallet');

                $('#color').val(w.color || '#3B82F6');

                // Update color button
                $('#colorPickerBtn').css('background-color', w.color || '#3B82F6');

                // Update selected icon state
                $('.icon-btn').removeClass('ring-2 ring-blue-500 border-blue-500').addClass('border-gray-200');
                $(`.icon-btn[data-icon="${w.icon || 'fa-wallet'}"]`).addClass('ring-2 ring-blue-500 border-blue-500').removeClass('border-gray-200');

                // Update selected color state
                $('.color-sheet-btn').removeClass('ring-4 ring-offset-2 ring-gray-400');
                $(`.color-sheet-btn[data-color="${w.color || '#3B82F6'}"]`).addClass('ring-4 ring-offset-2 ring-gray-400');

                $('#submitBtn').text('Update');

            }

        }).catch(function() {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat dompet'
            });

        });
    }
</script>

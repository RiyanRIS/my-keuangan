<!-- Wallet Form Modal -->
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900" id="formTitle">
            Tambah Dompet
        </h2>
        <button onclick="closeFormModal()" class="text-gray-600 hover:text-gray-900 transition">
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

        <!-- Buttons -->
        <div class="flex space-x-3 pt-4">
            <button type="submit"
                class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 rounded-lg transition disabled:opacity-50"
                id="submitBtn">
                Tambah
            </button>
            <button type="button" onclick="closeFormModal()"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold py-3 rounded-lg transition">
                Batal
            </button>
        </div>
    </form>
</div>

<script>
    const token = localStorage.getItem('api_token');
    const walletId = "{{ request('id') ?? null }}";
    
    $(document).ready(async function() {

        await loadWalletTypes();

        if (walletId) {
            await loadWallet(walletId);
        }

        // Form submission
        $('#walletForm').on('submit', function(e) {
            e.preventDefault();

            const isEdit = walletId ? true : false;

            const formData = {
                wallet_type_id: parseInt($('#wallet_type_id').val()),
                name: $('#name').val(),
                balance: parseFloat($('#balance').val())
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
                        closeFormModal();
                        loadWallets();
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

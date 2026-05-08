<!-- Wallet Type Form Modal -->
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900" id="formTitle">
            Tambah Tipe Dompet
        </h2>
        <button onclick="closeFormModal()" class="text-gray-600 hover:text-gray-900 transition">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Form -->
    <form id="walletTypeForm" class="space-y-4">
        <!-- Name -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tipe Dompet</label>
            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Bank, E-wallet, Tunai, dll" required>
            <span class="name-error text-red-600 text-sm hidden"></span>
        </div>

        <!-- Buttons -->
        <div class="flex space-x-3 pt-4">
            <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 rounded-lg transition disabled:opacity-50" id="submitBtn">
                Tambah
            </button>
            <button type="button" onclick="closeFormModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold py-3 rounded-lg transition">
                Batal
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        const token = localStorage.getItem('api_token');
        const walletTypeId = "{{ request('id') ?? null }}";

        // Load wallet type data if editing
        if (walletTypeId) {
            $.ajax({
                url: `/api/wallet-types/${walletTypeId}`,
                method: 'GET',
                headers: { 'Authorization': `Bearer ${token}` },
                success: function(response) {
                    if (response.data) {
                        const wt = response.data;
                        $('#formTitle').text('Edit Tipe Dompet');
                        $('#name').val(wt.name);
                        $('#submitBtn').text('Update');
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat tipe dompet'
                    });
                }
            });
        }

        // Form submission
        $('#walletTypeForm').on('submit', function(e) {
            e.preventDefault();

            const isEdit = walletTypeId ? true : false;
            const formData = {
                name: $('#name').val()
            };

            // Clear errors
            $('.error').addClass('hidden');

            $('#submitBtn').prop('disabled', true).css('opacity', '0.5');

            const url = isEdit ? `/api/wallet-types/${walletTypeId}` : '/api/wallet-types';
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
                        loadWalletTypes();
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

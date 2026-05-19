/**
 * Transaction Event Handlers
 * Organizes all event bindings and UI interactions
 * Keeps business logic separate from event handling
 */

window.TransactionEvents = {
    token: localStorage.getItem('api_token'),

    /**
     * Initialize all event listeners
     */
    init() {
        this.bindTypeChangeEvents();
        this.bindDateTimeEvents();
        this.bindNumericKeyboardEvents();
        this.bindFormEvents();
        this.bindCategoryEvents();
        this.bindWalletEvents();
        this.bindNavigationEvents();
    },

    /**
     * Type change events
     */
    bindTypeChangeEvents() {
        TransactionDOM.typeRadios.on('change', (e) => {
            const type = $(e.target).val();
            this.handleTypeChange(type);
        });
    },

    handleTypeChange(type) {
        // Update active visual state
        $('input[name="type"]').parent().find('div').removeClass('active');
        $(`input[name="type"][value="${type}"]`).parent().find('div').addClass('active');

        // Hide all fields and show appropriate ones
        TransactionDOM.hideAllConditionalFields();
        TransactionDOM.showFieldsForType(type);

        // Clear previous selections
        TransactionDOM.clearAllInputs();

        // Reset focus
        $('.focus-border').removeClass('focused');
        TransactionDOM.amountDisplay.focus();

        // Show keyboard, hide datetime picker
        TransactionDOM.numericKeyboard.removeClass('hidden');
        TransactionDOM.dateTimePickerBackdrop.addClass('hidden');
        TransactionDOM.dateTimePickerSheet.addClass('hidden');
    },

    /**
     * DateTime picker events
     */
    bindDateTimeEvents() {
        TransactionDOM.editDateTimeBtn.on('click', () => this.openDateTimePicker());
        TransactionDOM.closeDateTimeSheet.on('click', () => this.closeDateTimePicker());
        TransactionDOM.confirmDateTimeBtn.on('click', () => this.confirmDateTime());
        TransactionDOM.dateTimePickerBackdrop.on('click', () => this.closeDateTimePicker());
    },

    openDateTimePicker() {
        TransactionDOM.dateTimePickerDate.val(TransactionDOM.transactionDate.val());
        TransactionDOM.dateTimePickerTime.val(TransactionDOM.transactionTime.val());
        TransactionDOM.dateTimePickerBackdrop.removeClass('hidden');
        TransactionDOM.dateTimePickerSheet.removeClass('hidden');
        TransactionDOM.numericKeyboard.addClass('hidden');
    },

    closeDateTimePicker() {
        TransactionDOM.dateTimePickerBackdrop.addClass('hidden');
        TransactionDOM.dateTimePickerSheet.addClass('hidden');
    },

    confirmDateTime() {
        const date = TransactionDOM.dateTimePickerDate.val();
        const time = TransactionDOM.dateTimePickerTime.val();
        
        if (date && time) {
            TransactionDOM.transactionDate.val(date);
            TransactionDOM.transactionTime.val(time);
            this.updateDateTimeDisplay();
            this.closeDateTimePicker();
        }
    },

    /**
     * Numeric keyboard events
     */
    bindNumericKeyboardEvents() {
        TransactionDOM.amountDisplay.on('focus', () => {
            TransactionDOM.numericKeyboard.removeClass('hidden');
            TransactionDOM.dateTimePickerBackdrop.addClass('hidden');
            TransactionDOM.dateTimePickerSheet.addClass('hidden');
        });

        TransactionDOM.closeKeyboard.on('click', () => {
            TransactionDOM.numericKeyboard.addClass('hidden');
        });

        TransactionDOM.keyBtns
            .not('#closeKeyboard, #backspace, #done')
            .on('click', (e) => {
                const value = $(e.target).text();
                this.appendAmountValue(value);
            });

        TransactionDOM.backspaceBtn.on('click', () => {
            this.removeLastAmountDigit();
        });

        TransactionDOM.doneBtn.on('click', () => {
            TransactionDOM.numericKeyboard.addClass('hidden');
        });
    },

    appendAmountValue(value) {
        const current = TransactionDOM.amountInput.val();
        const newValue = current + value;
        TransactionDOM.amountInput.val(newValue);
        this.updateAmountDisplay(newValue);
    },

    removeLastAmountDigit() {
        const current = TransactionDOM.amountInput.val();
        const newValue = current.slice(0, -1);
        TransactionDOM.amountInput.val(newValue);
        this.updateAmountDisplay(newValue);
    },

    /**
     * Form submission events
     */
    bindFormEvents() {
        TransactionDOM.form.on('submit', (e) => {
            e.preventDefault();
            this.submitTransaction();
        });

        TransactionDOM.continueBtn.on('click', () => {
            this.handleContinueClick();
        });
    },

    handleContinueClick() {
        const formValues = TransactionDOM.getFormValues();
        const type = formValues.type;
        const focusOrder = TransactionConfig.getFocusOrder(type);

        for (let field of focusOrder) {
            if (!this.isFieldComplete(field, formValues)) {
                this.focusField(field);
                return;
            }
        }

        // All fields complete, submit
        this.submitTransaction();
    },

    isFieldComplete(field, formValues) {
        const fieldMap = {
            'amount': formValues.amount,
            'category': formValues.category_id,
            'wallet': formValues.wallet_id,
            'from_wallet': formValues.from_wallet_id,
            'to_wallet': formValues.to_wallet_id
        };
        return fieldMap[field] && fieldMap[field] !== '';
    },

    focusField(field) {
        const fieldMap = {
            'amount': TransactionDOM.amountDisplay,
            'category': TransactionDOM.categoryField,
            'wallet': TransactionDOM.walletField,
            'from_wallet': TransactionDOM.fromWalletField,
            'to_wallet': TransactionDOM.toWalletField
        };

        const element = fieldMap[field];
        if (element) {
            element.get(0)?.scrollIntoView({ behavior: 'smooth' });
        }
    },

    submitTransaction() {
        const formValues = TransactionDOM.getFormValues();
        
        // Validate form
        if (!TransactionConfig.isFormComplete(formValues)) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Lengkap',
                text: 'Mohon isi semua field yang diperlukan'
            });
            return;
        }

        // Build payload
        const payload = TransactionConfig.buildPayload(formValues);
        const endpoint = TransactionConfig.getEndpoint(formValues.type);

        // Disable submit button
        TransactionDOM.submitBtn.prop('disabled', true).css('opacity', '0.5');

        // Send request
        $.ajax({
            url: endpoint,
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.token}`,
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(payload),
            success: () => this.handleSubmitSuccess(),
            error: (error) => this.handleSubmitError(error)
        });
    },

    handleSubmitSuccess() {
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

    handleSubmitError(error) {
        TransactionDOM.submitBtn.prop('disabled', false).css('opacity', '1');

        if (error.status === 422) {
            const errors = error.responseJSON?.errors || {};
            Object.entries(errors).forEach(([field, messages]) => {
                const errorClass = `.${field}-error`;
                $(errorClass).text(messages[0]).removeClass('hidden');
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.responseJSON?.message || 'Terjadi kesalahan'
            });
        }
    },

    /**
     * Category events
     */
    bindCategoryEvents() {
        $(document).on('click', '#editCategoryGrid, .edit-category-btn', () => {
            TransactionDOM.categoryManagerState.removeClass('hidden');
            this.loadManageCategories();
        });

        TransactionDOM.categoryDisplay.on('click', () => {
            TransactionDOM.categoryManagerState.removeClass('hidden');
            this.loadManageCategories();
        });

        TransactionDOM.closeCategoryManager.on('click', () => {
            TransactionDOM.categoryManagerState.addClass('hidden');
        });

        $(document).on('click', '#categoryManagerContent > div', (e) => {
            this.selectCategory($(e.currentTarget));
        });

        $(document).on('click', '.btn-edit-category', (e) => {
            e.stopPropagation();
            const id = $(e.target).closest('.btn-edit-category').data('id');
            this.openCategoryForm(id);
        });

        $(document).on('click', '.btn-delete-category', (e) => {
            e.stopPropagation();
            const id = $(e.target).closest('.btn-delete-category').data('id');
            this.confirmDeleteCategory(id);
        });

        TransactionDOM.btnAddCategory.on('click', () => {
            this.openCategoryForm(null);
        });

        TransactionDOM.formCategoryModal.on('click', (e) => {
            if (e.target === e.currentTarget) {
                this.closeFormCategoryModal();
            }
        });
    },

    loadManageCategories() {
        const type = TransactionDOM.getActiveType() || 'expense';
        const allCategories = CacheManager.getAllCategories();
        const categories = allCategories.filter(c => c.type === type) || [];
        
        TransactionDOM.categoryManagerTitle.text('Kelola Kategori');
        TransactionDOM.categoryManagerSubtitle.text(TransactionConfig.types[type]?.label || type);
        
        this.renderManageCategories(categories);
    },

    renderManageCategories(categories) {
        let html = '';
        
        categories.forEach(cat => {
            const bgColor = `${cat.color}20`;
            html += `
            <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                         style="background-color:${bgColor}; color:${cat.color};">
                        <i class="fas ${cat.icon}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">${cat.name}</h3>
                        ${cat.description ? `<p class="text-xs text-gray-500 truncate">${cat.description}</p>` : ''}
                    </div>
                </div>
                <div class="flex items-center gap-2 ml-3">
                    <button class="btn-edit-category w-10 h-10 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition" data-id="${cat.id}">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn-delete-category w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition" data-id="${cat.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
        });

        TransactionDOM.categoryManagerContent.html(html);
    },

    selectCategory($element) {
        const categoryBtn = $element.find('.btn-edit-category');
        if (categoryBtn.length === 0) return;

        const id = categoryBtn.data('id');
        const name = $element.find('h3').text().trim();
        const icon = $element.find('i').attr('class');

        TransactionDOM.categoryInput.val(id);
        TransactionDOM.categoryDisplay.html(
            `<i class="fas ${icon} text-gray-400"></i><span id="categoryDisplayText">${name}</span>`
        );

        TransactionDOM.categoryManagerState.addClass('hidden');
    },

    openCategoryForm(id = null) {
        const type = TransactionDOM.getActiveType() || 'expense';
        const url = id ? 
            `/settings/category/form/${id}?type=${type}` : 
            `/settings/category/form?type=${type}`;

        TransactionDOM.formCategoryContainer.load(url, () => {
            TransactionDOM.formCategoryModal.removeClass('hidden');
        });
    },

    closeFormCategoryModal() {
        TransactionDOM.formCategoryModal.addClass('hidden');
        TransactionDOM.formCategoryContainer.html('');
        this.loadManageCategories();
    },

    confirmDeleteCategory(id) {
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
                this.deleteCategory(id);
            }
        });
    },

    deleteCategory(id) {
        $.ajax({
            url: `/api/categories/${id}`,
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${this.token}`,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus',
                    text: 'Kategori berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    CacheManager.removeCategoryFromCache(id);
                    this.loadManageCategories();
                });
            },
            error: (error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.responseJSON?.message || 'Gagal menghapus kategori'
                });
            }
        });
    },

    /**
     * Wallet events
     */
    bindWalletEvents() {
        $(document).on('click', '#editWalletGrid, .edit-wallet-btn', () => {
            TransactionDOM.walletManagerState.removeClass('hidden');
            this.loadManageWallets();
        });

        TransactionDOM.walletDisplay.on('click', () => {
            TransactionDOM.walletManagerState.removeClass('hidden');
            TransactionDOM.walletManagerState.removeData('selectTarget');
            this.loadManageWallets();
        });

        TransactionDOM.fromWalletDisplay.on('click', () => {
            TransactionDOM.walletManagerState.removeClass('hidden');
            TransactionDOM.walletManagerState.data('selectTarget', 'from_wallet_id');
            this.loadManageWallets();
        });

        TransactionDOM.toWalletDisplay.on('click', () => {
            TransactionDOM.walletManagerState.removeClass('hidden');
            TransactionDOM.walletManagerState.data('selectTarget', 'to_wallet_id');
            this.loadManageWallets();
        });

        TransactionDOM.closeWalletManager.on('click', () => {
            TransactionDOM.walletManagerState.addClass('hidden');
            TransactionDOM.walletManagerState.removeData('selectTarget');
        });

        $(document).on('click', '#walletManagerContent > div', (e) => {
            this.selectWallet($(e.currentTarget));
        });

        $(document).on('click', '.btn-edit-wallet', (e) => {
            e.stopPropagation();
            const id = $(e.target).closest('.btn-edit-wallet').data('id');
            this.openWalletForm(id);
        });

        $(document).on('click', '.btn-delete-wallet', (e) => {
            e.stopPropagation();
            const id = $(e.target).closest('.btn-delete-wallet').data('id');
            this.confirmDeleteWallet(id);
        });

        TransactionDOM.btnAddWallet.on('click', () => {
            this.openWalletForm(null);
        });

        TransactionDOM.formWalletModal.on('click', (e) => {
            if (e.target === e.currentTarget) {
                this.closeFormWalletModal();
            }
        });
    },

    loadManageWallets() {
        const allWallets = CacheManager.getAllWallets();
        
        TransactionDOM.walletManagerTitle.text('Kelola Dompet');
        TransactionDOM.walletManagerSubtitle.text('Pilih Dompet');
        
        this.renderManageWallets(allWallets);
    },

    renderManageWallets(wallets) {
        let html = '';
        
        wallets.forEach(wallet => {
            const bgColor = `${wallet.color}20`;
            html += `
            <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                         style="background-color:${bgColor}; color:${wallet.color};">
                        <i class="fas ${wallet.icon}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 truncate">${wallet.name}</h3>
                        ${wallet.description ? `<p class="text-xs text-gray-500 truncate">${wallet.description}</p>` : ''}
                    </div>
                </div>
                <div class="flex items-center gap-2 ml-3">
                    <button class="btn-edit-wallet w-10 h-10 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition" data-id="${wallet.id}">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn-delete-wallet w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition" data-id="${wallet.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>`;
        });

        TransactionDOM.walletManagerContent.html(html);
    },

    selectWallet($element) {
        const id = $element.find('.btn-edit-wallet').data('id');
        const name = $element.find('h3').text().trim();
        const icon = $element.find('i').attr('class');
        const selectTarget = TransactionDOM.walletManagerState.data('selectTarget') || 'wallet_id';

        $(`#${selectTarget}`).val(id);

        if (selectTarget === 'wallet_id') {
            TransactionDOM.walletDisplay.html(
                `<i class="fas ${icon} text-gray-400"></i><span id="walletDisplayText">${name}</span>`
            );
        } else if (selectTarget === 'from_wallet_id') {
            TransactionDOM.fromWalletDisplay.html(
                `<i class="fas ${icon} text-gray-400"></i><span id="fromWalletDisplayText">${name}</span>`
            );
        } else if (selectTarget === 'to_wallet_id') {
            TransactionDOM.toWalletDisplay.html(
                `<i class="fas ${icon} text-gray-400"></i><span id="toWalletDisplayText">${name}</span>`
            );
        }

        TransactionDOM.walletManagerState.addClass('hidden');
        TransactionDOM.walletManagerState.removeData('selectTarget');
    },

    openWalletForm(id = null) {
        const url = id ? `/settings/wallet/form/${id}` : `/settings/wallet/form`;
        
        TransactionDOM.formWalletContainer.load(url, () => {
            TransactionDOM.formWalletModal.removeClass('hidden');
        });
    },

    closeFormWalletModal() {
        TransactionDOM.formWalletModal.addClass('hidden');
        TransactionDOM.formWalletContainer.html('');
        this.loadManageWallets();
    },

    confirmDeleteWallet(id) {
        Swal.fire({
            title: 'Hapus Dompet?',
            text: 'Tindakan ini tidak dapat dibatalkan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                this.deleteWallet(id);
            }
        });
    },

    deleteWallet(id) {
        $.ajax({
            url: `/api/wallets/${id}`,
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${this.token}`,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus',
                    text: 'Dompet berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    CacheManager.removeWalletFromCache(id);
                    this.loadManageWallets();
                });
            },
            error: (error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.responseJSON?.message || 'Gagal menghapus dompet'
                });
            }
        });
    },

    /**
     * Navigation events
     */
    bindNavigationEvents() {
        TransactionDOM.closeTransactionModal.on('click', () => {
            history.back();
        });
    },

    /**
     * Utility functions
     */
    updateDateTimeDisplay() {
        const date = TransactionDOM.transactionDate.val();
        const time = TransactionDOM.transactionTime.val();

        if (date && time) {
            const dateObj = new Date(date + 'T' + time);
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const formatted = dateObj.toLocaleDateString('id-ID', options);
            TransactionDOM.dateTimeDisplay.text(formatted);
        }
    },

    updateAmountDisplay(value) {
        if (value === '' || value === '0') {
            TransactionDOM.amountDisplay.val('0');
        } else {
            const numValue = parseInt(value.replace(/\D/g, '')) || 0;
            TransactionDOM.amountDisplay.val(numValue.toLocaleString('id-ID'));
        }
    }
};

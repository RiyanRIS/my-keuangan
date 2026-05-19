/**
 * Transaction DOM Cache
 * Caches all DOM selectors to avoid repeated queries
 * Initialize with init() after DOM is ready
 */

window.TransactionDOM = {
    /**
     * Initialize all DOM caches
     * Must be called inside $(document).ready()
     */
    init() {
        // Form elements
        this.form = $('#transactionForm');
        this.amountDisplay = $('#amountDisplay');
        this.amountInput = $('#amount');
        this.noteInput = $('#note');
        this.transactionDate = $('#transaction_date');
        this.transactionTime = $('#transaction_time');
        this.submitBtn = $('#submitBtn');
        this.continueBtn = $('#continueBtn');

        // Transaction type
        this.typeRadios = $('input[name="type"]');
        
        // Conditional fields
        this.categoryField = $('#categoryField');
        this.walletField = $('#walletField');
        this.fromWalletField = $('#fromWalletField');
        this.toWalletField = $('#toWalletField');
        
        // Category elements
        this.categoryDisplay = $('#categoryDisplay');
        this.categoryDisplayText = $('#categoryDisplayText');
        this.categoryInput = $('#category_id');
        this.categoryManagerState = $('#categoryManagerState');
        this.categoryManagerTitle = $('#categoryManagerTitle');
        this.categoryManagerSubtitle = $('#categoryManagerSubtitle');
        this.categoryManagerContent = $('#categoryManagerContent');
        this.formCategoryModal = $('#formCategoryModal');
        this.formCategoryContainer = $('#formCategoryContainer');
        this.closeCategoryManager = $('#closeCategoryManager');
        this.btnAddCategory = $('#btnAddCategory');
        
        // Wallet elements
        this.walletDisplay = $('#walletDisplay');
        this.walletDisplayText = $('#walletDisplayText');
        this.walletInput = $('#wallet_id');
        this.fromWalletDisplay = $('#fromWalletDisplay');
        this.fromWalletDisplayText = $('#fromWalletDisplayText');
        this.fromWalletInput = $('#from_wallet_id');
        this.toWalletDisplay = $('#toWalletDisplay');
        this.toWalletDisplayText = $('#toWalletDisplayText');
        this.toWalletInput = $('#to_wallet_id');
        this.walletManagerState = $('#walletManagerState');
        this.walletManagerTitle = $('#walletManagerTitle');
        this.walletManagerSubtitle = $('#walletManagerSubtitle');
        this.walletManagerContent = $('#walletManagerContent');
        this.formWalletModal = $('#formWalletModal');
        this.formWalletContainer = $('#formWalletContainer');
        this.closeWalletManager = $('#closeWalletManager');
        this.btnAddWallet = $('#btnAddWallet');
        
        // DateTime picker
        this.dateTimePickerSheet = $('#dateTimePickerSheet');
        this.dateTimePickerBackdrop = $('#dateTimePickerBackdrop');
        this.dateTimeDisplay = $('#dateTimeDisplay');
        this.editDateTimeBtn = $('#editDateTimeBtn');
        this.closeDateTimeSheet = $('#closeDateTimeSheet');
        this.dateTimePickerDate = $('#dateTimePickerDate');
        this.dateTimePickerTime = $('#dateTimePickerTime');
        this.confirmDateTimeBtn = $('#confirmDateTimeBtn');
        
        // Numeric keyboard
        this.numericKeyboard = $('.numeric-keyboard-floating');
        this.keyBtns = $('.key-btn');
        this.backspaceBtn = $('#backspace');
        this.doneBtn = $('#done');
        this.closeKeyboard = $('#closeKeyboard');
        
        // Modal close
        this.closeTransactionModal = $('#closeTransactionModal');
    },

    /**
     * Utility: Get active transaction type
     */
    getActiveType() {
        return this.typeRadios.filter(':checked').val();
    },

    /**
     * Utility: Hide all conditional fields
     */
    hideAllConditionalFields() {
        this.categoryField.addClass('hidden');
        this.walletField.addClass('hidden');
        this.fromWalletField.addClass('hidden');
        this.toWalletField.addClass('hidden');
    },

    /**
     * Utility: Show fields for transaction type
     */
    showFieldsForType(type) {
        if (type === 'income' || type === 'expense') {
            this.walletField.removeClass('hidden');
            this.categoryField.removeClass('hidden');
        } else if (type === 'transfer') {
            this.fromWalletField.removeClass('hidden');
            this.toWalletField.removeClass('hidden');
        }
    },

    /**
     * Utility: Clear all form inputs
     */
    clearAllInputs() {
        this.amountInput.val('');
        this.categoryInput.val('');
        this.walletInput.val('');
        this.fromWalletInput.val('');
        this.toWalletInput.val('');
    },

    /**
     * Utility: Get current form values
     */
    getFormValues() {
        const type = this.getActiveType();
        return {
            type: type,
            amount: this.amountInput.val(),
            category_id: this.categoryInput.val(),
            wallet_id: this.walletInput.val(),
            from_wallet_id: this.fromWalletInput.val(),
            to_wallet_id: this.toWalletInput.val(),
            note: this.noteInput.val(),
            transaction_date: this.transactionDate.val(),
            transaction_time: this.transactionTime.val()
        };
    }
};

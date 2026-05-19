/**
 * Transaction Configuration
 * Separates business logic from UI code
 * Centralized configuration for transaction types and their workflows
 */

window.TransactionConfig = {
    /**
     * API endpoints configuration
     */
    endpoints: {
        income: '/api/transactions/income',
        expense: '/api/transactions/expense',
        transfer: '/api/transactions/transfer'
    },

    /**
     * Transaction type settings
     */
    types: {
        income: {
            label: 'Pemasukan',
            endpoint: '/api/transactions/income',
            requiredFields: ['amount', 'wallet_id', 'category_id'],
            fieldsToShow: ['walletField', 'categoryField'],
            focusOrder: ['amount', 'category', 'wallet']
        },
        expense: {
            label: 'Pengeluaran',
            endpoint: '/api/transactions/expense',
            requiredFields: ['amount', 'wallet_id', 'category_id'],
            fieldsToShow: ['walletField', 'categoryField'],
            focusOrder: ['amount', 'category', 'wallet']
        },
        transfer: {
            label: 'Transfer',
            endpoint: '/api/transactions/transfer',
            requiredFields: ['amount', 'from_wallet_id', 'to_wallet_id'],
            fieldsToShow: ['fromWalletField', 'toWalletField'],
            focusOrder: ['amount', 'from_wallet', 'to_wallet']
        }
    },

    /**
     * Get endpoint for transaction type
     */
    getEndpoint(type) {
        return this.endpoints[type] || null;
    },

    /**
     * Get configuration for transaction type
     */
    getTypeConfig(type) {
        return this.types[type] || null;
    },

    /**
     * Check if type is valid
     */
    isValidType(type) {
        return this.types.hasOwnProperty(type);
    },

    /**
     * Build request payload based on type
     */
    buildPayload(formValues) {
        const { type, amount, note, transaction_date, transaction_time } = formValues;

        let payload = {
            amount: parseFloat(amount),
            note: note,
            transaction_date: transaction_date,
            transaction_time: transaction_time
        };

        if (type === 'income' || type === 'expense') {
            payload.wallet_id = parseInt(formValues.wallet_id);
            payload.category_id = parseInt(formValues.category_id);
        } else if (type === 'transfer') {
            payload.from_wallet_id = parseInt(formValues.from_wallet_id);
            payload.to_wallet_id = parseInt(formValues.to_wallet_id);
        }

        return payload;
    },

    /**
     * Get required fields for type
     */
    getRequiredFields(type) {
        return this.types[type]?.requiredFields || [];
    },

    /**
     * Check if form is complete for type
     */
    isFormComplete(formValues) {
        const { type } = formValues;
        const requiredFields = this.getRequiredFields(type);
        
        return requiredFields.every(field => {
            const value = formValues[field];
            return value && value !== '';
        });
    },

    /**
     * Get fields to show for type
     */
    getFieldsToShow(type) {
        return this.types[type]?.fieldsToShow || [];
    },

    /**
     * Get focus order for type
     */
    getFocusOrder(type) {
        return this.types[type]?.focusOrder || [];
    }
};

<style>
    .active {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .focus-border.focused {
        border-color: #3b82f6;
    }

    .key-btn {
        width: 100%;
        height: 48px;
        margin: 0;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        background: #f9fafb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .key-btn:active {
        background: #e5e7eb;
    }

    .wallet-item,
    .category-item {
        width: 100%;
        height: 100%;
        margin: 0px;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        background: #f9fafb;
        text-align: center;
        white-space: normal;
        word-break: break-word;
        overflow-wrap: break-word;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        min-height: 40px;
    }

    .wallet-item:active,
    .category-item:active {
        background: #e5e7eb;
    }

    .wallet-item.active,
    .category-item.active {
        border-color: #3b82f6;
    }

    /* Horizontal Chips */
    .chip-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 20px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .chip-btn:hover {
        background: #e5e7eb;
    }

    .chip-btn.selected {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    /* Chips Container */
    .chips-container {
        display: flex;
        gap: 8px;
        overflow-x-auto;
        padding-bottom: 4px;
        scroll-behavior: smooth;
    }

    .chips-container::-webkit-scrollbar {
        height: 4px;
    }

    .chips-container::-webkit-scrollbar-track {
        background: transparent;
    }

    .chips-container::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 2px;
    }

    /* DateTime Display */
    .datetime-display {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .datetime-label {
        font-size: 14px;
        color: #6b7280;
    }

    .datetime-value {
        font-weight: 600;
        color: #111827;
    }

    .datetime-edit-btn {
        padding: 4px 12px;
        font-size: 12px;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .datetime-edit-btn:hover {
        background: #f3f4f6;
    }
</style>

<!-- Modal Header -->
<div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
    <h2 class="text-xl font-bold text-gray-900">Buat Transaksi</h2>
    <button id="closeTransactionModal" class="text-gray-600 hover:text-gray-900 text-2xl">
        <i class="fas fa-times"></i>
    </button>
</div>

<!-- Modal Body -->
<div class="px-6 py-4 space-y-5">
    <form id="transactionForm" class="space-y-3">
        @csrf

        <!-- Date & Time -->
        <div class="space-y-3">
            <!-- DateTime Display -->
            <div class="datetime-display">
                <div>
                    <div class="datetime-label">Tanggal & Waktu</div>
                    <div class="datetime-value">
                        <span id="dateTimeDisplay">-</span>
                    </div>
                </div>
                <button type="button" class="datetime-edit-btn" id="editDateTimeBtn">
                    Ubah waktu
                </button>
            </div>

            <!-- Hidden inputs -->
            <input type="hidden" id="transaction_date" name="transaction_date">
            <input type="hidden" id="transaction_time" name="transaction_time">
        </div>

        <!-- Transaction Type -->
        <div>
            <div class="grid grid-cols-3 gap-2">
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="income" class="hidden">
                    <div
                        class="p-3 border-2 border-gray-200 rounded-lg text-center hover:border-green-500 hover:bg-green-50 transition">
                        <p class="font-semibold text-gray-700">Pemasukan</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="expense" class="hidden" checked>
                    <div
                        class="p-3 border-2 border-gray-200 rounded-lg text-center hover:border-red-500 hover:bg-red-50 transition active">
                        <p class="font-semibold text-gray-700">Pengeluaran</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="transfer" class="hidden">
                    <div
                        class="p-3 border-2 border-gray-200 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition">
                        <p class="font-semibold text-gray-700">Transfer</p>
                    </div>
                </label>
            </div>
            <span class="text-red-500 text-sm type-error hidden mt-1 block"></span>
        </div>

        <!-- Amount -->
        <div>
            <div class="space-y-2">
                <label for="amountDisplay" class="block text-sm font-semibold text-gray-700 whitespace-nowrap">
                    Jumlah
                </label>
                <input type="hidden" id="amount" name="amount">
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                    <input type="text" id="amountDisplay"
                        class="w-full pl-12 pr-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                        placeholder="0" readonly required>
                </div>
            </div>
            <span class="text-red-500 text-sm amount-error hidden mt-1 block"></span>
        </div>

        <!-- Category (Income & Expense) -->
        <div id="categoryField" class="hidden">
            <button type="button" id="categoryDisplay"
                class="w-full h-12 px-4 rounded-xl border border-gray-300 bg-white text-left flex items-center gap-3 font-medium text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-tag text-gray-400"></i>
                <span id="categoryDisplayText">Pilih Kategori</span>
            </button>
            <input type="hidden" id="category_id" name="category_id">
            <span class="text-red-500 text-sm category_id-error hidden mt-1 block"></span>
        </div>

        <!-- Wallet Selection (Income & Expense) -->
        <div id="walletField" class="hidden">
            <button type="button" id="walletDisplay"
                class="w-full h-12 px-4 rounded-xl border border-gray-300 bg-white text-left flex items-center gap-3 font-medium text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-wallet text-gray-400"></i>
                <span id="walletDisplayText">Pilih Dompet</span>
            </button>
            <input type="hidden" id="wallet_id" name="wallet_id">
            <span class="text-red-500 text-sm wallet_id-error hidden mt-1 block"></span>
        </div>

        <!-- From Wallet (Transfer) -->
        <div id="fromWalletField" class="hidden">
            <button type="button" id="fromWalletDisplay"
                class="w-full h-12 px-4 rounded-xl border border-gray-300 bg-white text-left flex items-center gap-3 font-medium text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-right text-gray-400"></i>
                <span id="fromWalletDisplayText">Dari</span>
            </button>
            <input type="hidden" id="from_wallet_id" name="from_wallet_id">
            <span class="text-red-500 text-sm from_wallet_id-error hidden mt-1 block"></span>
        </div>

        <!-- To Wallet (Transfer) -->
        <div id="toWalletField" class="hidden">
            <button type="button" id="toWalletDisplay"
                class="w-full h-12 px-4 rounded-xl border border-gray-300 bg-white text-left flex items-center gap-3 font-medium text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-right text-gray-400"></i>
                <span id="toWalletDisplayText">Ke</span>
            </button>
            <input type="hidden" id="to_wallet_id" name="to_wallet_id">
            <span class="text-red-500 text-sm to_wallet_id-error hidden mt-1 block"></span>
        </div>

        <!-- Note -->
        <div>
            <div class="space-y-2">
                <label for="note" class="block text-sm font-semibold text-gray-700 whitespace-nowrap">
                    Catatan
                </label>
                <textarea id="note" name="note"
                    class="flex-1 px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 resize-none"
                    rows="3" placeholder="Tambahkan catatan (opsional)"></textarea>
            </div>
            <span class="text-red-500 text-sm note-error hidden mt-1 block"></span>
        </div>

        <!-- Submit & Lanjut Button dalam 1 baris -->
        <div class="space-y-3 mt-6">
            <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-xl" id="submitBtn">
                <i class="fas fa-save"></i>
                <span>Simpan Transaksi</span>
            </button>
            <button type="button" class="w-full bg-gray-100 text-gray-700 font-medium py-3 rounded-xl"
                id="continueBtn">
                <i class="fas fa-arrow-right"></i>
                <span>Lanjut</span>
            </button>
        </div>
    </form>
</div>

<!-- Backdrop -->
<div id="dateTimePickerBackdrop"
    class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm z-40">
</div>

<!-- DateTime Picker Bottom Sheet -->
<div id="dateTimePickerSheet"
    class="hidden fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl z-50 max-h-[50vh] overflow-y-auto">
    <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex items-center justify-between rounded-t-3xl">
        <h3 class="font-semibold text-gray-900">Ubah Waktu</h3>
        <button type="button" class="text-gray-600 hover:text-gray-900" id="closeDateTimeSheet">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="p-4 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
            <input type="date" id="dateTimePickerDate"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Jam</label>
            <input type="time" id="dateTimePickerTime"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="button" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium"
            id="confirmDateTimeBtn">
            Simpan
        </button>
    </div>
</div>

<!-- Custom Numeric Keyboard -->
<div class="numeric-keyboard-floating hidden fixed bottom-15 left-0 right-0 bg-white border-t-2 border-gray-200 p-4 z-50 h-[30vh] overflow-y-auto"
    id="numericKeyboard">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-700">Jumlah</h3>
        <button class="text-gray-500 hover:text-gray-700" id="closeKeyboard">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="grid grid-cols-4 gap-1 justify-items-center">
        <button class="key-btn">1</button>
        <button class="key-btn">2</button>
        <button class="key-btn">3</button>
        <button class="key-btn" id="backspace"><i class="fas fa-backspace"></i></button>
        <button class="key-btn">4</button>
        <button class="key-btn">5</button>
        <button class="key-btn">6</button>
        <button class="key-btn">-</button>
        <button class="key-btn">7</button>
        <button class="key-btn">8</button>
        <button class="key-btn">9</button>
        <button class="key-btn">.</button>
        <button class="key-btn"></button>
        <button class="key-btn">0</button>
        <button class="key-btn"></button>
        <button class="key-btn" id="done">DONE</button>
    </div>
</div>

<!-- Category Manager State -->
<div id="categoryManagerState" class="hidden fixed inset-0 bg-gray-50 z-[999] flex flex-col">

    <!-- Header -->
    <div class="bg-white shadow sticky top-0 z-10">

        <div class="flex items-center justify-between px-4 py-4">

            <div class="flex items-center gap-4">

                <!-- Back -->
                <button id="closeCategoryManager" class="text-gray-600 hover:text-gray-900 transition text-xl">

                    <i class="fas fa-arrow-left"></i>

                </button>

                <div>
                    <h2 id="categoryManagerTitle" class="text-xl font-bold text-gray-900">
                        Kelola Kategori
                    </h2>

                    <p id="categoryManagerSubtitle" class="text-sm text-gray-500">
                        Pengeluaran
                    </p>
                </div>

            </div>

            <!-- Add -->
            <button id="btnAddCategory"
                class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow">

                <i class="fas fa-plus"></i>

            </button>

        </div>

    </div>

    <!-- Content -->
    <div id="categoryManagerContent" class="flex-1 overflow-y-auto p-4 space-y-3">

    </div>

</div>

<div id="formCategoryModal" class="hidden fixed inset-0 bg-black/50 z-[1000] overflow-y-auto">

    <div class="flex items-end justify-center min-h-screen">

        <div id="formCategoryContainer"
            class="bg-white w-full max-w-2xl rounded-t-3xl shadow-2xl max-h-[90vh] overflow-y-auto">

        </div>

    </div>

</div>

<!-- Wallet Manager State -->
<div id="walletManagerState" class="hidden fixed inset-0 bg-gray-50 z-[999] flex flex-col">

    <!-- Header -->
    <div class="bg-white shadow sticky top-0 z-10">

        <div class="flex items-center justify-between px-4 py-4">

            <div class="flex items-center gap-4">

                <!-- Back -->
                <button id="closeWalletManager" class="text-gray-600 hover:text-gray-900 transition text-xl">

                    <i class="fas fa-arrow-left"></i>

                </button>

                <div>
                    <h2 id="walletManagerTitle" class="text-xl font-bold text-gray-900">
                        Kelola Dompet
                    </h2>

                    <p id="walletManagerSubtitle" class="text-sm text-gray-500">
                        Pengeluaran
                    </p>
                </div>

            </div>

            <!-- Add -->
            <button id="btnAddWallet"
                class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow">

                <i class="fas fa-plus"></i>

            </button>

        </div>

    </div>

    <!-- Content -->
    <div id="walletManagerContent" class="flex-1 overflow-y-auto p-4 space-y-3">

    </div>

</div>

<div id="formWalletModal" class="hidden fixed inset-0 bg-black/50 z-[1000] overflow-y-auto">

    <div class="flex items-end justify-center min-h-screen">

        <div id="formWalletContainer"
            class="bg-white w-full max-w-2xl rounded-t-3xl shadow-2xl max-h-[90vh] overflow-y-auto">

        </div>

    </div>

</div>


<script>
    const token = localStorage.getItem('api_token');

    $(document).ready(async function() {
        if (!token) {
            window.location.href = '/login';
            return;
        }

        // Initialize DOM cache first
        TransactionDOM.init();

        // Load wallets and categories from cache (will be ready from dashboard init)
        // If cache manager not available yet, wait for it
        if (!window.appData) {
            console.warn('Master data not ready yet, initializing...');
            await CacheManager.initMasterData();
        }

        // Initialize the application
        initializeApp();
        
        // Bind all event handlers
        TransactionEvents.init();
    });

    /**
     * Application initialization
     * Sets up default values and UI state
     */
    async function initializeApp() {
        // Autofocus to amount
        TransactionDOM.amountDisplay.focus();
        TransactionDOM.numericKeyboard.removeClass('hidden');

        // Set today's date and current time
        const now = new Date();
        TransactionDOM.transactionDate.val(now.toISOString().split('T')[0]);
        TransactionDOM.transactionTime.val(now.toTimeString().split(' ')[0].substring(0, 5));

        // Initialize datetime display
        TransactionEvents.updateDateTimeDisplay();

        // Default to expense
        TransactionDOM.typeRadios.filter('[value="expense"]').prop('checked', true);
        TransactionDOM.walletField.removeClass('hidden');
        TransactionDOM.categoryField.removeClass('hidden');
    }

    // All event handlers are now in transaction-events.js
    // Old code removed for better maintainability and separation of concerns
</script>

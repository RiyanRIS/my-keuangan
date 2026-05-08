<section id="trans-section" class="bg-white shadow overflow-hidden mb-6 border border-gray-100">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-5 text-white">

        <!-- Tabs -->
        <div class="flex gap-6 text-sm font-medium overflow-x-auto">

            <button class="tab-button border-b-2 border-white pb-2 whitespace-nowrap" data-group="day">
                Harian
            </button>

            <button class="tab-button text-blue-100 hover:text-white pb-2 whitespace-nowrap" data-group="week">
                Mingguan
            </button>

            <button class="tab-button text-blue-100 hover:text-white pb-2 whitespace-nowrap" data-group="month">
                Bulanan
            </button>

        </div>

    </div>

    <!-- Summary -->
    <div class="grid grid-cols-3 divide-x divide-gray-100 bg-gray-50">

        <div class="p-4">
            <p class="text-gray-500 text-xs mb-1 font-medium">
                Income
            </p>

            <h3 class="text-blue-600 font-bold text-sm" id="summary-income">
                Rp 0
            </h3>
        </div>

        <div class="p-4">
            <p class="text-gray-500 text-xs mb-1 font-medium">
                Expenses
            </p>

            <h3 class="text-red-500 font-bold text-sm" id="summary-expense">
                Rp 0
            </h3>
        </div>

        <div class="p-4">
            <p class="text-gray-500 text-xs mb-1 font-medium">
                Balance
            </p>

            <h3 class="text-gray-900 font-bold text-sm" id="summary-balance">
                Rp 0
            </h3>
        </div>

    </div>

    <!-- Transaction List -->
    <div id="transaction-list" class="divide-y divide-gray-100">

        <!-- Loading State -->
        <div id="loading-state" class="p-8 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-gray-500 text-sm mt-2">Memuat data...</p>
        </div>

    </div>

</section>

<script>
    $(document).ready(function() {
        const token = localStorage.getItem('api_token');
        const API_URL = '{{ url('/api/report/transactions') }}';
        const colors = {
            categoryBg: {
                'income': 'bg-green-50',
                'expense': 'bg-red-50',
                'transfer': 'bg-purple-50',
                'adjustment': 'bg-gray-50'
            },
            categoryText: {
                'income': 'text-green-700',
                'expense': 'text-red-700',
                'transfer': 'text-purple-700',
                'adjustment': 'text-gray-700'
            },
            amount: {
                'income': 'text-green-600',
                'expense': 'text-red-500',
                'transfer': 'text-purple-600',
                'adjustment': 'text-gray-600'
            }
        };

        // Format currency ke Rupiah
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(amount));
        }

        // Get color for category type
        function getCategoryColor(type) {
            return {
                bg: colors.categoryBg[type] || colors.categoryBg.adjustment,
                text: colors.categoryText[type] || colors.categoryText.adjustment
            };
        }

        // Get color for amount
        function getAmountColor(type) {
            return colors.amount[type] || colors.amount.adjustment;
        }

        // Render transaction list
        function renderTransactions(data) {
            let html = '';

            if (!data || data.length === 0) {
                return '<div class="p-8 text-center text-gray-500">Tidak ada data transaksi</div>';
            }

            data.forEach(group => {
                let headerInfo = '';

                if (group.date) {
                    // Day grouping
                    headerInfo = `
                                    <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-xl px-3 py-2 text-center shadow">
                                        <div class="text-xl font-bold leading-none">${group.day}</div>
                                        <div class="text-xs">${group.weekday}</div>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900">${group.month_year}</p>
                                        <p class="text-gray-500 text-xs">${group.total_transaction} transaksi</p>
                                    </div>
                                `;
                } else if (group.week) {
                    // Week grouping
                    headerInfo = `
                                    <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-xl px-3 py-2 text-center shadow">
                                        <div class="text-xs font-bold leading-tight">Minggu</div>
                                        <div class="text-sm font-semibold">ke-${group.week}</div>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900">${group.month_year}</p>
                                        <p class="text-gray-500 text-xs">${group.total_transaction} transaksi</p>
                                    </div>
                                `;
                } else if (group.month) {
                    // Month grouping
                    headerInfo = `
                                    <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-xl px-3 py-2 text-center shadow">
                                        <div class="text-xs font-bold leading-tight">Bulan</div>
                                        <div class="text-sm font-semibold">${group.month}</div>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-900">${group.month_year}</p>
                                        <p class="text-gray-500 text-xs">${group.total_transaction} transaksi</p>
                                    </div>
                                `;
                }

                html += `
                                <div class="p-3">
                                    <div class="flex items-center justify-between mb-5">
                                        <div class="flex items-center gap-3">
                                            ${headerInfo}
                                        </div>
                                        <div class="text-right">
                                            <p class="text-green-600 font-bold text-sm">${formatCurrency(group.total_income)}</p>
                                            <p class="text-red-500 font-bold text-sm">${formatCurrency(group.total_expense)}</p>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                            `;

                // Render items
                if (group.items && group.items.length > 0) {
                    group.items.forEach(item => {
                        const categoryColor = getCategoryColor(item.type);
                        const amountColor = getAmountColor(item.type);

                        html += `
                          <div class="bg-gray-50 p-2 flex items-center justify-between hover:bg-gray-100 transition border border-gray-100">
                              <div class="flex items-center gap-3 flex-1 min-w-0">
                                  <div class="w-20 truncate text-xs font-medium text-blue-700 bg-blue-50 rounded-lg px-2 py-1 text-center">
                                      ${item.category}
                                  </div>
                                  <div class="flex-1 min-w-0">
                                      ${item.note ? `
                                          <h4 class="text-sm font-semibold text-gray-900 truncate">
                                              ${item.note}
                                          </h4>
                                      ` : ''}
                                      <p class="text-xs text-gray-500 truncate">${item.wallet}</p>
                                  </div>
                              </div>
                              <div class="shrink-0 ${amountColor} font-bold text-sm ml-3 whitespace-nowrap">
                                  ${formatCurrency(item.amount)}
                              </div>
                          </div>
                      `;
                    });
                }

                html += `
                        </div>
                    </div>
                `;
            });

            return html;
        }

        // Load report data
        function loadReport(groupBy) {
            $('#loading-state').show();
            $('#transaction-list').html(
                '<div id="loading-state" class="p-8 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="text-gray-500 text-sm mt-2">Memuat data...</p></div>'
            );

            const params = {
                group: groupBy
            };

            $.ajax({
                url: API_URL,
                type: 'GET',
                data: params,
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                success: function(response) {
                    if (response.success && response.data) {
                        const data = response.data;

                        // Update summary
                        $('#summary-income').text(formatCurrency(data.summary.income));
                        $('#summary-expense').text(formatCurrency(data.summary.expense));
                        $('#summary-balance').text(formatCurrency(data.summary.balance));

                        // Render transactions
                        const html = renderTransactions(data.data);
                        $('#transaction-list').html(html);
                    } else {
                        $('#transaction-list').html(
                            '<div class="p-8 text-center text-gray-500">Terjadi kesalahan mengambil data</div>'
                        );
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    $('#transaction-list').html(
                        '<div class="p-8 text-center text-red-500">Gagal memuat data. Silahkan coba lagi.</div>'
                    );
                }
            });
        }

        // Tab button click handler
        $(document).on('click', '.tab-button', function(e) {
            e.preventDefault();

            const groupBy = $(this).data('group');

            // Update active tab
            $('.tab-button').removeClass('border-b-2 border-white text-white').addClass(
                'text-blue-100');
            $(this).removeClass('text-blue-100').addClass('border-b-2 border-white text-white');

            // Load report
            loadReport(groupBy);
        });

        // Load initial data
        loadReport('day');
    });
</script>

<section id="stats-section" class="bg-white shadow overflow-hidden mb-6 border border-gray-100">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-5 text-white">

        <!-- Tabs -->
        <div class="flex gap-6 text-sm font-medium">
            <button class="type-tab border-b-2 border-white pb-2 whitespace-nowrap" data-type="income">
                Pemasukan
            </button>
            <button class="type-tab text-blue-100 hover:text-white pb-2 whitespace-nowrap" data-type="expense">
                Pengeluaran
            </button>
        </div>

    </div>

    <!-- Period Filter -->
    <div class="grid grid-cols-3 divide-x divide-gray-100 bg-gray-50">

        <div class="p-4">
            <button class="period-button px-3 py-1 rounded bg-white text-blue-600 font-medium text-xs"
                data-period="week">
                Mingguan
            </button>
        </div>

        <div class="p-4">
            <button class="period-button px-3 py-1 rounded bg-blue-500 text-white font-medium text-xs hover:bg-blue-400"
                data-period="month">
                Bulanan
            </button>
        </div>

        <div class="p-4">
            <button class="period-button px-3 py-1 rounded bg-blue-500 text-white font-medium text-xs hover:bg-blue-400"
                data-period="year">
                Tahunan
            </button>
        </div>

    </div>

    <!-- Content -->
    <div class="p-6">

        <!-- Loading State -->
        <div id="loading-state" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-gray-500 text-sm mt-3">Memuat grafik...</p>
        </div>

        <!-- Chart Container -->
        <div id="chart-container" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Chart -->
                <div class="flex items-center justify-center">
                    <div style="position: relative; width: 300px; height: 300px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- Category List -->
                <div>
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2" id="chart-title">Pengeluaran</h3>
                        <p class="text-sm text-gray-500" id="period-label">Minggu ini</p>
                    </div>

                    <!-- Summary -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-gray-600 text-xs font-medium mb-1">Total</p>
                        <h3 class="text-2xl font-bold text-gray-900" id="total-amount">Rp 0</h3>
                        <p class="text-gray-500 text-xs mt-1"><span id="transaction-count">0</span> transaksi</p>
                    </div>

                    <!-- Categories -->
                    <div class="space-y-3" id="categories-list">
                        <!-- Categories will be rendered here -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-500 text-sm mt-4">Tidak ada data untuk periode ini</p>
        </div>

    </div>

</section>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    $(document).ready(function() {
        const token = localStorage.getItem('api_token');
        const API_URL = '{{ url('/api/report/category-breakdown') }}';
        let chartInstance = null;

        // Format currency ke Rupiah
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(Math.round(amount));
        }

        // Get period label
        function getPeriodLabel(period) {

            const now = new Date();

            if (period === 'week') {

                const current = new Date();

                const startOfWeek = new Date(current);
                startOfWeek.setDate(current.getDate() - current.getDay());

                const endOfWeek = new Date(current);
                endOfWeek.setDate(current.getDate() - current.getDay() + 6);

                return startOfWeek.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short'
                    }) + ' - ' +
                    endOfWeek.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });

            } else if (period === 'month') {

                return now.toLocaleDateString('id-ID', {
                    month: 'long',
                    year: 'numeric'
                });

            } else if (period === 'year') {

                return now.getFullYear().toString();
            }

            return '';
        }

        // Get chart title
        function getChartTitle(type) {
            return type === 'income' ? 'Pemasukan' : 'Pengeluaran';
        }

        // Generate colors for chart
        function generateColors(count) {
            const colors = [
                '#3B82F6', '#EF4444', '#8B5CF6', '#10B981', '#F59E0B',
                '#EC4899', '#6366F1', '#14B8A6', '#F97316', '#06B6D4',
                '#84CC16', '#D946EF', '#0EA5E9', '#2DD4BF', '#FBBF24'
            ];
            return colors.slice(0, count);
        }

        // Render categories list
        function renderCategoriesList(categories) {
            let html = '';

            if (!categories || categories.length === 0) {
                return '<p class="text-gray-500 text-sm text-center py-4">Tidak ada kategori</p>';
            }

            categories.forEach((cat, index) => {
                const colors = generateColors(categories.length);
                const bgColor = colors[index];

                html += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: ${bgColor}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${cat.category}</p>
                                <p class="text-xs text-gray-500">${cat.percentage.toFixed(2)}% • ${cat.transaction_count} transaksi</p>
                            </div>
                        </div>
                        <div class="shrink-0 ml-2">
                            <p class="text-sm font-bold text-gray-900">${formatCurrency(cat.amount)}</p>
                        </div>
                    </div>
                `;
            });

            return html;
        }

        // Render chart
        function renderChart(data) {
            const ctx = document.getElementById('categoryChart');

            if (!ctx) return;

            const categories = data.categories || [];
            const colors = generateColors(categories.length);
            const labels = categories.map(cat => cat.category);
            const amounts = categories.map(cat => cat.amount);

            // Destroy previous chart if exists
            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: amounts,
                        backgroundColor: colors,
                        borderColor: '#fff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatCurrency(context.parsed);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Load report data
        function loadReport(type, period) {
            $('#loading-state').show();
            $('#chart-container').addClass('hidden');
            $('#empty-state').addClass('hidden');

            const params = {
                type: type,
                period: period
            };

            $.ajax({
                url: API_URL,
                type: 'GET',
                data: params,
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                dataType: 'json',
                success: function(response) {
                    $('#loading-state').hide();

                    if (response.success && response.data) {
                        const data = response.data;

                        if (!data.categories || data.categories.length === 0) {
                            $('#empty-state').removeClass('hidden');
                        } else {
                            $('#chart-container').removeClass('hidden');

                            // Update title and period
                            $('#chart-title').text(getChartTitle(type));
                            $('#period-label').text(getPeriodLabel(period));

                            // Update summary
                            $('#total-amount').text(formatCurrency(data.summary.total));
                            $('#transaction-count').text(data.summary.transaction_count);

                            // Render chart and categories
                            renderChart(data);
                            $('#categories-list').html(renderCategoriesList(data.categories));
                        }
                    } else {
                        $('#empty-state').removeClass('hidden');
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    $('#loading-state').hide();
                    $('#empty-state').removeClass('hidden');
                }
            });
        }

        // Type tab click handler
        $(document).on('click', '.type-tab', function(e) {
            e.preventDefault();

            const type = $(this).data('type');

            // Update active tab
            $('.type-tab').removeClass('border-b-2 border-white text-white').addClass('text-blue-100');
            $(this).removeClass('text-blue-100').addClass('border-b-2 border-white text-white');

            // Get current period
            const period = $('.period-button.active').data('period');

            // Load report
            loadReport(type, period);
        });

        // Period button click handler
        $(document).on('click', '.period-button', function(e) {
            e.preventDefault();

            const period = $(this).data('period');

            // Update active button
            $('.period-button').removeClass('bg-white text-blue-600').addClass(
                'bg-blue-500 text-white');
            $(this).removeClass('bg-blue-500 text-white').addClass('bg-white text-blue-600');

            // Get current type
            const type = $('.type-tab.active').data('type') || 'income';

            // Load report
            loadReport(type, period);
        });

        // Load initial data
        loadReport('income', 'week');
    });
</script>

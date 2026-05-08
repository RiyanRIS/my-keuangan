<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup - Keuangan App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            padding-bottom: 0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex items-center space-x-4">
                <button id="backBtn" class="text-gray-600 hover:text-gray-900 text-xl">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-900">Backup & Restore</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-2xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Backup Information -->
            <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded mb-6">
                <h3 class="font-semibold text-blue-900 flex items-center space-x-2 mb-2">
                    <i class="fas fa-info-circle"></i>
                    <span>Informasi Backup</span>
                </h3>
                <p class="text-sm text-blue-800">Backup akan menyimpan semua data transaksi, dompet, dan kategori Anda dalam format JSON. Anda dapat mengunduh atau memulihkan backup kapan saja.</p>
            </div>

            <!-- Last Backup -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-clock text-blue-600"></i>
                        <span>Backup Terakhir</span>
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-2">
                        <p class="text-gray-700"><strong>Status:</strong> <span class="text-green-600">Belum pernah backup</span></p>
                        <p class="text-gray-700"><strong>Ukuran Data:</strong> Calculating...</p>
                        <p class="text-gray-700" id="lastBackupDate"><strong>Terakhir:</strong> -</p>
                    </div>
                </div>
            </div>

            <!-- Backup Actions -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-download text-green-600"></i>
                        <span>Buat Backup</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <p class="text-gray-600 text-sm">Unduh backup data Anda sekarang dalam format JSON</p>
                    <button id="downloadBackup" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-3 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-download"></i>
                        <span>Unduh Backup Sekarang</span>
                    </button>
                </div>
            </div>

            <!-- Restore from Backup -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-upload text-purple-600"></i>
                        <span>Pulihkan dari Backup</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <p class="text-gray-600 text-sm">Upload file backup (JSON) untuk memulihkan data Anda</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer" id="dropZone">
                        <input type="file" id="backupFile" class="hidden" accept=".json">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="font-semibold text-gray-700">Klik atau drag file backup di sini</p>
                        <p class="text-xs text-gray-600 mt-1">File harus berupa JSON dari backup Keuangan App</p>
                    </div>
                    <button id="restoreBackup" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 rounded-lg transition flex items-center justify-center space-x-2" disabled>
                        <i class="fas fa-sync"></i>
                        <span>Pulihkan Backup</span>
                    </button>
                </div>
            </div>

            <!-- Auto Backup Schedule -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-calendar-check text-orange-600"></i>
                        <span>Backup Otomatis</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">Aktifkan Backup Harian</p>
                            <p class="text-sm text-gray-600">Backup data otomatis setiap hari pukul 01:00 WIB</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="autoBackup" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            const token = localStorage.getItem('api_token');

            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Back button handler
            $('#backBtn').on('click', function() {
                history.replaceState(null, '', '/dashboard');
                window.location.href = '/dashboard';
            });

            let selectedFile = null;

            // Drop zone handling
            $('#dropZone').on('click', function() {
                $('#backupFile').click();
            });

            $('#backupFile').on('change', function(e) {
                selectedFile = e.target.files[0];
                if (selectedFile) {
                    $('#dropZone').css('border-color', '#3b82f6').css('background-color', '#eff6ff');
                    $('#dropZone').find('p:first').text(selectedFile.name);
                    $('#restoreBackup').prop('disabled', false);
                }
            });

            // Drag and drop
            $('#dropZone').on('dragover', function(e) {
                e.preventDefault();
                $(this).css('border-color', '#3b82f6').css('background-color', '#eff6ff');
            });

            $('#dropZone').on('dragleave', function() {
                $(this).css('border-color', '#d1d5db').css('background-color', 'white');
            });

            $('#dropZone').on('drop', function(e) {
                e.preventDefault();
                selectedFile = e.originalEvent.dataTransfer.files[0];
                if (selectedFile && selectedFile.type === 'application/json') {
                    $('#backupFile')[0].files = e.originalEvent.dataTransfer.files;
                    $('#dropZone').css('border-color', '#3b82f6').css('background-color', '#eff6ff');
                    $('#dropZone').find('p:first').text(selectedFile.name);
                    $('#restoreBackup').prop('disabled', false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File',
                        text: 'Mohon upload file JSON yang valid'
                    });
                }
            });

            // Download backup
            $('#downloadBackup').on('click', function() {
                $.ajax({
                    url: '/api/wallets',
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(walletsResponse) {
                        $.ajax({
                            url: '/api/categories',
                            method: 'GET',
                            headers: {
                                'Authorization': 'Bearer ' + token
                            },
                            success: function(categoriesResponse) {
                                $.ajax({
                                    url: '/api/transactions',
                                    method: 'GET',
                                    headers: {
                                        'Authorization': 'Bearer ' + token
                                    },
                                    success: function(transactionsResponse) {
                                        const backupData = {
                                            version: '1.0.0',
                                            timestamp: new Date().toISOString(),
                                            wallets: walletsResponse.data || [],
                                            categories: categoriesResponse.data || [],
                                            transactions: transactionsResponse.data || []
                                        };

                                        const dataStr = JSON.stringify(backupData, null, 2);
                                        const dataBlob = new Blob([dataStr], { type: 'application/json' });
                                        const url = URL.createObjectURL(dataBlob);
                                        const link = document.createElement('a');
                                        link.href = url;
                                        link.download = `keuangan-backup-${new Date().getTime()}.json`;
                                        document.body.appendChild(link);
                                        link.click();
                                        document.body.removeChild(link);
                                        URL.revokeObjectURL(url);

                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Backup Berhasil',
                                            text: 'Data backup telah diunduh',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                    }
                                });
                            }
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal membuat backup'
                        });
                    }
                });
            });

            // Restore backup
            $('#restoreBackup').on('click', function() {
                if (!selectedFile) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No File',
                        text: 'Pilih file backup terlebih dahulu'
                    });
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const backupData = JSON.parse(e.target.result);
                        
                        Swal.fire({
                            title: 'Restore Backup?',
                            text: 'Data saat ini akan diganti dengan data backup. Tindakan ini tidak dapat dibatalkan.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Restore',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Coming Soon',
                                    text: 'Fitur restore backup akan segera tersedia'
                                });
                            }
                        });
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Backup',
                            text: 'File backup tidak valid atau rusak'
                        });
                    }
                };
                reader.readAsText(selectedFile);
            });

            // Auto backup toggle
            const autoBackupSetting = JSON.parse(localStorage.getItem('app_settings') || '{}').autoBackup || false;
            $('#autoBackup').prop('checked', autoBackupSetting);

            $('#autoBackup').on('change', function() {
                const settings = JSON.parse(localStorage.getItem('app_settings') || '{}');
                settings.autoBackup = $(this).is(':checked');
                localStorage.setItem('app_settings', JSON.stringify(settings));
                
                Swal.fire({
                    icon: 'success',
                    title: 'Pengaturan Disimpan',
                    timer: 1000,
                    showConfirmButton: false
                });
            });
        });
    </script>
</body>
</html>

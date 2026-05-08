<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan - Keuangan App</title>
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
                <h1 class="text-2xl font-bold text-gray-900">Pusat Bantuan</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-2xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Search Box -->
            <div class="mb-6">
                <div class="relative">
                    <input
                        type="text"
                        id="searchHelp"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                        placeholder="Cari artikel bantuan..."
                    >
                    <i class="fas fa-search absolute right-4 top-3.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white p-6 mb-6">
                <h3 class="text-lg font-bold mb-2">Butuh Bantuan Langsung?</h3>
                <p class="text-blue-100 mb-4">Tim support kami siap membantu Anda 24/7</p>
                <div class="space-y-2">
                    <a href="mailto:support@keuangan.app" class="block bg-white text-blue-600 font-semibold py-2 px-4 rounded text-center hover:bg-blue-50 transition">
                        <i class="fas fa-envelope mr-2"></i>Email Support
                    </a>
                </div>
            </div>

            <!-- FAQ Categories -->
            <div class="space-y-4 mb-6">
                <!-- Getting Started -->
                <details class="bg-white rounded-lg shadow overflow-hidden group cursor-pointer">
                    <summary class="px-6 py-4 border-b border-gray-100 flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fas fa-rocket text-green-600"></i>
                            <span>Memulai</span>
                        </span>
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <div class="px-6 py-4 space-y-3 text-sm">
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara membuat akun?</h4>
                            <p class="text-gray-600">Klik tombol "Daftar" dan isi form dengan nama, email, dan password Anda. Setelah verifikasi, akun siap digunakan.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara login?</h4>
                            <p class="text-gray-600">Masukkan email dan password yang terdaftar di halaman login. Gunakan "Remember Me" untuk login otomatis di perangkat.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Apakah data saya aman?</h4>
                            <p class="text-gray-600">Ya, kami menggunakan enkripsi tingkat bank untuk melindungi data Anda. Semua komunikasi dienkripsi dengan SSL.</p>
                        </div>
                    </div>
                </details>

                <!-- Wallets & Transactions -->
                <details class="bg-white rounded-lg shadow overflow-hidden group cursor-pointer">
                    <summary class="px-6 py-4 border-b border-gray-100 flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fas fa-wallet text-blue-600"></i>
                            <span>Dompet & Transaksi</span>
                        </span>
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <div class="px-6 py-4 space-y-3 text-sm">
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara membuat dompet?</h4>
                            <p class="text-gray-600">Di dashboard, buka menu "Accounts", klik "+ Dompet Baru", pilih tipe dompet, dan berikan nama. Dompet siap digunakan.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara mencatat transaksi?</h4>
                            <p class="text-gray-600">Klik tombol "+" di dashboard atau menu Transaksi. Pilih tipe (Pemasukan/Pengeluaran/Transfer), isi dompet, kategori, dan jumlah.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bisakah saya mengedit transaksi?</h4>
                            <p class="text-gray-600">Ya, klik transaksi yang ingin diedit, ubah detail sesuai kebutuhan, dan simpan perubahan.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara menghapus dompet?</h4>
                            <p class="text-gray-600">Buka pengaturan dompet, klik "Hapus". Perhatian: Hanya dompet kosong yang bisa dihapus.</p>
                        </div>
                    </div>
                </details>

                <!-- Categories & Organization -->
                <details class="bg-white rounded-lg shadow overflow-hidden group cursor-pointer">
                    <summary class="px-6 py-4 border-b border-gray-100 flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fas fa-tags text-purple-600"></i>
                            <span>Kategori & Organisasi</span>
                        </span>
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <div class="px-6 py-4 space-y-3 text-sm">
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Apakah saya bisa membuat kategori custom?</h4>
                            <p class="text-gray-600">Ya, di menu "Accounts", buka "Kategori", klik "+ Baru", beri nama, pilih tipe, dan simpan.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Apa perbedaan kategori Pemasukan dan Pengeluaran?</h4>
                            <p class="text-gray-600">Kategori Pemasukan untuk uang yang masuk (gaji, bonus), Pengeluaran untuk uang yang keluar (makan, transportasi).</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara mengorganisir transaksi?</h4>
                            <p class="text-gray-600">Gunakan kategori dan catatan untuk mengorganisir transaksi. Gunakan filter di menu Transaksi untuk mencari.</p>
                        </div>
                    </div>
                </details>

                <!-- Statistics & Reports -->
                <details class="bg-white rounded-lg shadow overflow-hidden group cursor-pointer">
                    <summary class="px-6 py-4 border-b border-gray-100 flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fas fa-chart-pie text-orange-600"></i>
                            <span>Statistik & Laporan</span>
                        </span>
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <div class="px-6 py-4 space-y-3 text-sm">
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara melihat laporan bulanan?</h4>
                            <p class="text-gray-600">Di menu "Stats", pilih bulan yang ingin dilihat. Sistem akan menampilkan ringkasan pemasukan dan pengeluaran.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bisakah saya membandingkan data antar bulan?</h4>
                            <p class="text-gray-600">Ya, gunakan fitur "Bandingkan Bulan" di halaman statistik untuk melihat perbandingan.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara export laporan?</h4>
                            <p class="text-gray-600">Di halaman laporan, klik "Export PDF" atau "Export Excel" untuk mengunduh laporan.</p>
                        </div>
                    </div>
                </details>

                <!-- Account & Security -->
                <details class="bg-white rounded-lg shadow overflow-hidden group cursor-pointer">
                    <summary class="px-6 py-4 border-b border-gray-100 flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fas fa-lock text-red-600"></i>
                            <span>Akun & Keamanan</span>
                        </span>
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <div class="px-6 py-4 space-y-3 text-sm">
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara mengubah password?</h4>
                            <p class="text-gray-600">Buka menu "Keamanan", klik "Ubah Password", masukkan password lama dan baru, kemudian simpan.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Saya lupa password saya</h4>
                            <p class="text-gray-600">Klik "Lupa Password" di halaman login, masukkan email, ikuti instruksi reset yang dikirim ke email Anda.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara mengubah email?</h4>
                            <p class="text-gray-600">Buka menu "Akun", ubah email di field Email, dan simpan. Email baru akan diverifikasi.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Apa itu Autentikasi Dua Faktor?</h4>
                            <p class="text-gray-600">2FA memberikan lapisan keamanan tambahan dengan meminta kode verifikasi saat login dari perangkat baru.</p>
                        </div>
                    </div>
                </details>

                <!-- Data & Backup -->
                <details class="bg-white rounded-lg shadow overflow-hidden group cursor-pointer">
                    <summary class="px-6 py-4 border-b border-gray-100 flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fas fa-database text-green-600"></i>
                            <span>Data & Backup</span>
                        </span>
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <div class="px-6 py-4 space-y-3 text-sm">
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara backup data?</h4>
                            <p class="text-gray-600">Buka menu "Backup", klik "Unduh Backup Sekarang" untuk mengunduh file backup data Anda.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara restore backup?</h4>
                            <p class="text-gray-600">Di menu "Backup", upload file backup JSON yang sudah diunduh sebelumnya, kemudian klik restore.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Bisakah saya membuat backup otomatis?</h4>
                            <p class="text-gray-600">Ya, di menu "Backup", aktifkan "Backup Otomatis Harian" untuk backup setiap hari otomatis.</p>
                        </div>
                    </div>
                </details>

                <!-- Troubleshooting -->
                <details class="bg-white rounded-lg shadow overflow-hidden group cursor-pointer">
                    <summary class="px-6 py-4 border-b border-gray-100 flex items-center justify-between font-semibold text-gray-900 hover:bg-gray-50 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fas fa-tools text-yellow-600"></i>
                            <span>Troubleshooting</span>
                        </span>
                        <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <div class="px-6 py-4 space-y-3 text-sm">
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Aplikasi lambat/lag</h4>
                            <p class="text-gray-600">Coba refresh halaman, bersihkan cache browser, atau gunakan browser yang berbeda. Hubungi support jika masalah berlanjut.</p>
                        </div>
                        <div class="border-b pb-3">
                            <h4 class="font-semibold text-gray-900 mb-2">Data tidak tersimpan</h4>
                            <p class="text-gray-600">Pastikan koneksi internet stabil dan browser tidak mengalami error. Cek kembali form validation.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Tidak bisa login</h4>
                            <p class="text-gray-600">Periksa email dan password, reset password jika lupa, atau hubungi support untuk bantuan lebih lanjut.</p>
                        </div>
                    </div>
                </details>
            </div>

            <!-- Still Need Help -->
            <div class="bg-white rounded-lg shadow p-6 text-center mb-6">
                <i class="fas fa-headset text-4xl text-blue-600 mb-3"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Masih Memerlukan Bantuan?</h3>
                <p class="text-gray-600 mb-4">Hubungi tim support kami yang siap membantu 24/7</p>
                <button id="contactSupport" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Hubungi Support
                </button>
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

            // Contact Support
            $('#contactSupport').on('click', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'Hubungi Support',
                    html: '<p class="mb-3">Email: <strong>support@keuangan.app</strong></p><p>WhatsApp: <strong>+62 812-3456-7890</strong></p>',
                    confirmButtonText: 'OK'
                });
            });

            // Search functionality
            $('#searchHelp').on('keyup', function() {
                const query = $(this).val().toLowerCase();
                
                if (query.length === 0) {
                    $('details').show();
                    return;
                }

                $('details').each(function() {
                    const text = $(this).text().toLowerCase();
                    if (text.includes(query)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
</body>
</html>

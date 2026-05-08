<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Keuangan App</title>
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
                <h1 class="text-2xl font-bold text-gray-900">Feedback & Saran</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-2xl w-full mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header Info -->
            <div class="bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg shadow text-white p-6 mb-6">
                <h2 class="text-xl font-bold mb-2">Kami Mendengarkan Anda!</h2>
                <p class="text-yellow-100">Feedback Anda sangat penting untuk meningkatkan Keuangan App</p>
            </div>

            <!-- Feedback Form -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-edit text-blue-600"></i>
                        <span>Kirim Feedback</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <!-- Feedback Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Feedback</label>
                        <select id="feedbackType" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Pilih tipe feedback...</option>
                            <option value="bug">🐛 Laporan Bug</option>
                            <option value="feature">✨ Saran Fitur</option>
                            <option value="improvement">🚀 Saran Perbaikan</option>
                            <option value="ui">🎨 Feedback UI/UX</option>
                            <option value="other">💬 Lainnya</option>
                        </select>
                        <span class="text-red-500 text-sm feedbackType-error hidden mt-1 block"></span>
                    </div>

                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                        <div class="flex space-x-2">
                            <input type="radio" name="rating" value="1" class="hidden" id="rating1">
                            <label for="rating1" class="cursor-pointer text-3xl transition transform hover:scale-110">
                                <i class="far fa-frown text-gray-400"></i>
                            </label>
                            <input type="radio" name="rating" value="2" class="hidden" id="rating2">
                            <label for="rating2" class="cursor-pointer text-3xl transition transform hover:scale-110">
                                <i class="far fa-frown text-yellow-400"></i>
                            </label>
                            <input type="radio" name="rating" value="3" class="hidden" id="rating3">
                            <label for="rating3" class="cursor-pointer text-3xl transition transform hover:scale-110">
                                <i class="far fa-meh text-yellow-500"></i>
                            </label>
                            <input type="radio" name="rating" value="4" class="hidden" id="rating4">
                            <label for="rating4" class="cursor-pointer text-3xl transition transform hover:scale-110">
                                <i class="far fa-smile text-lime-400"></i>
                            </label>
                            <input type="radio" name="rating" value="5" class="hidden" id="rating5">
                            <label for="rating5" class="cursor-pointer text-3xl transition transform hover:scale-110">
                                <i class="fas fa-grin-stars text-green-500"></i>
                            </label>
                        </div>
                        <span class="text-red-500 text-sm rating-error hidden mt-1 block"></span>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Pesan</label>
                        <textarea
                            id="message"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 resize-none"
                            rows="5"
                            placeholder="Tuliskan feedback Anda di sini... (minimum 10 karakter)"
                        ></textarea>
                        <p class="text-xs text-gray-600 mt-1"><span id="charCount">0</span>/500</p>
                        <span class="text-red-500 text-sm message-error hidden mt-1 block"></span>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email (Opsional)</label>
                        <input
                            type="email"
                            id="email"
                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="Untuk follow-up feedback Anda"
                        >
                        <span class="text-red-500 text-sm email-error hidden mt-1 block"></span>
                    </div>

                    <!-- Submit Button -->
                    <button id="submitFeedback" class="w-full bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white font-bold py-3 rounded-lg transition flex items-center justify-center space-x-2 mt-4">
                        <i class="fas fa-paper-plane"></i>
                        <span>Kirim Feedback</span>
                    </button>
                </div>
            </div>

            <!-- Feedback Guidelines -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-lightbulb text-purple-600"></i>
                        <span>Tips Memberikan Feedback</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-2 text-sm text-gray-700">
                    <p><strong>✓ Jadilah Spesifik:</strong> Jelaskan masalah atau saran dengan detail</p>
                    <p><strong>✓ Berikan Konteks:</strong> Kapan/di mana masalah terjadi?</p>
                    <p><strong>✓ Tetap Konstruktif:</strong> Feedback positif membantu kami berkembang</p>
                    <p><strong>✓ Satu Topik:</strong> Fokus pada satu masalah per feedback</p>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center space-x-2">
                        <i class="fas fa-question-circle text-blue-600"></i>
                        <span>Pertanyaan Umum</span>
                    </h3>
                </div>
                <div class="px-6 py-4 space-y-3 text-sm">
                    <details class="border rounded p-3 cursor-pointer hover:bg-gray-50">
                        <summary class="font-semibold text-gray-900">Berapa lama feedback akan diproses?</summary>
                        <p class="text-gray-600 mt-2">Tim kami biasanya merespons dalam 1-2 hari kerja.</p>
                    </details>
                    <details class="border rounded p-3 cursor-pointer hover:bg-gray-50">
                        <summary class="font-semibold text-gray-900">Apakah feedback saya dijaga kerahasiaannya?</summary>
                        <p class="text-gray-600 mt-2">Ya, kami menjaga privasi dan kerahasiaannya dengan ketat.</p>
                    </details>
                    <details class="border rounded p-3 cursor-pointer hover:bg-gray-50">
                        <summary class="font-semibold text-gray-900">Bagaimana jika saya menemukan bug serius?</summary>
                        <p class="text-gray-600 mt-2">Segera hubungi tim support kami melalui email untuk penanganan prioritas.</p>
                    </details>
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

            // Character counter
            $('#message').on('input', function() {
                const count = $(this).val().length;
                $('#charCount').text(Math.min(count, 500));
            });

            // Rating emoji update
            $('input[name="rating"]').on('change', function() {
                const rating = $(this).val();
                // Just visual feedback, handled by CSS
            });

            // Submit feedback
            $('#submitFeedback').on('click', function() {
                const type = $('#feedbackType').val();
                const rating = $('input[name="rating"]:checked').val();
                const message = $('#message').val();
                const email = $('#email').val();

                // Clear errors
                $('.error').addClass('hidden');

                // Validation
                if (!type) {
                    $('.feedbackType-error').text('Pilih tipe feedback').removeClass('hidden');
                    return;
                }

                if (!rating) {
                    $('.rating-error').text('Berikan rating').removeClass('hidden');
                    return;
                }

                if (!message || message.length < 10) {
                    $('.message-error').text('Pesan minimal 10 karakter').removeClass('hidden');
                    return;
                }

                if (message.length > 500) {
                    $('.message-error').text('Pesan maksimal 500 karakter').removeClass('hidden');
                    return;
                }

                if (email && !isValidEmail(email)) {
                    $('.email-error').text('Email tidak valid').removeClass('hidden');
                    return;
                }

                $(this).prop('disabled', true).css('opacity', '0.5');

                // Save feedback locally (since we don't have a feedback API endpoint yet)
                const feedback = {
                    type: type,
                    rating: rating,
                    message: message,
                    email: email,
                    timestamp: new Date().toISOString(),
                    user_id: JSON.parse(localStorage.getItem('user')).id
                };

                let feedbacks = JSON.parse(localStorage.getItem('feedbacks') || '[]');
                feedbacks.push(feedback);
                localStorage.setItem('feedbacks', JSON.stringify(feedbacks));

                Swal.fire({
                    icon: 'success',
                    title: 'Feedback Terkirim',
                    text: 'Terima kasih atas feedback Anda! Tim kami akan segera meninjaunya.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    $('#feedbackType').val('');
                    $('input[name="rating"]').prop('checked', false);
                    $('#message').val('');
                    $('#email').val('');
                    $('#charCount').text('0');
                    $('#submitFeedback').prop('disabled', false).css('opacity', '1');
                });
            });

            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
</body>
</html>

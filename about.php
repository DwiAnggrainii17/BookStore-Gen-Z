<?php
// Start session
session_start();
// Initialize variables
$isLoggedIn = true;
// Check login status
if (isset($_SESSION['session_username'])) {
    $isLoggedIn = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore Gen Z</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#1e40af',
                        dark: '#1f2937',
                        light: '#f9fafb'
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        .animate-fade {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-bottom: 3px solid transparent;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            border-color: #2563eb;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            position: relative;
        }
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #2563eb;
            transition: width 0.3s ease;
        }
        .nav-link:hover:after {
            width: 100%;
        }
        .avatar-initial {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
        }
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .mobile-menu.open {
            max-height: 500px;
            transition: max-height 0.5s ease-in;
        }
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            font-size: 1.25rem;
        }
        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(to right, #2563eb, #1e40af);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .username-link {
            transition: color 0.2s ease;
            font-weight: 500;
        }
        .username-link:hover {
            color: #2563eb;
        }
        /* WhatsApp Button Animation */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
            70% { box-shadow: 0 0 0 12px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
        }
        .fa-whatsapp {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">
    <!-- Updated Navbar -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <span class="logo-text">BookStore</span>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="nav-link text-gray-700 hover:text-primary">Produk</a>
                    <a href="about.php" class="nav-link text-gray-700 hover:text-primary">About Us</a>
                    <a href="contact.php" class="nav-link text-gray-700 hover:text-primary">Contact</a>
                    
                    <!-- User Actions -->
                    <div class="flex items-center space-x-6 ml-6">
                        <?php if ($isLoggedIn): ?>
                            <a href="keranjang.php" class="flex items-center text-gray-700 hover:text-primary">
                                <i class="fas fa-shopping-cart mr-2"></i> Keranjang
                            </a>
                            <a href="logout.php" class="text-gray-700 hover:text-primary">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        <?php else: ?>
                            <a href="login_user.php" class="text-gray-700 hover:text-primary">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                            </a>
                            <a href="login_user.php" class="flex items-center text-gray-700 hover:text-primary">
                                <i class="fas fa-shopping-cart mr-2"></i> Keranjang
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>

                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="mobile-menu md:hidden">
                <div class="px-2 pt-2 pb-4 space-y-1">
                    <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Produk</a>
                    <a href="about.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">About Us</a>
                    <a href="contact.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">Contact</a>
                    
                    <?php if ($isLoggedIn): ?>
                        <a href="keranjang.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">
                            <i class="fas fa-shopping-cart mr-2"></i> Keranjang
                        </a>
                        <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    <?php else: ?>
                        <a href="login_user.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                        <a href="login_user.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary hover:bg-gray-50">
                            <i class="fas fa-shopping-cart mr-2"></i> Keranjang
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <header class="relative bg-gradient-to-r from-dark to-gray-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black/30 z-10"></div>
        <div class="absolute inset-0 bg-[url('assets/images/hero-bg.jpg')] bg-cover bg-center"></div>
        <div class="relative z-20 container mx-auto px-6 py-32 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight animate-fade">
                Surga Nya Buku <br><span class="text-primary">Para Kaum Gen Z</span>
            </h1>
            <p class="text-lg md:text-xl max-w-3xl mx-auto mb-10 text-gray-300 animate-fade">
                Udah tahu yang lagi viral belum? Toko Buku ini baru buka, tapi yang dateng udah ribuan orang tiap harinya. Mari, datang ke sini. 
                Disuguhkan dengan tampilan toko buku yang cozzy dan dengan produk terbaik, bisa dapet souvenir juga. Kurang apa coba?
                <br>Yakin nih nggak ngoleksi produk kami? Rugi sih.  
            </p>
        </div>
    </header>

    <!-- Features Section -->
    <section id="tentang" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-semibold">KEUNGGULAN KAMI</span>
                <h2 class="text-3xl font-bold text-dark mt-2">Mengapa Memilih BookStore Gen Z Kami ?</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl card-hover">
                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-6 mx-auto text-2xl">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center text-dark">Suasana Yang Nyaman</h3>
                    <p class="text-gray-600 text-center">
                        Toko kami didesain senyaman dan semenarik mungkin mengikuti perkembangan zaman agar para remaja senang membaca buku di toko kami.
                    </p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl card-hover">
                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-6 mx-auto text-2xl">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center text-dark">Buku Terbaru</h3>
                    <p class="text-gray-600 text-center">
                        Toko kami menyuguhkan segala macam buku yang baru dan menarik untuk dibaca.. Bukan hanya buku pelajaran, toko kami juga menyediakan berbagai buku cerita baik itu novel maupun komik lucu.
                    </p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl card-hover">
                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-6 mx-auto text-2xl">
                        <i class="fas fa-mug-hot"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center text-dark">Dilengkapi Dengan Cafe</h3>
                    <p class="text-gray-600 text-center">
                        Toko buku kami tidak hanya menyediakan layanan pembelian atau membaca buku,, kami juga menyediakan cafe dengan suasana yang cozzy agar semakin betah untuk berbelanja atau hanya sekedar singgah ke toko kami
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-semibold">LAYANAN UNGGULAN</span>
                <h2 class="text-3xl font-bold text-dark mt-2">Solusi Bagi Para Pecinta Buku</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $services = [
                    ['address-book', 'Pencarian Buku', 'Pelanggan dapat mencari buku berdasarkan judul, penulis, penerbit, kategori, atau kata kunci lainnya. ', 'Pencarian-Buku'],
                    ['download', 'Sistem Belanja Online', 'Pelanggan dapat memilih buku yang diinginkan, menambahkan ke keranjang belanja, dan menyelesaikan transaksi pembayaran secara online. ', 'Belanja-Online'],
                    ['file-invoice', 'Pilihan Pembayaran', 'Toko buku online menyediakan berbagai metode pembayaran seperti transfer bank, kartu kredit, atau e-wallet. ', 'Pilihan-Pembayaran'],
                    ['cart-shopping ', 'Layanan Pengiriman', 'Buku yang dibeli akan dikirim ke alamat pelanggan dengan pilihan ekspedisi yang beragam. ', 'Layanan-Pengiriman'],
                    ['swatchbook', 'Layanan Tambahan', ' Melayanani print on demand (untuk buku yang out of stock), layanan digital (buku elektronik, majalah digital), dan kolaborasi dengan berbagai penerbit. ', 'Layanan-Tambahan'],
                    ['percent', 'Promosi Atau Diskon Setiap Bulan', 'Setiap bulan selalu ada aturan menarik dan terbaru. Ada diskon, Ada potongan harga dan masih banyak lagi kejutan tiap bulannya.', 'Promosi-Diskon-Setiap-Bulan']
                ];
                foreach ($services as $service) {
                    echo '
                    <div class="bg-white p-6 rounded-lg shadow-sm card-hover">
                        <div class="w-14 h-14 bg-primary/10 text-primary rounded-lg flex items-center justify-center mb-4 text-2xl">
                            <i class="fas fa-'.$service[0].'"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-dark">'.$service[1].'</h3>
                        <p class="text-gray-600 mb-5">'.$service[2].'</p>
                        <a href="layanan.php?service='.$service[3].'" 
                           class="text-primary font-medium inline-flex items-center group">
                            Pesan Sekarang 
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-semibold">FASILITAS KAMI</span>
                <h2 class="text-3xl font-bold text-dark mt-2">BookStore Gen Z</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="relative group overflow-hidden rounded-lg aspect-square bg-gray-200">
                    <img src="../book-store/image/3.jpg" 
                         alt="Ruang Baca" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-medium">Area Baca Indoor</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-lg aspect-square bg-gray-200">
                    <img src="..//book-store/image/2.jpg" 
                         alt="Area Cafe" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-medium">Area Cafe Indoor</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-lg aspect-square bg-gray-200">
                    <img src="..//book-store/image/7.jpg" 
                         alt="Area Santai" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-medium">Area Santai</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-lg aspect-square bg-gray-200">
                    <img src="../book-store/image/8.jpg" 
                         alt="Cafe Outdoor" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-medium">Area Cafe Outdoor</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Discount Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-semibold">PROMO SPESIAL</span>
                <h2 class="text-3xl font-bold text-dark mt-2">Dapatkan Diskon Menarik</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Discount Card 1 -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 card-hover">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-dark">Member</h3>
                            <p class="text-gray-600">Diskon khusus member</p>
                        </div>
                        <span class="text-2xl font-bold text-primary">20%</span>
                    </div>
                    <div class="bg-white rounded-lg p-3 flex justify-between items-center">
                        <code class="text-gray-800 font-mono font-bold">MEMBER15</code>
                        <button onclick="copyDiscountCode('Gen-Z')" class="text-primary hover:text-blue-700">
                            <i class="far fa-copy"></i> Salin
                        </button>
                    </div>
                </div>

                <!-- Discount Card 2 -->
                <div class="bg-green-50 border border-green-100 rounded-xl p-6 card-hover">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-dark">Pelanggan Baru</h3>
                            <p class="text-gray-600">Diskon Khusus Pelanggan Pertama</p>
                        </div>
                        <span class="text-2xl font-bold text-green-600">15%</span>
                    </div>
                    <div class="bg-white rounded-lg p-3 flex justify-between items-center">
                        <code class="text-gray-800 font-mono font-bold">NEW22</code>
                        <button onclick="copyDiscountCode('PREMIUM15')" class="text-green-600 hover:text-green-700">
                            <i class="far fa-copy"></i> Salin
                        </button>
                    </div>
                </div>

                <!-- Discount Card 3 -->
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-6 card-hover">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-dark">Diskon Spesial</h3>
                            <p class="text-gray-600">Diskon untuk semua layanan</p>
                        </div>
                        <span class="text-2xl font-bold text-yellow-600">10%</span>
                    </div>
                    <div class="bg-white rounded-lg p-3 flex justify-between items-center">
                        <code class="text-gray-800 font-mono font-bold">DISKON31</code>
                        <button onclick="copyDiscountCode('SPESIAL10')" class="text-yellow-600 hover:text-yellow-700">
                            <i class="far fa-copy"></i> Salin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notification -->
    <div id="discount-notification" class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 hidden">
        <span id="notification-text"></span>
    </div>

    <!-- Testimonials -->
    <section class="py-20 bg-gray-900 text-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-semibold">TESTIMONI PELANGGAN</span>
                <h2 class="text-3xl font-bold mt-2">Apa Kata Mereka</h2>
                <div class="w-20 h-1 bg-primary mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <?php
                $testimonials = [
                    ['A.S.', 'Belanja di toko buku ini sangat menyenangkan. Koleksi bukunya sangat lengkap dan harganya juga terjangkau. Saya juga suka dengan promo-promo yang sering mereka tawarkan. Rekomendasi banget!', '5'],
                    ['R.W.', 'Saya sangat puas dengan pelayanan toko buku ini. Proses pemesanan mudah, pengemasan buku rapi, dan pengirimannya cepat. Buku yang saya pesan juga dalam kondisi baik. Terima kasih !', '5'],
                    ['B.K.', 'Saya baru pertama kali belanja di toko buku ini, dan saya sangat terkesan dengan kualitas produk dan layanan mereka. Buku yang saya pesan datang dengan cepat dan dalam kondisi yang baik. Saya akan berbelanja lagi di sini!', '4']
                ];
                foreach ($testimonials as $testimonial) {
                    echo '
                    <div class="bg-gray-800 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full avatar-initial mr-4 text-xl">
                                '.$testimonial[0].'
                            </div>
                            <div>
                                <h4 class="font-bold">'.$testimonial[0].'</h4>
                                <div class="flex text-yellow-400 mt-1">
                                    '.str_repeat('<i class="fas fa-star"></i>', $testimonial[2]).'
                                    '.($testimonial[2] < 5 ? str_repeat('<i class="far fa-star"></i>', 5 - $testimonial[2]) : '').'
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-300">"'.$testimonial[1].'"</p>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>
    <script>
        // Close mobile menu when clicking on a link
        const mobileMenuLinks = document.querySelectorAll('#mobile-menu a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('open');
            });
        });

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animation on scroll
        document.addEventListener('DOMContentLoaded', () => {
            const animateElements = document.querySelectorAll('.animate-fade');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade');
                    }
                });
            }, { threshold: 0.1 });
            animateElements.forEach(el => observer.observe(el));
        });

        // Function to copy discount code with notification
        function copyDiscountCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                const notification = document.getElementById('discount-notification');
                const notificationText = document.getElementById('notification-text');
                
                notificationText.textContent = `Kode diskon "${code}" berhasil disalin!`;
                notification.classList.remove('hidden');
                notification.classList.add('flex');
                
                // Hide notification after 3 seconds
                setTimeout(() => {
                    notification.classList.add('hidden');
                    notification.classList.remove('flex');
                }, 3000);
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>
</body>
</html>
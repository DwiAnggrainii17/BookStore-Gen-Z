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
                <a href="#" class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-book mr-2 text-primary"></i>
                    <span>BookStore</span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-primary transition-colors">Produk</a>
                    <a href="about.php" class="text-gray-700 hover:text-primary transition-colors">About Us</a>
                    <a href="contact.php" class="text-gray-700 hover:text-primary transition-colors">Contact</a>
                    
                    <!-- User Actions -->
                    <div class="flex items-center space-x-6 ml-6">
                        <?php if ($isLoggedIn): ?>
                            <a href="keranjang.php" class="text-gray-700 hover:text-primary transition-colors">
                                <i class="fas fa-shopping-cart mr-1"></i> Keranjang
                            </a>
                            <a href="logout.php" class="text-gray-700 hover:text-primary transition-colors">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </a>
                        <?php else: ?>
                            <a href="login_user.php" class="text-gray-700 hover:text-primary transition-colors">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a>
                            <a href="login_user.php" class="text-gray-700 hover:text-primary transition-colors">
                                <i class="fas fa-shopping-cart mr-1"></i> Keranjang
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

    <!-- CTA Section -->
    <section id="kontak" class="py-16 bg-primary text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-6">Tertarik Dengan Toko Buku Kami?</h2>
            <p class="text-xl max-w-2xl mx-auto mb-10">
                Hubungi kami sekarang atau datang langsung ke BookStore Gen-Z
            </p>
            <div class="flex flex-col md:flex-row justify-center gap-4">
                <a href="tel:085694414812" class="bg-white text-dark px-8 py-3 rounded-md font-medium hover:bg-gray-100 transition-colors">
                    <i class="fas fa-phone-alt mr-2"></i> 0856 9441 4812
                </a>
                <a href="https://maps.google.com" target="_blank" class="bg-dark text-white px-8 py-3 rounded-md font-medium hover:bg-gray-900 transition-colors">
                    <i class="fas fa-map-marker-alt mr-2"></i> Lihat Lokasi
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-gray-400 py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="logo-icon">
                            <i class="fas fa-book-open-reader"></i>
                        </div>
                        <span class="logo-text">BookStore Gen-Z</span>
                    </div>
                    <p class="mb-4">
                        Solusi bagi anda yang ingin mencari buku terlengkap dan berkualitas.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white transition-colors"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-white transition-colors"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Layanan</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">Judul Buku Lengkap</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pengiriman Cepat</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Menyediakan Buku Secara Digital</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Banyak Kejutan Setiap Bulannya</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Informasi</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                            <span>Jl. Remaja No. 123, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3"></i>
                            <span>(021) 8579243</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            <span>contact@gen-zbookstore.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock mr-3"></i>
                            <span>Senin-Sabtu: 08.00-22.00</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p>&copy; 2024 BookStore Gen-Z. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <div class="fixed bottom-6 right-6 z-50 group">
        <a href="https://wa.me/6285694414812" 
           target="_blank"
           class="bg-green-500 hover:bg-green-600 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 transform hover:scale-110">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
        <div class="absolute right-16 bottom-0 bg-white text-gray-800 px-3 py-1 rounded-md shadow-md hidden md:block opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            Chat via WhatsApp
        </div>
    </div>

    <script>
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('open');
        });

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
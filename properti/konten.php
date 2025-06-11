<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - KSPPS PMA</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            .gradient-bg {
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            }
            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
            .branch-map {
                display: none;
                height: 0;
                transition: all 0.3s ease;
            }
            .branch-map.active {
                display: block;
                height: 450px;
            }
        </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div>
                        <img src="logonav.png" alt="Logo KSSP" class="w-[60px] h-[50px] rounded-full">
                    </div>
                    <div class="hidden md:flex items-center space-x-1">
                        <span class="text-xl font-bold text-blue-600">KSPPS</span>
                        <span class="text-xl font-bold text-gray-800">PMA</span>
                    </div>
                </div>
                
                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.html" class="text-gray-600 hover:text-blue-600 transition">Beranda</a>
                    <a href="layanan.html" class="text-gray-600 hover:text-blue-600 transition">Layanan</a>
                    <a href="tentang.html" class="text-gray-600 hover:text-blue-600 transition">Tentang Kami</a>
                    <a href="berita.html" class="text-gray-600 hover:text-blue-600 transition">berita</a>
                   <a href="kontak.html" class="text-blue-600 font-medium">Kontak</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="outline-none">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="index.html" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50">Beranda</a>
                <a href="layanan.html" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50">Layanan</a>
                <a href="tentang.html" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-blue-600 hover:bg-blue-50">Tentang Kami</a>
               <a href="kontak.html" class="block px-3 py-2 rounded-md text-base font-medium text-blue-700 bg-blue-50">Kontak</a>
            </div>
        </div>
    </nav>




    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Koperasi KSSP</h3>
                    <p class="text-gray-400">Memberikan solusi keuangan syariah untuk kesejahteraan anggota dan masyarakat.</p>
                    <div class="flex space-x-4 mt-6">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="index.html" class="text-gray-400 hover:text-white transition">Beranda</a></li>
                        <li><a href="layanan.html" class="text-gray-400 hover:text-white transition">Layanan</a></li>
                        <li><a href="tentang.html" class="text-gray-400 hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="testimoni.html" class="text-gray-400 hover:text-white transition">Testimoni</a></li>
                        <li><a href="kontak.html" class="text-gray-400 hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Layanan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Simpanan Syariah</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Pembiayaan Syariah</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Investasi Halal</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Pendidikan Keuangan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Newsletter</h4>
                    <p class="text-gray-400 mb-4">Berlangganan untuk info terbaru dan promo menarik.</p>
                    <form class="flex">
                        <input type="email" placeholder="Email Anda" class="px-4 py-2 rounded-l-lg focus:outline-none text-gray-800 w-full">
                        <button type="submit" class="bg-blue-600 px-4 py-2 rounded-r-lg hover:bg-blue-700 transition"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-10 pt-6 text-center text-gray-400">
                <p>&copy; 2025 Koperasi Simpan Pinjam dan Pembiayaan Syariah Perambabulan Makmur Abadi. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Fungsi untuk toggle map
        function toggleMap(mapId) {
            const map = document.getElementById(mapId);
            const allMaps = document.querySelectorAll('.branch-map');
            
            // Tutup semua map yang terbuka
            allMaps.forEach(m => {
                if (m.id !== mapId && m.classList.contains('active')) {
                    m.classList.remove('active');
                }
            });
            
            // Buka/tutup map yang diklik
            map.classList.toggle('active');
            
            // Rotate icon
            const icon = map.previousElementSibling.querySelector('i.fa-chevron-down');
            icon.classList.toggle('rotate-180');
        }

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // FAQ Toggle
        document.querySelectorAll('.faq-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const icon = button.querySelector('i');
                
                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
                button.classList.toggle('bg-gray-100');
            });
        });
    </script>
</body>
</html>

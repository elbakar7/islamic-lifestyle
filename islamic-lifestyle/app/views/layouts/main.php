<!DOCTYPE html>
<html lang="sw" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Islamic Lifestyle - Jukwaa kamili la Kiislamu kwa jamii ya Waislamu Tanzania">
    <title><?= e($title ?? 'Islamic Lifestyle') ?> - Islamic Lifestyle</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .arabic { font-family: 'Traditional Arabic', 'Noto Naskh Arabic', serif; direction: rtl; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-800 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-4">
                    <a href="/" class="flex items-center space-x-2">
                        <i class="fas fa-mosque text-2xl"></i>
                        <span class="font-bold text-xl">Islamic Lifestyle</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/prayer-times" class="hover:text-green-200 transition">
                        <i class="fas fa-clock mr-1"></i> Nyakati za Sala
                    </a>
                    <a href="/quran" class="hover:text-green-200 transition">
                        <i class="fas fa-book-quran mr-1"></i> Qurani
                    </a>
                    <a href="/hadith" class="hover:text-green-200 transition">
                        <i class="fas fa-book mr-1"></i> Hadith
                    </a>
                    <a href="/fatwa" class="hover:text-green-200 transition">
                        <i class="fas fa-question-circle mr-1"></i> Fatwa
                    </a>
                    <a href="/zakat-calculator" class="hover:text-green-200 transition">
                        <i class="fas fa-calculator mr-1"></i> Zakat
                    </a>
                    <a href="/businesses" class="hover:text-green-200 transition">
                        <i class="fas fa-store mr-1"></i> Biashara Halal
                    </a>
                    <a href="/donate" class="hover:text-green-200 transition">
                        <i class="fas fa-hand-holding-heart mr-1"></i> Changia
                    </a>
                    
                    <?php if (isLoggedIn()): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-1 hover:text-green-200">
                                <i class="fas fa-user-circle"></i>
                                <span><?= e($_SESSION['user_name'] ?? 'User') ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="/dashboard" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                                <a href="/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Wasifu
                                </a>
                                <?php if (isAdmin()): ?>
                                    <a href="/admin" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i> Admin
                                    </a>
                                <?php endif; ?>
                                <hr class="my-1">
                                <a href="/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Toka
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md transition">
                            <i class="fas fa-sign-in-alt mr-1"></i> Ingia
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-green-700">
            <div class="px-4 py-2 space-y-2">
                <a href="/prayer-times" class="block py-2 hover:text-green-200">
                    <i class="fas fa-clock mr-2"></i> Nyakati za Sala
                </a>
                <a href="/quran" class="block py-2 hover:text-green-200">
                    <i class="fas fa-book-quran mr-2"></i> Qurani
                </a>
                <a href="/hadith" class="block py-2 hover:text-green-200">
                    <i class="fas fa-book mr-2"></i> Hadith
                </a>
                <a href="/fatwa" class="block py-2 hover:text-green-200">
                    <i class="fas fa-question-circle mr-2"></i> Fatwa
                </a>
                <a href="/zakat-calculator" class="block py-2 hover:text-green-200">
                    <i class="fas fa-calculator mr-2"></i> Zakat
                </a>
                <a href="/businesses" class="block py-2 hover:text-green-200">
                    <i class="fas fa-store mr-2"></i> Biashara Halal
                </a>
                <a href="/donate" class="block py-2 hover:text-green-200">
                    <i class="fas fa-hand-holding-heart mr-2"></i> Changia
                </a>
                
                <?php if (isLoggedIn()): ?>
                    <hr class="border-green-600">
                    <a href="/dashboard" class="block py-2 hover:text-green-200">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="/profile" class="block py-2 hover:text-green-200">
                        <i class="fas fa-user mr-2"></i> Wasifu
                    </a>
                    <?php if (isAdmin()): ?>
                        <a href="/admin" class="block py-2 hover:text-green-200">
                            <i class="fas fa-cog mr-2"></i> Admin
                        </a>
                    <?php endif; ?>
                    <a href="/logout" class="block py-2 hover:text-green-200">
                        <i class="fas fa-sign-out-alt mr-2"></i> Toka
                    </a>
                <?php else: ?>
                    <hr class="border-green-600">
                    <a href="/login" class="block py-2 hover:text-green-200">
                        <i class="fas fa-sign-in-alt mr-2"></i> Ingia
                    </a>
                    <a href="/register" class="block py-2 hover:text-green-200">
                        <i class="fas fa-user-plus mr-2"></i> Jisajili
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if ($success = flash('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= e($success) ?></span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </span>
        </div>
    <?php endif; ?>
    
    <?php if ($error = flash('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= e($error) ?></span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </span>
        </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="min-h-screen">
        <?= $content ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Kuhusu Sisi</h3>
                    <p class="text-gray-300">
                        Islamic Lifestyle ni jukwaa la kidijitali linalolenga kutatua mahitaji ya Kiislamu kwa jamii ya Waislamu Tanzania.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Viungo Muhimu</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="/prayer-times" class="hover:text-white">Nyakati za Sala</a></li>
                        <li><a href="/quran" class="hover:text-white">Soma Qurani</a></li>
                        <li><a href="/zakat-calculator" class="hover:text-white">Kokotoa Zakat</a></li>
                        <li><a href="/articles" class="hover:text-white">Makala</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Huduma Zetu</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="/fatwa" class="hover:text-white">Fatwa & Maswali</a></li>
                        <li><a href="/inheritance-calculator" class="hover:text-white">Urithi wa Kiislamu</a></li>
                        <li><a href="/businesses" class="hover:text-white">Biashara Halal</a></li>
                        <li><a href="/donate" class="hover:text-white">Michango</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="font-bold text-lg mb-4">Wasiliana Nasi</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-envelope mr-2"></i> info@islamiclifestyle.tz</li>
                        <li><i class="fas fa-phone mr-2"></i> +255 123 456 789</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Dar es Salaam, Tanzania</li>
                    </ul>
                    <div class="mt-4 space-x-3">
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-youtube text-xl"></i></a>
                    </div>
                </div>
            </div>
            
            <hr class="border-gray-700 my-8">
            
            <div class="text-center text-gray-300">
                <p>&copy; <?= date('Y') ?> Islamic Lifestyle. Haki zote zimehifadhiwa.</p>
                <p class="mt-2 text-sm">
                    Imetengenezwa kwa upendo <i class="fas fa-heart text-red-500"></i> kwa jamii ya Waislamu Tanzania
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
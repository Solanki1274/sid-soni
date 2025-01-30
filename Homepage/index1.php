<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soni Builders - Building Dreams</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="custom-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-black text-white">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-black/90 backdrop-blur-sm border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="flex items-center space-x-2">
                        <img class="h-10 w-10" src="photos/logo.png" alt="Logo">
                        <span class="text-2xl font-bold gradient-text">
                            Soni Builders
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="flex items-center text-white hover:text-red-600 transition-all duration-300">
                        HOME
                    </a>
                    <a href="#" class="flex items-center text-white hover:text-red-600 transition-all duration-300">
                        ABOUT
                    </a>
                    <a href="#" class="flex items-center text-white hover:text-red-600 transition-all duration-300">
                        SERVICES
                    </a>
                    <a href="#" class="flex items-center text-white hover:text-red-600 transition-all duration-300">
                        PORTFOLIO
                    </a>
                    <a href="#" class="flex items-center text-white hover:text-red-600 transition-all duration-300">
                        CONTACT
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden rounded-md p-2 text-white hover:bg-gray-800 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen bg-gradient-to-br from-black to-gray-900 flex items-center justify-center py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 gradient-text animate-fadeIn">
                Transform Your Digital Presence
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-8 animate-fadeIn">
                We build innovative solutions for forward-thinking businesses
            </p>
            <button class="hero-btn bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-8 rounded-lg">
                Get Started
            </button>
        </div>
        
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden -z-10">
            <div class="absolute w-96 h-96 bg-red-600/10 rounded-full blur-3xl -top-10 -left-10 animate-float"></div>
            <div class="absolute w-96 h-96 bg-red-600/10 rounded-full blur-3xl -bottom-10 -right-10 animate-float delay-1000"></div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-black">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4 gradient-text">Our Services</h2>
            <p class="text-gray-400 text-center mb-12">Comprehensive solutions for your digital needs</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service Card 1 -->
                <div class="service-card bg-gray-900/50 p-6 rounded-xl border border-gray-800">
                    <div class="text-red-600 text-4xl mb-4">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Web Development</h3>
                    <p class="text-gray-400">Custom websites and web applications built with the latest technologies.</p>
                </div>
                
                <!-- Add more service cards similarly -->
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="py-20 bg-gradient-to-b from-black to-gray-900">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-4 gradient-text">Our Work</h2>
            <p class="text-gray-400 text-center mb-12">Browse through our recent projects</p>
            
            <!-- Portfolio grid/slider here -->
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-gray-800 pt-12 pb-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Company Info -->
                <div>
                    <h3 class="text-2xl font-bold gradient-text mb-4">Soni Builders</h3>
                    <p class="text-gray-400 mb-6">Building dreams into reality since 2020.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="social-link text-gray-400 hover:text-red-600">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link text-gray-400 hover:text-red-600">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link text-gray-400 hover:text-red-600">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Services</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Portfolio</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-red-600 transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-4">
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                            <span class="text-gray-400">123 Business Street, City, Country</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone text-red-600"></i>
                            <span class="text-gray-400">+1 234 567 890</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-red-600"></i>
                            <span class="text-gray-400">info@sonibuilders.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-12 pt-6 text-center text-gray-400">
                <p>&copy; 2024 Soni Builders. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Add any necessary JavaScript here
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fadeIn': 'fadeIn 0.5s ease-in-out',
                    }
                }
            }
        }
    </script>
</body>
</html>
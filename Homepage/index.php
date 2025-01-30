<?php
// config.php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'sonibuild';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soni Builders - Building Dreams</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="index.php" class="flex items-center space-x-2">
                        <img class="h-10 w-10" src="photos/logo.png" alt="Logo">
                        <span class="text-2xl font-bold bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">Soni Builders</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <?php
                    $menu_items = [
                        'HOME' => ['url' => 'index.php', 'icon' => 'fa-home'],
                        'ABOUT' => ['url' => 'about.php', 'icon' => 'fa-info-circle'],
                        'SERVICES' => ['url' => 'services.php', 'icon' => 'fa-cogs'],
                        'PORTFOLIO' => ['url' => 'portfolio.php', 'icon' => 'fa-briefcase'],
                        'CONTACT' => ['url' => 'contact.php', 'icon' => 'fa-envelope']
                    ];

                    foreach ($menu_items as $name => $data) {
                        $active = basename($_SERVER['PHP_SELF']) == $data['url'] ? 'text-red-600' : 'text-gray-800';
                        echo "<a href='{$data['url']}' class='$active hover:text-red-600 transition-all duration-300 flex items-center gap-2'>
                                <i class='fas {$data['icon']}'></i>
                                <span>$name</span>
                              </a>";
                    }
                    ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-800 hover:text-red-600">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden fixed inset-0 z-50 bg-white">
        <div class="flex flex-col items-center justify-center h-full space-y-8">
            <?php
            foreach ($menu_items as $name => $data) {
                echo "<a href='{$data['url']}' class='text-2xl text-gray-800 hover:text-red-600 flex items-center gap-2'>
                        <i class='fas {$data['icon']}'></i>
                        <span>$name</span>
                      </a>";
            }
            ?>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center py-20 bg-cover bg-center" 
             style="background-image: url('photos/bg.jpg');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 text-white">
                Transform Your Digital Presence
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 mb-8">
                We build innovative solutions for forward-thinking businesses
            </p>
            <a href="../Main/login.php" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-8 rounded-lg transform hover:scale-105 transition-all duration-300">
                Get Started
            </a>
        </div>

        <!-- Floating SVGs -->
        <img src="photos/pic-1.jpg" alt="Floating Image 1" 
         class="animate-float absolute top-20 left-20 w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg" style="animation-delay: 0.2s">
    <img src="photos/pic-2.jpg" alt="Floating Image 2" 
         class="animate-float absolute bottom-10 right-20 w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg" style="animation-delay: 0.5s">
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4 bg-gradient-to-r from-red-600 to-red-800 bg-clip-text text-transparent">
            Our Services
        </h2>
        <p class="text-gray-600 text-center mb-12">Comprehensive solutions for your digital needs</p>

        <!-- Slider Container -->
        <div class="relative w-full overflow-hidden">
            <div class="flex transition-transform duration-500 ease-in-out" id="serviceSlider">
                <?php
                $query = "SELECT * FROM services";
                $result = mysqli_query($conn, $query);
                
                while($service = mysqli_fetch_assoc($result)) {
                ?>
                <!-- Service Card -->
                <div class="min-w-full md:min-w-[33.33%] p-4">
                    <div class="service-card bg-white p-6 rounded-xl shadow-lg border border-gray-100 
                                transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                        <img src="<?= $service['image_url'] ?>" alt="<?= $service['name'] ?>" 
                             class="w-full h-48 object-cover mb-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-3 text-gray-800"><?= $service['name'] ?></h3>
                        <p class="text-gray-600 mb-4"><?= $service['description'] ?></p>

                        <!-- Features List -->
                        <ul class="text-gray-500 text-sm mb-4">
                            <?php
                            $features = explode(',', $service['features']); // Assuming features are comma-separated
                            foreach ($features as $feature) {
                                echo "<li class='flex items-center space-x-2'><i class='fas fa-check text-green-500'></i> <span>$feature</span></li>";
                            }
                            ?>
                        </ul>

                        <!-- Price and Duration -->
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-red-600 font-bold">$<?= $service['price'] ?></span>
                            <span class="text-gray-500"><?= $service['duration'] ?> mins</span>
                        </div>

                        <!-- Book Now Button -->
                        <a href="../Main/login.php" class="block text-center bg-red-600 text-white py-2 px-4 rounded-md 
                                hover:bg-red-700 transition">Book Now</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-center space-x-4 mt-6">
    <button id="prevBtn" class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full">
        <i class="fas fa-chevron-left text-gray-700"></i>
    </button>
    <button id="nextBtn" class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full">
        <i class="fas fa-chevron-right text-gray-700"></i>
    </button>
</div>

</section>
<script>
    // Slider Logic
    const slider = document.getElementById('serviceSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let index = 0;
    let slides = slider.children; // Get all the slider items
    const totalSlides = slides.length; // Number of available slides
    if (totalSlides === 0) return; // If no slides, exit the script

    const slideWidth = slides[0].offsetWidth; // Get the width of one slide
    let autoSlide = setInterval(nextSlide, 3000); // Auto slide every 3 sec

    function nextSlide() {
        if (index < totalSlides - 1) {
            index++;
        } else {
            index = 0;  // Loop back to the first slide
        }
        updateSlider();
    }

    function prevSlide() {
        if (index > 0) {
            index--;
        } else {
            index = totalSlides - 1; // Loop to the last slide
        }
        updateSlider();
    }

    function updateSlider() {
        // Ensure that the slider's transform is updated with the correct index and width
        slider.style.transition = "transform 0.5s ease"; // Smooth transition for sliding effect
        slider.style.transform = `translateX(-${index * slideWidth}px)`;
    }

    // Pause auto-slide on hover
    slider.addEventListener("mouseenter", () => clearInterval(autoSlide));
    slider.addEventListener("mouseleave", () => autoSlide = setInterval(nextSlide, 3000));

    // Button controls (only enable if there are more than one slide)
    if (totalSlides > 1) {
        nextBtn.addEventListener("click", nextSlide);
        prevBtn.addEventListener("click", prevSlide);
    } else {
        // Disable navigation buttons if there is only one slide
        nextBtn.disabled = true;
        prevBtn.disabled = true;
    }
    
    // Ensure the slide width is updated when the window is resized
    window.addEventListener('resize', () => {
        const newSlideWidth = slides[0].offsetWidth;
        if (newSlideWidth !== slideWidth) {
            updateSlider(); // Recalculate position on resize
        }
    });
</script>
<!-- Our Work Area -->
<div class="text-center py-16 bg-gray-100">
    <h2 class="text-3xl font-semibold mb-8 text-gray-800">Our Work Area</h2>

    <!-- Slider Container -->
    <div class="relative w-full">
        <div class="flex overflow-x-auto space-x-6 snap-x scroll-smooth" id="workSlider">

            <!-- Social Media Card -->
            <div class="relative w-80 h-72 bg-cover bg-center rounded-lg shadow-lg hover:shadow-xl transition-transform transform hover:scale-105 cursor-pointer" style="background-image: url('path-to-social-media-background.jpg');">
                <div class="absolute inset-0 bg-black opacity-50"></div> <!-- Overlay for readability -->
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="text-2xl font-medium">Social Media</h3>
                    <p class="text-sm mt-2">Boost your brand's presence across various social media platforms with effective strategies.</p>
                </div>
            </div>

            <!-- Emerging Startups Card -->
            <div class="relative w-80 h-72 bg-cover bg-center rounded-lg shadow-lg hover:shadow-xl transition-transform transform hover:scale-105 cursor-pointer" style="background-image: url('path-to-startup-background.jpg');">
                <div class="absolute inset-0 bg-black opacity-50"></div> <!-- Overlay for readability -->
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="text-2xl font-medium">Emerging Startups</h3>
                    <p class="text-sm mt-2">We help emerging startups grow by providing customized strategies and creative solutions.</p>
                </div>
            </div>

            <!-- Large Enterprises Card -->
            <div class="relative w-80 h-72 bg-cover bg-center rounded-lg shadow-lg hover:shadow-xl transition-transform transform hover:scale-105 cursor-pointer" style="background-image: url('path-to-enterprise-background.jpg');">
                <div class="absolute inset-0 bg-black opacity-50"></div> <!-- Overlay for readability -->
                <div class="absolute bottom-4 left-4 text-white">
                    <h3 class="text-2xl font-medium">Large Enterprises</h3>
                    <p class="text-sm mt-2">Transform your large enterprise with digital solutions that drive efficiency and growth.</p>
                </div>
            </div>

        </div>

        <!-- Slider Buttons -->
        <div class="absolute top-1/2 left-0 transform -translate-y-1/2 flex justify-between w-full px-4">
            <button id="prevBtn" class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full">
                <i class="fas fa-chevron-left text-gray-700"></i>
            </button>
            <button id="nextBtn" class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full">
                <i class="fas fa-chevron-right text-gray-700"></i>
            </button>
        </div>
    </div>
</div>
<script>
    const workSlider = document.getElementById('workSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let index = 0;
    const slides = workSlider.children;
    const totalSlides = slides.length;

    function nextSlide() {
        if (index < totalSlides - 1) {
            index++;
        } else {
            index = 0;  // Loop back to the first slide
        }
        updateSlider();
    }

    function prevSlide() {
        if (index > 0) {
            index--;
        } else {
            index = totalSlides - 1;  // Loop to the last slide
        }
        updateSlider();
    }

    function updateSlider() {
        workSlider.style.transform = `translateX(-${index * (slides[0].offsetWidth + 24)}px)`;  // Adjusts the slider position based on the width of each card and the gap
    }

    // Button Controls
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);
</script>



<!-- Portfolio -->
<section class="py-20 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-4 bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
            Our Projects
        </h2>
        <p class="text-gray-600 text-center mb-12">Showcasing our best work and innovations</p>

        <!-- Slider Container -->
        <div class="relative w-full overflow-hidden">
            <div class="flex transition-transform duration-500 ease-in-out" id="projectSlider">
                <?php
                $query = "SELECT * FROM projects"; // Assuming you have a 'projects' table
                $result = mysqli_query($conn, $query);
                
                while($project = mysqli_fetch_assoc($result)) {
                ?>
                <!-- Project Card -->
                <div class="min-w-full md:min-w-[33.33%] p-4">
                    <div class="project-card bg-white p-6 rounded-xl shadow-lg border border-gray-100 
                                transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                        <img src="<?= $project['image_url'] ?>" alt="<?= $project['title'] ?>" 
                             class="w-full h-48 object-cover mb-4 rounded-lg">
                        <h3 class="text-xl font-bold mb-3 text-gray-800"><?= $project['title'] ?></h3>
                        <p class="text-gray-600 mb-4"><?= $project['description'] ?></p>

                        <!-- Features List -->
                        <ul class="text-gray-500 text-sm mb-4">
                            <?php
                            $features = explode(',', $project['features']); // Assuming features are comma-separated
                            foreach ($features as $feature) {
                                echo "<li class='flex items-center space-x-2'><i class='fas fa-check text-green-500'></i> <span>$feature</span></li>";
                            }
                            ?>
                        </ul>

                        <!-- View More Button -->
                        <a href="projects.php" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-md 
                                hover:bg-blue-700 transition">View More</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-center space-x-4 mt-6">
            <button id="prevProjectBtn" class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full">
                <i class="fas fa-chevron-left text-gray-700"></i>
            </button>
            <button id="nextProjectBtn" class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full">
                <i class="fas fa-chevron-right text-gray-700"></i>
            </button>
        </div>
    </div>
</section>

<script>
    // Project Slider Logic
    const projectSlider = document.getElementById('projectSlider');
    const prevProjectBtn = document.getElementById('prevProjectBtn');
    const nextProjectBtn = document.getElementById('nextProjectBtn');

    let projectIndex = 0;
    let projectCards = projectSlider.children; // Get all the project cards
    const totalProjects = projectCards.length; // Number of available projects
    if (totalProjects === 0) return; // If no projects, exit the script

    const projectSlideWidth = projectCards[0].offsetWidth;
    let autoProjectSlide = setInterval(nextProjectSlide, 3000); // Auto slide every 3 sec

    function nextProjectSlide() {
        if (projectIndex < totalProjects - 1) {
            projectIndex++;
        } else {
            projectIndex = 0;
        }
        updateProjectSlider();
    }

    function prevProjectSlide() {
        if (projectIndex > 0) {
            projectIndex--;
        } else {
            projectIndex = totalProjects - 1;
        }
        updateProjectSlider();
    }

    function updateProjectSlider() {
        projectSlider.style.transform = `translateX(-${projectIndex * projectSlideWidth}px)`;
    }

    // Pause auto-slide on hover
    projectSlider.addEventListener("mouseenter", () => clearInterval(autoProjectSlide));
    projectSlider.addEventListener("mouseleave", () => autoProjectSlide = setInterval(nextProjectSlide, 3000));

    // Button controls (only enable if there are available projects)
    if (totalProjects > 1) {
        nextProjectBtn.addEventListener("click", nextProjectSlide);
        prevProjectBtn.addEventListener("click", prevProjectSlide);
    } else {
        nextProjectBtn.disabled = true;
        prevProjectBtn.disabled = true;
    }
</script>

<!-- logos -->
<script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
    <style>
        /* Align the full page */
        .zig-zag-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            width: 100%;
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 10px;
            background-size: cover;
            background-position: center;
;
            
        }

        /* Zig-Zag Movement */
        .logo:nth-child(odd) {
            transform: translateY(-20px);
        }

        .logo:nth-child(even) {
            transform: translateY(20px);
        }

        /* Hover effect */
        .logo:hover {
            transform: scale(1.1) translateY(0); /* Slight zoom effect and remove the translateY offset */
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2); /* Add a shadow to the logo on hover */
        }
    </style>



    <div class="zig-zag-container">
        <h2 class="text-4xl font-bold text-center mb-4 bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Tech We use</h2>
        <div class="logo bg-cover bg-center" style="background-image: url('https://smsforyou.biz/wp-content/uploads/2024/01/1.jpg');"></div>
        <div class="logo bg-cover bg-center" style="background-image: url('https://smsforyou.biz/wp-content/uploads/2022/12/Group-4.jpg');"></div>
        <div class="logo bg-cover bg-center" style="background-image: url('https://smsforyou.biz/wp-content/uploads/2024/01/2.jpg');"></div>
        <div class="logo bg-cover bg-center" style="background-image: url('https://smsforyou.biz/wp-content/uploads/2024/01/3.jpg');"></div>
        <div class="logo bg-cover bg-center" style="background-image: url('https://smsforyou.biz/wp-content/uploads/2022/12/Group-10.jpg');"></div>
        <div class="logo bg-cover bg-center" style="background-image: url('https://smsforyou.biz/wp-content/uploads/2024/01/8.jpg');"></div>
        <div class="logo bg-cover bg-center" style="background-image: url('https://smsforyou.biz/wp-content/uploads/2024/01/7.jpg');"></div>
    </div>

   
    <footer class="relative text-white py-16">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('photos/footer.png');"></div>

    <!-- Overlay for better readability -->
    <div class="absolute inset-0 bg-black bg-opacity-70"></div>

    <!-- Content Wrapper -->
    <div class="relative container mx-auto px-6 z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            
            <!-- Company Info -->
            <div>
                <!-- Logo -->
                <div class="flex justify-center md:justify-start items-center space-x-3">
                    <img src="photos/logo.png" alt="Soni Builders Logo" class="h-12">
                    <h3 class="text-3xl font-bold text-white tracking-wide">Soni Builders</h3>
                </div>
                <p class="text-gray-300 mt-4">
                    Transforming visions into reality with unmatched craftsmanship and integrity.
                </p>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-2xl font-semibold mb-4 text-white">Contact Us</h3>
                <ul class="space-y-3">
                    <li class="flex items-center justify-center md:justify-start space-x-3">
                        <i class="fas fa-map-marker-alt text-red-500"></i>
                        <span class="text-gray-300">
                            301, Vaibhav Tower, Anand - Vidyanagar Rd, Gujarat
                        </span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start space-x-3">
                        <i class="fas fa-phone text-red-500"></i>
                        <span class="text-gray-300">+91 94048 81016</span>
                    </li>
                    <li class="flex items-center justify-center md:justify-start space-x-3">
                        <i class="fas fa-envelope text-red-500"></i>
                        <span class="text-gray-300">info@sonibuilders.com</span>
                    </li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-2xl font-semibold mb-4 text-white">Quick Links</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-300 hover:text-red-400 transition">Home</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-red-400 transition">About Us</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-red-400 transition">Projects</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-red-400 transition">Contact</a></li>
                </ul>
            </div>
        </div>

        <!-- Social Media Icons -->
        <div class="flex justify-center space-x-6 mt-8">
            <a href="#" class="text-gray-300 hover:text-red-400 transition">
                <i class="fab fa-facebook-f text-xl"></i>
            </a>
            <a href="#" class="text-gray-300 hover:text-red-400 transition">
                <i class="fab fa-twitter text-xl"></i>
            </a>
            <a href="#" class="text-gray-300 hover:text-red-400 transition">
                <i class="fab fa-instagram text-xl"></i>
            </a>
            <a href="#" class="text-gray-300 hover:text-red-400 transition">
                <i class="fab fa-linkedin-in text-xl"></i>
            </a>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-600 mt-8 pt-6 text-center text-gray-400">
            <p>&copy; <?php echo date('Y'); ?> Soni Builders. All rights reserved.</p>
        </div>
    </div>
</footer>


    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
<?php
session_start();
require_once '../db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch services from the database
$sql = "SELECT * FROM services";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soni Builders - Building Dreams</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>

<body>
    <!-- Header -->
    <header class="header">
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">
                <img src="logo.png" alt="Soni Builders Logo">
            </a>
        </div>
        <div class="nav-toggle" id="navToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-links" id="navLinks">
            <li><a href="#" class="active">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Portfolio</a></li>
            <li><a href="#">Contact</a></li>
            <li class="cta-button"><a href="../Main/login.php">Book</a></li>
        </ul>
    </nav>
</header>


    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-carousel">
            <div class="hero-slide active">
                <h1>Welcome to Our Website</h1>
                <p>Your success starts here. Discover endless possibilities.</p>
                <button class="hero-btn">Learn More</button>
            </div>
            <div class="hero-slide">
                <h1>Empower Your Journey</h1>
                <p>Transform your ideas into reality with our expertise.</p>
                <button class="hero-btn">Get Started</button>
            </div>
            <div class="hero-slide">
                <h1>Innovate with Confidence</h1>
                <p>Explore cutting-edge solutions tailored for you.</p>
                <button class="hero-btn">Explore Now</button>
            </div>
        </div>
        <div class="hero-dots">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
        </div>
    </section>



    <section class="services" id="services">
        <style>
            .hero-section {
                position: relative;
                width: 100%;
                height: 100vh;
                background-image: url('photos/back.jpg');
                /* Use your image */
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                overflow: hidden;
            }

            .hero-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1;
            }

            .hero-carousel {
                position: relative;
                z-index: 2;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;
                color: #fff;
                text-align: center;
            }

            .hero-slide {
                display: none;
                /* Hide slides initially */
                opacity: 0;
                transition: opacity 0.5s ease-in-out;
            }

            .hero-slide.active {
                display: block;
                /* Show active slide */
                opacity: 1;
            }

            .hero-slide h1 {
                font-size: 4rem;
                font-weight: bold;
                margin-bottom: 1rem;
            }

            .hero-slide p {
                font-size: 1.5rem;
                margin-bottom: 2rem;
            }

            .hero-btn {
                padding: 10px 20px;
                font-size: 1rem;
                color: #fff;
                background-color: #007BFF;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .hero-btn:hover {
                background-color: #0056b3;
            }

            .hero-dots {
                position: absolute;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 10px;
                z-index: 2;
            }

            .dot {
                width: 12px;
                height: 12px;
                background-color: #fff;
                border-radius: 50%;
                cursor: pointer;
                opacity: 0.5;
                transition: opacity 0.3s ease;
            }

            .dot.active {
                opacity: 1;
            }


            .services {
                padding: 80px 0;
                background: #f8f9fa;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 15px;
            }

            .section-header {
                text-align: center;
                margin-bottom: 60px;
            }

            .section-title {
                font-size: 2.5rem;
                color: #2c3e50;
                margin-bottom: 15px;
            }

            .section-subtitle {
                font-size: 1.1rem;
                color: #7f8c8d;
            }

            .services-slider {
                position: relative;
                overflow: hidden;
            }

            .services-grid {
                display: flex;
                transition: transform 0.5s ease;
                gap: 30px;
            }

            .service-card {
                flex: 0 0 calc(33.333% - 20px);
                background: white;
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                min-height: 400px;
                display: flex;
                flex-direction: column;
            }

            .service-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            }

            .service-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background: #f0f4f8;
                transition: all 0.3s ease;
            }

            .service-card:hover .service-icon {
                background: #3498db;
            }

            .service-card:hover .service-icon svg {
                fill: white;
            }

            .service-content {
                flex-grow: 1;
                display: flex;
                flex-direction: column;
            }

            .service-content h3 {
                font-size: 1.5rem;
                color: #2c3e50;
                margin: 15px 0;
                text-align: center;
            }

            .service-content p {
                color: #7f8c8d;
                margin-bottom: 20px;
                text-align: center;
                line-height: 1.6;
            }

            .service-features {
                list-style: none;
                padding: 0;
                margin: 20px 0;
            }

            .service-features li {
                color: #34495e;
                margin: 10px 0;
                padding-left: 25px;
                position: relative;
            }

            .service-features li:before {
                content: "✓";
                color: #3498db;
                position: absolute;
                left: 0;
            }

            .service-link {
                text-decoration: none;
                color: #3498db;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-top: auto;
                padding: 10px 20px;
                border: 2px solid #3498db;
                border-radius: 25px;
                transition: all 0.3s ease;
            }

            .service-link:hover {
                background: #3498db;
                color: white;
            }

            .slider-controls {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 30px;
            }

            .slider-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #ddd;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .slider-dot.active {
                background: #3498db;
            }

            .testimonials {
                padding: 60px 0;
                background-color: #f8f9fa;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .section-header {
                text-align: center;
                margin-bottom: 40px;
            }

            .section-title {
                font-size: 2.5rem;
                color: #333;
                margin-bottom: 10px;
            }

            .section-subtitle {
                font-size: 1.1rem;
                color: #666;
            }

            .testimonials-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
                padding: 20px;
            }

            .testimonial-card {
                background: white;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }

            .testimonial-card:hover {
                transform: translateY(-5px);
            }

            .testimonial-quote {
                color: #007bff;
                font-size: 24px;
                margin-bottom: 20px;
            }

            .testimonial-content {
                font-size: 1.1rem;
                color: #555;
                line-height: 1.6;
                margin-bottom: 20px;
            }

            .testimonial-author {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .author-image {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                overflow: hidden;
            }

            .author-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .author-info {
                flex-grow: 1;
            }

            .author-info h4 {
                font-size: 1.1rem;
                color: #333;
                margin-bottom: 5px;
            }

            .author-info p {
                font-size: 0.9rem;
                color: #666;
                margin-bottom: 8px;
            }

            .rating {
                color: #ffc107;
                margin-bottom: 10px;
            }

            .blog-btn {
                background: #007bff;
                color: white;
                border: none;
                padding: 8px 20px;
                border-radius: 5px;
                cursor: pointer;
                transition: background 0.3s;
            }

            .blog-btn:hover {
                background: #0056b3;
            }

            @media (max-width: 768px) {
                .testimonials-grid {
                    grid-template-columns: 1fr;
                }
            }

            .portfolio {
                padding: 60px 0;
                background-color: #f8f9fa;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .section-header {
                text-align: center;
                margin-bottom: 40px;
            }

            .section-title {
                font-size: 2.5rem;
                color: #333;
                margin-bottom: 10px;
            }

            .section-subtitle {
                font-size: 1.1rem;
                color: #666;
            }

            .portfolio-filters {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-bottom: 40px;
                flex-wrap: wrap;
            }

            .filter-btn {
                padding: 8px 20px;
                border: 2px solid #007bff;
                background: transparent;
                color: #007bff;
                border-radius: 25px;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 1rem;
            }

            .filter-btn:hover,
            .filter-btn.active {
                background: #007bff;
                color: white;
            }

            .portfolio-slider {
                position: relative;
                max-width: 1000px;
                margin: 0 auto;
                overflow: hidden;
            }

            .portfolio-container {
                display: flex;
                transition: transform 0.5s ease;
            }

            .portfolio-item {
                min-width: 100%;
                padding: 20px;
                opacity: 1;
                transition: opacity 0.3s ease;
            }

            .portfolio-item.hidden {
                opacity: 0;
                pointer-events: none;
            }

            .portfolio-card {
                background: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }

            .portfolio-card:hover {
                transform: translateY(-5px);
            }

            .portfolio-image {
                position: relative;
                height: 300px;
                overflow: hidden;
            }

            .portfolio-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .portfolio-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 123, 255, 0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .portfolio-card:hover .portfolio-overlay {
                opacity: 1;
            }

            .portfolio-buttons {
                display: flex;
                gap: 15px;
            }

            .portfolio-btn {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background: white;
                color: #007bff;
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .portfolio-btn:hover {
                background: #007bff;
                color: white;
            }

            .portfolio-content {
                padding: 20px;
            }

            .portfolio-content h3 {
                font-size: 1.3rem;
                color: #333;
                margin-bottom: 10px;
            }

            .portfolio-content p {
                color: #666;
                margin-bottom: 15px;
            }

            .read-more {
                color: #007bff;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
            }

            .read-more:hover {
                color: #0056b3;
            }

            .slider-buttons {
                position: absolute;
                top: 50%;
                width: 100%;
                transform: translateY(-50%);
                display: flex;
                justify-content: space-between;
                padding: 0 10px;
                pointer-events: none;
            }

            .slider-btn {
                background: rgba(0, 123, 255, 0.9);
                color: white;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                pointer-events: auto;
            }

            .slider-btn:hover {
                background: #007bff;
                transform: scale(1.1);
            }

            .slider-dots {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin-top: 20px;
            }

            .dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: #ccc;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .dot:hover {
                transform: scale(1.2);
            }

            .dot.active {
                background: #007bff;
            }
        </style>

        <!-- services -->
<div class="container">
    <div class="section-header" data-aos="fade-up">
        <h2 class="section-title">Our Services</h2>
        <p class="section-subtitle">Comprehensive solutions for your digital needs</p>
    </div>

    <div class="services-slider">
        <div class="services-grid">
            <?php
            // Ensure features key exists and is valid
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Add null coalescing and default empty array for features
                    $features = isset($row['features']) ? json_decode($row['features'], true) : [];
                    
                    // Sanitize and provide fallback values
                    $serviceName = htmlspecialchars($row['name'] ?? 'Unnamed Service');
                    $serviceDescription = htmlspecialchars($row['description'] ?? 'No description available');
                    $serviceImageUrl = htmlspecialchars($row['image_url'] ?? 'path/to/default-icon.png');
                    $serviceLink = htmlspecialchars($row['link'] ?? '#');
                    
                    echo '
                    <div class="service-card">
                        <div class="service-icon">
                            <img src="' . $serviceImageUrl . '" alt="' . $serviceName . ' Icon" width="40" height="40">
                        </div>
                        <div class="service-content">
                            <h3>' . $serviceName . '</h3>
                            <p>' . $serviceDescription . '</p>
                            <ul class="service-features">';
                    
                    if (!empty($features)) {
                        foreach ($features as $feature) {
                            echo '<li>' . htmlspecialchars($feature) . '</li>';
                        }
                    } else {
                        echo '<li>No specific features listed</li>';
                    }
                    
                    echo '
                            </ul>
                            <a href="' . $serviceLink . '" class="service-link">Learn More <span>→</span></a>
                        </div>
                    </div>';
                }
            } else {
                echo '<p>No services available at the moment.</p>';
            }
            ?>
        </div>
    </div>
</div>

<script>
    // Slider functionality
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.querySelector('.services-grid');
        const cards = document.querySelectorAll('.service-card');
        const dots = document.querySelectorAll('.slider-dot');
        let currentSlide = 0;
        const cardsPerSlide = 3;
        const totalSlides = Math.ceil(cards.length / cardsPerSlide);

        function updateSlider() {
            const offset = -currentSlide * (100 / totalSlides);
            slider.style.transform = `translateX(${offset}%)`;

            if (dots) {
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        // Auto-slide every 3 seconds
        if (cards.length > cardsPerSlide) {
            setInterval(nextSlide, 3000);
        }

        // Click handlers for dots
        if (dots) {
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    updateSlider();
                });
            });
        }
    });
</script>

    <section class="portfolio" id="portfolio">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Work</h2>
                <p class="section-subtitle">Showcasing our best projects</p>
            </div>

            <div class="portfolio-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="web">Web</button>
                <button class="filter-btn" data-filter="marketing">Marketing</button>
                <button class="filter-btn" data-filter="video">Video</button>
                <button class="filter-btn" data-filter="design">Design</button>
            </div>

            <div class="portfolio-slider">
                <div class="portfolio-container">
                    <!-- Portfolio items will be added here -->
                </div>
                <div class="slider-buttons">
                    <button class="slider-btn prev-btn">&lt;</button>
                    <button class="slider-btn next-btn">&gt;</button>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Portfolio items data
        const portfolioItems = [
            { title: "E-commerce Website", description: "Full-stack development", category: "web", image: "https://via.placeholder.com/300x200", link: "#", caseStudy: "#" },
            { title: "Digital Marketing Campaign", description: "Social media strategy", category: "marketing", image: "https://via.placeholder.com/300x200", link: "#", caseStudy: "#" },
            { title: "Product Video", description: "Promotional video", category: "video", image: "https://via.placeholder.com/300x200", link: "#", caseStudy: "#" },
            { title: "Brand Identity Design", description: "Complete brand identity", category: "design", image: "https://via.placeholder.com/300x200", link: "#", caseStudy: "#" },
            { title: "Mobile App Development", description: "Cross-platform app", category: "web", image: "https://via.placeholder.com/300x200", link: "#", caseStudy: "#" }
        ];

        const container = document.querySelector('.portfolio-container');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        let currentFilter = 'all';
        let currentSlideIndex = 0;

        // Function to render portfolio items
        function renderItems(filter) {
            container.innerHTML = ''; // Clear previous items
            const filteredItems = portfolioItems.filter(item => filter === 'all' || item.category === filter);

            filteredItems.forEach((item, index) => {
                const slide = document.createElement('div');
                slide.className = `portfolio-item ${index === 0 ? 'active' : ''}`;
                slide.innerHTML = `
                <div class="portfolio-card">
                    <div class="portfolio-image">
                        <img src="${item.image}" alt="${item.title}">
                        <div class="portfolio-overlay">
                            <a href="${item.link}" class="portfolio-btn">Visit</a>
                        </div>
                    </div>
                    <div class="portfolio-content">
                        <h3>${item.title}</h3>
                        <p>${item.description}</p>
                        <a href="${item.caseStudy}" class="read-more">Read Case Study</a>
                    </div>
                </div>
            `;
                container.appendChild(slide);
            });
        }

        // Function to handle filtering
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                currentFilter = button.getAttribute('data-filter');
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                currentSlideIndex = 0;
                renderItems(currentFilter);
            });
        });

        // Slider navigation
        function showSlide(index) {
            const slides = document.querySelectorAll('.portfolio-item');
            slides.forEach((slide, idx) => {
                slide.style.display = idx === index ? 'block' : 'none';
            });
        }

        prevBtn.addEventListener('click', () => {
            const slides = document.querySelectorAll('.portfolio-item');
            currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
            showSlide(currentSlideIndex);
        });

        nextBtn.addEventListener('click', () => {
            const slides = document.querySelectorAll('.portfolio-item');
            currentSlideIndex = (currentSlideIndex + 1) % slides.length;
            showSlide(currentSlideIndex);
        });

        // Initial render
        renderItems(currentFilter);
        showSlide(currentSlideIndex);
    </script>

    <style>
        .portfolio-item {
            display: none;
        }

        .portfolio-item.active {
            display: block;
        }

        .slider-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
    </style>



    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Client Testimonials</h2>
                <p class="section-subtitle">What our clients say about us</p>
            </div>

            <div class="testimonials-grid">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>"Working with Soni Builders was an excellent experience. Their team's expertise and
                            dedication resulted in a website that exceeded our expectations."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="/api/placeholder/60/60" alt="John Smith">
                        </div>
                        <div class="author-info">
                            <h4>John Smith</h4>
                            <p>CEO, Tech Solutions</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <button class="blog-btn">Read Blog</button>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>"The team delivered exceptional work on time and within budget. Highly recommend Soni
                            Builders for their professionalism."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="/api/placeholder/60/60" alt="Emily Johnson">
                        </div>
                        <div class="author-info">
                            <h4>Emily Johnson</h4>
                            <p>Marketing Head, Bright Ideas</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <button class="blog-btn">Read Blog</button>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>"I was impressed with the creativity and skill Soni Builders brought to our project. The
                            results were outstanding!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="/api/placeholder/60/60" alt="Michael Lee">
                        </div>
                        <div class="author-info">
                            <h4>Michael Lee</h4>
                            <p>Founder, Innovate Inc.</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <button class="blog-btn">Read Blog</button>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>"Exceptional service! They truly care about their clients and deliver quality work every
                            time."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="/api/placeholder/60/60" alt="Sarah Brown">
                        </div>
                        <div class="author-info">
                            <h4>Sarah Brown</h4>
                            <p>COO, Future Works</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <button class="blog-btn">Read Blog</button>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 5 -->
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>"Soni Builders transformed our ideas into reality. The results have had a huge positive
                            impact on our business."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="/api/placeholder/60/60" alt="Chris White">
                        </div>
                        <div class="author-info">
                            <h4>Chris White</h4>
                            <p>Manager, Smart Enterprises</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <button class="blog-btn">Read Blog</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        const testimonials = [
            {
                quote: "Working with Soni Builders was an excellent experience. Their team's expertise and dedication resulted in a website that exceeded our expectations.",
                author: "John Smith",
                position: "CEO, Tech Solutions",
                image: "/api/placeholder/60/60"
            },
            {
                quote: "The team delivered exceptional work on time and within budget. Highly recommend Soni Builders for their professionalism.",
                author: "Emily Johnson",
                position: "Marketing Head, Bright Ideas",
                image: "/api/placeholder/60/60"
            },
            {
                quote: "I was impressed with the creativity and skill Soni Builders brought to our project. The results were outstanding!",
                author: "Michael Lee",
                position: "Founder, Innovate Inc.",
                image: "/api/placeholder/60/60"
            },
            {
                quote: "Exceptional service! They truly care about their clients and deliver quality work every time.",
                author: "Sarah Brown",
                position: "COO, Future Works",
                image: "/api/placeholder/60/60"
            },
            {
                quote: "Soni Builders transformed our ideas into reality. The results have had a huge positive impact on our business.",
                author: "Chris White",
                position: "Manager, Smart Enterprises",
                image: "/api/placeholder/60/60"
            }
        ];

        let currentSlide = 0;
        const container = document.querySelector('.testimonial-container');
        const dotsContainer = document.querySelector('.slider-dots');
        let autoSlideInterval;

        // Create testimonial slides
        testimonials.forEach((testimonial, index) => {
            const slide = document.createElement('div');
            slide.className = 'testimonial-item';
            slide.innerHTML = `
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="testimonial-content">
                        <p>${testimonial.quote}</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="${testimonial.image}" alt="${testimonial.author}">
                        </div>
                        <div class="author-info">
                            <h4>${testimonial.author}</h4>
                            <p>${testimonial.position}</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <button class="blog-btn">Read Blog</button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(slide);
        });

        // Create dots
        testimonials.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = `dot ${index === 0 ? 'active' : ''}`;
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        function updateSlider() {
            container.style.transform = `translateX(-${currentSlide * 100}%)`;
            // Update dots
            document.querySelectorAll('.dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
            resetAutoSlide();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % testimonials.length;
            updateSlider();
            resetAutoSlide();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + testimonials.length) % testimonials.length;
            updateSlider();
            resetAutoSlide();
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(nextSlide, 5000);
        }

        // Add button event listeners
        document.querySelector('.prev-btn').addEventListener('click', prevSlide);
        document.querySelector('.next-btn').addEventListener('click', nextSlide);

        // Start auto-sliding
        startAutoSlide();

        // Pause auto-sliding when hovering over the slider
        const slider = document.querySelector('.testimonial-slider');
        slider.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
        slider.addEventListener('mouseleave', startAutoSlide);
    </script>

    <!-- Newsletter Section -->

    <!-- Enhanced Footer Section -->
    <footer class="footer">
        <div class="footer-wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#DC2626" fill-opacity="1"
                    d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,128C672,128,768,160,864,176C960,192,1056,192,1152,176C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>

        <div class="container">
            <div class="footer-content">
                <!-- Company Info Section -->
                <div class="footer-section company-info" data-aos="fade-right">
                    <h3>Soni Builders</h3>
                    <p>Building dreams into reality since 2020. Your trusted partner in construction and
                        development.
                    </p>
                    <div class="company-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-count="500">0</span>
                            <span class="stat-label">Projects</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="100">0</span>
                            <span class="stat-label">Clients</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="50">0</span>
                            <span class="stat-label">Awards</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <div class="footer-section quick-links" data-aos="fade-up">
                    <h3>Quick Links</h3>
                    <div class="links-grid">
                        <div class="links-column">
                            <h4>Services</h4>
                            <ul>
                                <li><a href="services.php#residential">Residential</a></li>
                                <li><a href="services.php#commercial">Commercial</a></li>
                                <li><a href="services.php#renovation">Renovation</a></li>
                                <li><a href="services.php#interior">Interior Design</a></li>
                            </ul>
                        </div>
                        <div class="links-column">
                            <h4>Company</h4>
                            <ul>
                                <li><a href="about.php">About Us</a></li>
                                <li><a href="team.php">Our Team</a></li>
                                <li><a href="careers.php">Careers</a></li>
                                <li><a href="blog.php">Blog</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="footer-section contact-info" data-aos="fade-left">
                    <h3>Get in Touch</h3>
                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h4>Visit Us</h4>
                                <p>123 Construction Ave<br>Builder's District, City 12345</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h4>Call Us</h4>
                                <p>+1 (234) 567-8900<br>+1 (234) 567-8901</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h4>Email Us</h4>
                                <p>info@sonibuilders.com<br>support@sonibuilders.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="#" class="social-link facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link linkedin"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="footer-newsletter" data-aos="fade-up">
                <h3>Subscribe to Our Newsletter</h3>
                <p>Stay updated with our latest projects and news</p>
                <form class="newsletter-form" id="newsletterForm">
                    <div class="form-group">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit">Subscribe</button>
                    </div>
                </form>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-legal">
                    <p>&copy; 2025 Soni Builders. All rights reserved.</p>
                    <ul class="legal-links">
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                        <li><a href="#">Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="script.js"></script>
    <!-- carasole -->
    <script>// Select elements
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.dot');

        let currentIndex = 0; // Current slide index
        const slideInterval = 3000; // Interval in milliseconds

        // Function to show a specific slide
        function showSlide(index) {
            // Hide all slides
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                dots[i].classList.remove('active');
            });

            // Show the current slide
            slides[index].classList.add('active');
            dots[index].classList.add('active');
        }

        // Function to go to the next slide
        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length; // Loop back to the first slide
            showSlide(currentIndex);
        }

        // Start the carousel
        setInterval(nextSlide, slideInterval);

        // Add event listeners for dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentIndex = index; // Set the current index to the clicked dot
                showSlide(currentIndex);
            });
        });
    </script>
</body>

</html>
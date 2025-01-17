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
                <a href="index.php">Soni Builders</a>
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
                <li class="cta-button"><a href="quote.php">Book</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 data-aos="fade-up">Building Ideas, Crafting Dreams</h1>
            <p data-aos="fade-up" data-aos-delay="200">Your vision, our expertise - creating excellence together.</p>
            <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
                <a href="services.php" class="btn btn-primary">Explore Services</a>
                <a href="contact.php" class="btn btn-secondary">Contact Us</a>
            </div>
        </div>
        <div class="hero-overlay"></div>
        <video autoplay muted loop class="hero-video">
            <source src="videos/v1.mp4" type="video/mp4">
        </video>
    </section>

    <section class="services" id="services">
        <style>
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

        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Our Services</h2>
                <p class="section-subtitle">Comprehensive solutions for your digital needs</p>
            </div>

            <div class="services-slider">
                <div class="services-grid">
                    <!-- Web Development -->
                    <div class="service-card">
                        <div class="service-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#3498db">
                                <path
                                    d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-2-1h-6v-2h6v2zm-8-6h8v2h-8zm-2 4h-2v-2h2zm0-4h-2v-2h2z" />
                            </svg>
                        </div>
                        <div class="service-content">
                            <h3>Web Development</h3>
                            <p>Custom websites and web applications built with modern technologies.</p>
                            <ul class="service-features">
                                <li>Responsive Design</li>
                                <li>E-commerce Solutions</li>
                                <li>CMS Integration</li>
                            </ul>
                            <a href="services.php#web" class="service-link">Learn More <span>→</span></a>
                        </div>
                    </div>
                    <!-- Web Development -->
                    <div class="service-card">
                        <div class="service-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#3498db">
                                <path
                                    d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10z" />
                                <path d="M8 13h3v3H8zm5-9h3v3h-3zM8 9h3v3H8z" />
                            </svg>
                        </div>
                        <div class="service-content">
                            <h3>Web Development</h3>
                            <p>Custom websites and web applications built with modern technologies.</p>
                            <ul class="service-features">
                                <li>Responsive Design</li>
                                <li>E-commerce Solutions</li>
                                <li>CMS Integration</li>
                            </ul>
                            <a href="services.php#web" class="service-link">Learn More <span>→</span></a>
                        </div>
                    </div>

                    <!-- Digital Marketing -->
                    <div class="service-card">
                        <div class="service-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#3498db">
                                <path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z" />
                                <path d="M16 6l2.29-2.29L20.59 6 22 4.59 19.41 2 16 5.41 18.59 8 20 6.59z" />
                            </svg>
                        </div>
                        <div class="service-content">
                            <h3>Digital Marketing</h3>
                            <p>Strategic marketing solutions to grow your online presence.</p>
                            <ul class="service-features">
                                <li>SEO Optimization</li>
                                <li>Social Media Marketing</li>
                                <li>Content Strategy</li>
                            </ul>
                            <a href="services.php#marketing" class="service-link">Learn More <span>→</span></a>
                        </div>
                    </div>

                    <!-- Video Editing -->
                    <div class="service-card">
                        <div class="service-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#3498db">
                                <path
                                    d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z" />
                            </svg>
                        </div>
                        <div class="service-content">
                            <h3>Video Editing</h3>
                            <p>Professional video editing and post-production services.</p>
                            <ul class="service-features">
                                <li>Motion Graphics</li>
                                <li>Color Grading</li>
                                <li>Sound Design</li>
                            </ul>
                            <a href="services.php#video" class="service-link">Learn More <span>→</span></a>
                        </div>
                    </div>

                    <!-- Graphic Design -->
                    <div class="service-card">
                        <div class="service-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#3498db">
                                <path
                                    d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
                            </svg>
                        </div>
                        <div class="service-content">
                            <h3>Graphic Design</h3>
                            <p>Creative design solutions for your brand identity.</p>
                            <ul class="service-features">
                                <li>Brand Identity</li>
                                <li>Print Design</li>
                                <li>UI/UX Design</li>
                            </ul>
                            <a href="services.php#design" class="service-link">Learn More <span>→</span></a>
                        </div>
                    </div>

                    <!-- Content Creation -->
                    <div class="service-card">
                        <div class="service-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#3498db">
                                <path
                                    d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                            </svg>
                        </div>
                        <div class="service-content">
                            <h3>Content Creation</h3>
                            <p>Engaging content that tells your brand story.</p>
                            <ul class="service-features">
                                <li>Copywriting</li>
                                <li>Blog Posts</li>
                                <li>Social Media Content</li>
                            </ul>
                            <a href="services.php#content" class="service-link">Learn More <span>→</span></a>
                        </div>
                    </div>

                    <!-- App Development -->
                    <div class="service-card">
                        <div class="service-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#3498db">
                                <path
                                    d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z" />
                                <path d="M12 17c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1z" />
                            </svg>
                        </div>
                        <div class="service-content">
                            <h3>App Development</h3>
                            <p>Native and cross-platform mobile applications.</p>
                            <ul class="service-features">
                                <li>iOS Development</li>
                                <li>Android Development</li>
                                <li>Flutter Apps</li>
                            </ul>
                            <a href="services.php#app" class="service-link">Learn More <span>→</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Similar cards for other services... -->
        </div>
        </div>

        <div class="slider-controls">
            <span class="slider-dot active"></span>
            <span class="slider-dot"></span>
        </div>
        </div>

        <script>
            // Slider functionality
            const slider = document.querySelector('.services-grid');
            const cards = document.querySelectorAll('.service-card');
            const dots = document.querySelectorAll('.slider-dot');
            let currentSlide = 0;
            const cardsPerSlide = 3;
            const totalSlides = Math.ceil(cards.length / cardsPerSlide);

            function updateSlider() {
                const offset = -currentSlide * (100 / totalSlides);
                slider.style.transform = `translateX(${offset}%)`;

                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
            }

            // Auto-slide every 3 seconds
            setInterval(nextSlide, 3000);

            // Click handlers for dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    updateSlider();
                });
            });
        </script>
    </section>

    <section class="portfolio" id="portfolio">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">Our Work</h2>
                <p class="section-subtitle">Showcasing our best projects</p>
            </div>

            <div class="portfolio-filters" data-aos="fade-up">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="web">Web</button>
                <button class="filter-btn" data-filter="marketing">Marketing</button>
                <button class="filter-btn" data-filter="video">Video</button>
                <button class="filter-btn" data-filter="design">Design</button>
            </div>

            <div class="portfolio-slider">
                <div class="portfolio-container">
                    <!-- Will be populated by JavaScript -->
                </div>
                <div class="slider-buttons">
                    <button class="slider-btn prev-btn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="slider-btn next-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="slider-dots">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </section>
    <script>
        const portfolioItems = [
            {
                title: "E-commerce Website",
                description: "Full-stack development with modern technologies",
                category: "web",
                image: "/api/placeholder/800/600",
                link: "#",
                caseStudy: "blog.php?project=ecommerce"
            },
            {
                title: "Digital Marketing Campaign",
                description: "Comprehensive social media marketing strategy",
                category: "marketing",
                image: "/api/placeholder/800/600",
                link: "#",
                caseStudy: "blog.php?project=marketing"
            },
            {
                title: "Product Video",
                description: "Engaging promotional video content",
                category: "video",
                image: "/api/placeholder/800/600",
                link: "#",
                caseStudy: "blog.php?project=video"
            },
            {
                title: "Brand Identity Design",
                description: "Complete brand identity and guidelines",
                category: "design",
                image: "/api/placeholder/800/600",
                link: "#",
                caseStudy: "blog.php?project=design"
            },
            {
                title: "Mobile App Development",
                description: "Cross-platform mobile application",
                category: "web",
                image: "/api/placeholder/800/600",
                link: "#",
                caseStudy: "blog.php?project=mobile"
            }
        ];

        let currentSlide = 0;
        let currentFilter = 'all';
        const container = document.querySelector('.portfolio-container');
        const dotsContainer = document.querySelector('.slider-dots');
        let autoSlideInterval;

        // Create portfolio items
        portfolioItems.forEach(item => {
            const slide = document.createElement('div');
            slide.className = `portfolio-item ${item.category}`;
            slide.setAttribute('data-category', item.category);
            slide.innerHTML = `
                <div class="portfolio-card">
                    <div class="portfolio-image">
                        <img src="${item.image}" alt="${item.title}">
                        <div class="portfolio-overlay">
                            <div class="portfolio-buttons">
                                <a href="${item.link}" class="portfolio-btn"><i class="fas fa-link"></i></a>
                                <a href="#" class="portfolio-btn"><i class="fas fa-search"></i></a>
                            </div>
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

        // Create dots
        portfolioItems.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = `dot ${index === 0 ? 'active' : ''}`;
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        function updateSlider() {
            container.style.transform = `translateX(-${currentSlide * 100}%)`;
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
            const visibleItems = Array.from(document.querySelectorAll('.portfolio-item:not(.hidden)'));
            if (visibleItems.length > 0) {
                currentSlide = (currentSlide + 1) % visibleItems.length;
                updateSlider();
            }
            resetAutoSlide();
        }

        function prevSlide() {
            const visibleItems = Array.from(document.querySelectorAll('.portfolio-item:not(.hidden)'));
            if (visibleItems.length > 0) {
                currentSlide = (currentSlide - 1 + visibleItems.length) % visibleItems.length;
                updateSlider();
            }
            resetAutoSlide();
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(nextSlide, 5000);
        }

        function filterItems(category) {
            currentFilter = category;
            currentSlide = 0;
            document.querySelectorAll('.portfolio-item').forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                if (category === 'all' || itemCategory === category) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
            updateSlider();

            // Update filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-filter') === category);
            });
        }

        // Add event listeners
        document.querySelector('.prev-btn').addEventListener('click', prevSlide);
        document.querySelector('.next-btn').addEventListener('click', nextSlide);

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => filterItems(btn.getAttribute('data-filter')));
        });

        // Pause auto-sliding when hovering over the slider
        const slider = document.querySelector('.portfolio-slider');
        slider.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
        slider.addEventListener('mouseleave', startAutoSlide);

        // Start auto-sliding
        startAutoSlide();
    </script>


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
                        <p>"Working with Soni Builders was an excellent experience. Their team's expertise and dedication resulted in a website that exceeded our expectations."</p>
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
                        <p>"The team delivered exceptional work on time and within budget. Highly recommend Soni Builders for their professionalism."</p>
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
                        <p>"I was impressed with the creativity and skill Soni Builders brought to our project. The results were outstanding!"</p>
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
                        <p>"Exceptional service! They truly care about their clients and deliver quality work every time."</p>
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
                        <p>"Soni Builders transformed our ideas into reality. The results have had a huge positive impact on our business."</p>
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
                    <p>Building dreams into reality since 2020. Your trusted partner in construction and development.
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
                        <li><a href="sitemap.php">Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="script.js"></script>
</body>

</html>
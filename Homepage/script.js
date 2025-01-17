// Initialize AOS Animation
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
});

// Navigation Toggle
const navToggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');

navToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

// Sticky Header
window.addEventListener('scroll', () => {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.classList.add('sticky');
    } else {
        header.classList.remove('sticky');
    }
});

// Testimonial Slider
class TestimonialSlider {
    constructor() {
        this.slider = document.getElementById('testimonialSlider');
        this.testimonials = [
            {
                text: "Soni Builders transformed our vision into reality. The attention to detail was impressive!",
                author: "John Smith",
                role: "CEO, Tech Corp",
            },
            {
                text: "Outstanding service and professional team. Highly recommended!",
                author: "Sarah Johnson",
                role: "Marketing Director",
            },
        ];
        this.currentSlide = 0;
        this.init();
    }

    init() {
        this.renderSlides();
        this.startAutoPlay();
    }

    renderSlides() {
        this.slider.innerHTML = this.testimonials.map((testimonial, index) => `
            <div class="testimonial-slide ${index === this.currentSlide ? 'active' : ''}">
                <p>${testimonial.text}</p>
                <div class="testimonial-author">
                    <h4>${testimonial.author}</h4>
                    <span>${testimonial.role}</span>
                </div>
            </div>
        `).join('');
    }

    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.testimonials.length;
        this.renderSlides();
    }

    startAutoPlay() {
        setInterval(() => this.nextSlide(), 5000);
    }
}

new TestimonialSlider();

// Newsletter Form
const newsletterForm = document.getElementById('newsletterForm');

if (newsletterForm) {
    newsletterForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = e.target.querySelector('input[type="email"]').value;

        try {
            const response = await fetch('includes/newsletter.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email }),
            });

            const data = await response.json();

            if (data.success) {
                showNotification('Success! Thank you for subscribing.', 'success');
                e.target.reset();
            } else {
                showNotification('Oops! Something went wrong.', 'error');
            }
        } catch (error) {
            showNotification('Error submitting form. Please try again.', 'error');
        }
    });
}

// Footer Animations and Counter
document.addEventListener('DOMContentLoaded', () => {
    // Animate stats when in viewport
    const stats = document.querySelectorAll('.stat-number');

    const animateValue = (element, start, end, duration) => {
        let startTimestamp = null;

        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (end - start) + start);
            element.textContent = current;

            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };

        window.requestAnimationFrame(step);
    };

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const endValue = parseInt(element.getAttribute('data-count'));
                animateValue(element, 0, endValue, 2000);
                statsObserver.unobserve(element);
            }
        });
    }, { threshold: 0.5 });

    stats.forEach(stat => statsObserver.observe(stat));

    // Particle Background Animation for Footer
    const createParticle = () => {
        const particle = document.createElement('div');
        particle.className = 'footer-particle';
        document.querySelector('.footer').appendChild(particle);

        const x = Math.random() * window.innerWidth;
        const y = Math.random() * 300;
        const size = Math.random() * 5 + 2;

        particle.style.cssText = `
            left: ${x}px;
            bottom: ${y}px;
            width: ${size}px;
            height: ${size}px;
            opacity: ${Math.random()};
        `;

        setTimeout(() => particle.remove(), 5000);
    };

    setInterval(createParticle, 300);

    // Smooth Scroll for Footer Links
    const footerLinks = document.querySelectorAll('.footer a[href^="#"]');
    footerLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});

// Notification System
const showNotification = (message, type) => {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <p>${message}</p>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
};

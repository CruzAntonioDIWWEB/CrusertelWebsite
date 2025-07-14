/* ================================== */
/* CRUSERTEL - CONSOLIDATED JAVASCRIPT */
/* ================================== */

document.addEventListener('DOMContentLoaded', () => {
    
    // ===================================
    // COMMON ANIMATION FUNCTIONS
    // ===================================
    
    /**
     * Animate elements with delay
     */
    function animateElement(element, delay) {
        if (!element) return;
        setTimeout(() => {
            element.classList.add('animated-element');
        }, delay);
    }

    /**
     * Initialize all animated elements
     */
    function initAnimations() {
        const allAnimated = document.querySelectorAll(
            '.fade-in-up-initial, .fade-in-down-initial, .slide-in-from-left-initial, .pop-in-initial'
        );

        allAnimated.forEach((el, index) => {
            animateElement(el, 300 + index * 150);
        });
    }

    // ===================================
    // HEADER SCROLL EFFECT
    // ===================================
    
    /**
     * Add scrolled class to header when scrolling
     */
    function initHeaderScroll() {
        const header = document.querySelector('header');
        if (!header) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });
    }

    // ===================================
    // SERVICE ITEMS HOVER EFFECTS
    // ===================================
    
    /**
     * Add pulsing effect to service items on hover
     */
    function initServiceItemsEffects() {
        const serviceItems = document.querySelectorAll('.service-item');
        
        serviceItems.forEach((item) => {
            let pulseInterval;

            item.addEventListener('mouseenter', () => {
                item.classList.add('pulsing');
                pulseInterval = setInterval(() => {
                    item.classList.toggle('pulsing');
                }, 800);
            });

            item.addEventListener('mouseleave', () => {
                item.classList.remove('pulsing');
                clearInterval(pulseInterval);
            });
        });
    }

    // ===================================
    // IMAGE HOVER EFFECTS
    // ===================================
    
    /**
     * Add grow effect to images with specific class
     */
    function initImageHoverEffects() {
        const hoverImages = document.querySelectorAll('.img-hover-grow');
        
        hoverImages.forEach(image => {
            image.style.transition = 'transform 0.4s ease';

            image.addEventListener('mouseenter', () => {
                image.style.transform = 'scale(1.1)';
            });

            image.addEventListener('mouseleave', () => {
                image.style.transform = 'scale(1)';
            });
        });
    }

    // ===================================
    // MOBILE MENU FUNCTIONALITY
    // ===================================
    
    /**
     * Initialize mobile menu toggle
     */
    function initMobileMenu() {
        const menuToggle = document.getElementById('menu-toggle');
        const mainNav = document.querySelector('.main-nav');

        if (menuToggle && mainNav) {
            menuToggle.addEventListener('click', function() {
                mainNav.classList.toggle('menu-abierto');
            });
        }
    }

    // ===================================
    // TARIFAS MODAL FUNCTIONALITY
    // ===================================
    
    /**
     * Initialize modal for tarifa images
     */
    function initTarifasModal() {
        const imagenes = document.querySelectorAll('.tarifa img');
        const modal = document.getElementById('modal');
        const modalImg = document.getElementById('imgAmpliada');
        const cerrar = document.querySelector('.cerrar');

        if (!modal || !modalImg || imagenes.length === 0) return;

        // Open modal when clicking on tarifa images
        imagenes.forEach(img => {
            img.addEventListener('click', () => {
                modal.style.display = 'block';
                modalImg.src = img.src;
                modalImg.alt = img.alt;
            });
        });

        // Close modal when clicking close button
        if (cerrar) {
            cerrar.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }

        // Close modal when clicking outside of it
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display === 'block') {
                modal.style.display = 'none';
            }
        });
    }

    // ===================================
    // CONTACT FORM FUNCTIONALITY
    // ===================================
    
    /**
     * Initialize contact form submission
     */
    function initContactForm() {
        const contactForm = document.querySelector('#contact-form form');
        
        if (!contactForm) return;

        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Get form data
            const formData = new FormData(contactForm);
            const data = {
                nombre: formData.get('nombre'),
                email: formData.get('email'),
                telefono: formData.get('telefono'),
                asunto: formData.get('asunto'),
                mensaje: formData.get('mensaje')
            };

            // Basic validation
            if (!data.nombre || !data.email || !data.telefono || !data.asunto || !data.mensaje) {
                alert('Por favor, completa todos los campos.');
                return;
            }

            if (!isValidEmail(data.email)) {
                alert('Por favor, ingresa un email válido.');
                return;
            }

            try {
                // Here you would typically send to your server
                // For now, we'll just show a success message
                alert('Mensaje enviado correctamente. Te contactaremos pronto.');
                contactForm.reset();
            } catch (error) {
                alert('Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.');
                console.error('Error:', error);
            }
        });
    }

    /**
     * Validate email format
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // ===================================
    // FAQ FUNCTIONALITY
    // ===================================
    
    /**
     * Initialize FAQ accordion functionality
     */
    function initFAQAccordion() {
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('h3');
            const answer = item.querySelector('.faq-respuesta');
            
            if (question && answer) {
                question.style.cursor = 'pointer';
                
                // Toggle answer on click instead of hover
                question.addEventListener('click', () => {
                    const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
                    
                    // Close all other FAQ items
                    faqItems.forEach(otherItem => {
                        const otherAnswer = otherItem.querySelector('.faq-respuesta');
                        if (otherAnswer && otherAnswer !== answer) {
                            otherAnswer.style.maxHeight = '0px';
                            otherAnswer.style.opacity = '0';
                        }
                    });
                    
                    // Toggle current item
                    if (isOpen) {
                        answer.style.maxHeight = '0px';
                        answer.style.opacity = '0';
                    } else {
                        answer.style.maxHeight = '500px';
                        answer.style.opacity = '1';
                    }
                });
            }
        });
    }

    // ===================================
    // SMOOTH SCROLLING
    // ===================================
    
    /**
     * Initialize smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        
        anchorLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const targetId = link.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // ===================================
    // FORM ENHANCEMENTS
    // ===================================
    
    /**
     * Add focus/blur effects to form inputs
     */
    function initFormEnhancements() {
        const formInputs = document.querySelectorAll('input, textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.parentElement.classList.remove('focused');
                }
            });
        });
    }

    // ===================================
    // BACK TO TOP BUTTON
    // ===================================
    
    /**
     * Initialize back to top button
     */
    function initBackToTop() {
        // Create back to top button
        const backToTopBtn = document.createElement('button');
        backToTopBtn.innerHTML = '↑';
        backToTopBtn.className = 'back-to-top';
        backToTopBtn.setAttribute('aria-label', 'Volver arriba');
        
        // Style the button
        Object.assign(backToTopBtn.style, {
            position: 'fixed',
            bottom: '20px',
            right: '20px',
            backgroundColor: '#dc3545',
            color: 'white',
            border: 'none',
            borderRadius: '50%',
            width: '50px',
            height: '50px',
            fontSize: '20px',
            cursor: 'pointer',
            display: 'none',
            zIndex: '1000',
            transition: 'all 0.3s ease'
        });

        document.body.appendChild(backToTopBtn);

        // Show/hide button based on scroll position
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        // Scroll to top when clicked
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Hover effects
        backToTopBtn.addEventListener('mouseenter', () => {
            backToTopBtn.style.backgroundColor = '#b1001d';
            backToTopBtn.style.transform = 'scale(1.1)';
        });

        backToTopBtn.addEventListener('mouseleave', () => {
            backToTopBtn.style.backgroundColor = '#dc3545';
            backToTopBtn.style.transform = 'scale(1)';
        });
    }

    // ===================================
    // LOADING INDICATOR
    // ===================================
    
    /**
     * Hide loading indicator when page is ready
     */
    function hideLoadingIndicator() {
        const loader = document.querySelector('.loading-indicator');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        }
    }

    // ===================================
    // INITIALIZE ALL FUNCTIONS
    // ===================================
    
    // Initialize all functionality
    initAnimations();
    initHeaderScroll();
    initServiceItemsEffects();
    initImageHoverEffects();
    initMobileMenu();
    initTarifasModal();
    initContactForm();
    initFAQAccordion();
    initSmoothScrolling();
    initFormEnhancements();
    initBackToTop();
    hideLoadingIndicator();

    // ===================================
    // PAGE-SPECIFIC FUNCTIONALITY
    // ===================================
    
    // Get current page from URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get('page') || 'home';

    // Initialize page-specific functionality
    switch(currentPage) {
        case 'tarifas':
            // Additional tarifas-specific code if needed
            console.log('Tarifas page loaded');
            break;
        case 'contact':
            // Additional contact-specific code if needed
            console.log('Contact page loaded');
            break;
        case 'faq':
            // FAQ page already has accordion functionality
            console.log('FAQ page loaded');
            break;
        case 'services':
            // Additional services-specific code if needed
            console.log('Services page loaded');
            break;
        case 'unete':
            // Additional unete-specific code if needed
            console.log('Unete page loaded');
            break;
        default:
            // Home page
            console.log('Home page loaded');
            break;
    }
});

// ===================================
// UTILITY FUNCTIONS
// ===================================

/**
 * Debounce function to limit function calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Check if element is in viewport
 */
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

/**
 * Format phone number
 */
function formatPhoneNumber(phoneNumber) {
    const cleaned = phoneNumber.replace(/\D/g, '');
    const match = cleaned.match(/^(\d{3})(\d{2})(\d{2})(\d{2})$/);
    if (match) {
        return `${match[1]} ${match[2]} ${match[3]} ${match[4]}`;
    }
    return phoneNumber;
}
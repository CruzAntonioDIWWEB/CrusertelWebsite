document.addEventListener('DOMContentLoaded', () => {
    function animateElement(element, delay) {
        if (!element) return;
        setTimeout(() => {
            element.classList.add('animated-element');
        }, delay);
    }

    const allAnimated = document.querySelectorAll(
        '.fade-in-up-initial, .fade-in-down-initial, .slide-in-from-left-initial, .pop-in-initial'
    );

    allAnimated.forEach((el, index) => {
        animateElement(el, 300 + index * 150);
    });

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

    // 🔽 Nuevo: Añadir clase al header al hacer scroll
    const header = document.querySelector('header');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
    });

    // 🔽 Animación para la imagen con clase img-hover-grow
    const hoverImage = document.querySelector('.img-hover-grow');
    if (hoverImage) {
        hoverImage.style.transition = 'transform 0.4s ease';

        hoverImage.addEventListener('mouseenter', () => {
            hoverImage.style.transform = 'scale(1.1)';
        });

        hoverImage.addEventListener('mouseleave', () => {
            hoverImage.style.transform = 'scale(1)';
        });
    }
});